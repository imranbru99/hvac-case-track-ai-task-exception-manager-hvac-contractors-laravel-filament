<?php

namespace App\Services;

use App\Models\CaseException;
use App\Models\CaseMessage;
use App\Models\Customer;
use App\Models\Escalation;
use App\Models\ServiceRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CaseWorkflowService
{
    public function __construct(
        protected AiTaskAssistService $assist,
        protected ModuleManager $modules,
        protected AuditLogger $audit,
    ) {}

    public function intake(array $data, ?User $actor = null): CaseMessage
    {
        $message = CaseMessage::query()->create([
            'channel' => $data['channel'] ?? 'email',
            'direction' => $data['direction'] ?? 'inbound',
            'from_name' => $data['from_name'] ?? $data['contact_name'] ?? null,
            'from_email' => $data['from_email'] ?? $data['contact_email'] ?? null,
            'from_phone' => $data['from_phone'] ?? $data['contact_phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'consent_sms' => (bool) ($data['consent_sms'] ?? false),
            'consent_email' => (bool) ($data['consent_email'] ?? false),
            'raw_payload' => $data,
            'status' => 'new',
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        $this->audit->log('message.intake', CaseMessage::class, $message->id, null, $message->toArray(), $actor?->id);

        return $this->assist->classify($message);
    }

    public function approve(CaseMessage $message, User $actor, ?User $assignee = null): Task
    {
        return DB::transaction(function () use ($message, $actor, $assignee) {
            $before = $message->toArray();
            $draft = $message->latestDraft;
            $assignee ??= $actor;

            $customer = null;
            if ($message->from_email || $message->from_phone) {
                $customer = Customer::query()->firstOrCreate(
                    ['email' => $message->from_email ?: 'case+'.$message->id.'@example.local'],
                    [
                        'name' => $message->from_name ?: 'HVAC Customer',
                        'phone' => $message->from_phone,
                        'notes' => 'Auto-created from message '.$message->reference,
                    ]
                );
            }

            $slaHours = (int) ($draft?->suggested_sla_hours ?: $message->suggested_sla_hours ?: 24);

            $task = Task::query()->create([
                'case_message_id' => $message->id,
                'customer_id' => $customer?->id,
                'assignee_id' => $assignee->id,
                'created_by' => $actor->id,
                'title' => $draft?->suggested_title ?: (($message->ai_category ?: 'HVAC task').' — '.($message->from_name ?: 'case')),
                'description' => $draft?->suggested_description ?: $message->ai_summary,
                'category' => $draft?->suggested_category ?: $message->ai_category,
                'priority' => $draft?->suggested_priority ?: $message->ai_priority ?: 'normal',
                'status' => 'open',
                'sla_hours' => $slaHours,
                'sla_due_at' => now()->addHours($slaHours),
            ]);

            $message->update([
                'status' => 'tasked',
                'approved_at' => now(),
                'approved_by' => $actor->id,
                'customer_id' => $customer?->id,
            ]);

            $draft?->update(['status' => 'approved']);

            if ($this->modules->enabled('service_requests')) {
                ServiceRequest::query()->create([
                    'task_id' => $task->id,
                    'customer_id' => $customer?->id,
                    'customer_name' => $message->from_name ?: 'HVAC Customer',
                    'service_type' => $task->category,
                    'priority' => $task->priority,
                    'status' => 'intake',
                    'notes' => $message->body,
                ]);
            }

            $this->audit->log(
                'message.approved_tasked',
                CaseMessage::class,
                $message->id,
                $before,
                ['task_id' => $task->id, 'status' => 'tasked'],
                $actor->id
            );

            return $task;
        });
    }

    public function reject(CaseMessage $message, User $actor, ?string $reason = null): CaseMessage
    {
        $before = $message->toArray();
        $message->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $actor->id,
            'approved_at' => now(),
        ]);
        $message->latestDraft?->update(['status' => 'rejected']);

        $this->audit->log('message.rejected', CaseMessage::class, $message->id, $before, $message->toArray(), $actor->id);

        return $message->fresh();
    }

    public function openException(Task $task, User $actor, array $data = []): CaseException
    {
        $exception = CaseException::query()->create([
            'task_id' => $task->id,
            'opened_by' => $actor->id,
            'owner_id' => $data['owner_id'] ?? $task->assignee_id ?? $actor->id,
            'type' => $data['type'] ?? ($task->isOverdue() ? 'sla_breach' : 'ops_block'),
            'severity' => $data['severity'] ?? ($task->priority === 'urgent' ? 'critical' : 'high'),
            'status' => 'open',
            'title' => $data['title'] ?? ('Exception on '.$task->task_number),
            'details' => $data['details'] ?? $task->description,
        ]);

        if ($task->isOverdue() || ($data['type'] ?? null) === 'sla_breach') {
            $task->update(['sla_breached' => true, 'status' => 'blocked']);
        }

        $this->audit->log('exception.opened', CaseException::class, $exception->id, null, $exception->toArray(), $actor->id);

        if ($this->modules->enabled('escalation_engine') && ($data['escalate'] ?? true)) {
            $this->escalate($task, $actor, [
                'exception_id' => $exception->id,
                'reason' => $exception->title,
                'to_user_id' => $data['escalate_to'] ?? null,
            ]);
        }

        return $exception;
    }

    public function escalate(Task $task, User $actor, array $data = []): Escalation
    {
        $owner = User::query()->where('role', 'owner')->first();
        $toUserId = $data['to_user_id'] ?? $owner?->id ?? $actor->id;
        $priorLevel = Escalation::query()->where('task_id', $task->id)->max('level') ?? 0;

        $escalation = Escalation::query()->create([
            'task_id' => $task->id,
            'exception_id' => $data['exception_id'] ?? null,
            'from_user_id' => $actor->id,
            'to_user_id' => $toUserId,
            'level' => $priorLevel + 1,
            'reason' => $data['reason'] ?? 'SLA / exception escalation',
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        $this->audit->log('task.escalated', Escalation::class, $escalation->id, null, $escalation->toArray(), $actor->id);

        return $escalation;
    }

    public function resolveTask(Task $task, User $actor, ?string $notes = null): Task
    {
        $before = $task->toArray();
        $task->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $notes,
            'first_response_at' => $task->first_response_at ?? now(),
        ]);

        $this->audit->log('task.resolved', Task::class, $task->id, $before, $task->toArray(), $actor->id);

        return $task->fresh();
    }
}
