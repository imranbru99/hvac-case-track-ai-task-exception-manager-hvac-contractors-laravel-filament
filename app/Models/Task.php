<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $fillable = [
        'task_number',
        'case_message_id',
        'customer_id',
        'assignee_id',
        'created_by',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'sla_hours',
        'sla_due_at',
        'first_response_at',
        'resolved_at',
        'sla_breached',
        'resolution_notes',
    ];

    protected function casts(): array
    {
        return [
            'sla_due_at' => 'datetime',
            'first_response_at' => 'datetime',
            'resolved_at' => 'datetime',
            'sla_breached' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Task $task): void {
            if (blank($task->task_number)) {
                $task->task_number = 'TSK-'.strtoupper(Str::random(8));
            }
        });
    }

    public function caseMessage(): BelongsTo
    {
        return $this->belongsTo(CaseMessage::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(CaseException::class);
    }

    public function escalations(): HasMany
    {
        return $this->hasMany(Escalation::class);
    }

    public function serviceRequest(): HasOne
    {
        return $this->hasOne(ServiceRequest::class);
    }

    public function isOverdue(): bool
    {
        return $this->sla_due_at
            && $this->sla_due_at->isPast()
            && ! in_array($this->status, ['resolved', 'cancelled'], true);
    }
}
