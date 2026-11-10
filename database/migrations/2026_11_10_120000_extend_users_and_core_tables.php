<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('phone');
        });

        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('core');
            $table->boolean('enabled')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip', 20)->nullable();
            $table->text('notes')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('case_messages', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->enum('channel', ['email', 'sms', 'phone', 'portal', 'ops_note', 'jobber', 'servicetitan'])->default('email');
            $table->enum('direction', ['inbound', 'internal'])->default('inbound');
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('ai_category')->nullable();
            $table->string('ai_priority')->nullable();
            $table->unsignedTinyInteger('ai_score')->nullable();
            $table->text('ai_summary')->nullable();
            $table->json('ai_tags')->nullable();
            $table->unsignedInteger('suggested_sla_hours')->nullable();
            $table->enum('status', ['new', 'classified', 'pending_approval', 'approved', 'rejected', 'tasked'])->default('new');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('consent_sms')->default(false);
            $table->boolean('consent_email')->default(false);
            $table->json('raw_payload')->nullable();
            $table->timestamp('classified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number')->unique();
            $table->foreignId('case_message_id')->nullable()->constrained('case_messages')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('priority')->default('normal');
            $table->enum('status', ['draft', 'open', 'in_progress', 'blocked', 'resolved', 'cancelled'])->default('draft');
            $table->unsignedInteger('sla_hours')->default(24);
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('sla_breached')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('exceptions', function (Blueprint $table) {
            $table->id();
            $table->string('exception_number')->unique();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('sla_breach');
            $table->string('severity')->default('high');
            $table->enum('status', ['open', 'investigating', 'escalated', 'resolved', 'closed'])->default('open');
            $table->string('title');
            $table->text('details')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('escalations', function (Blueprint $table) {
            $table->id();
            $table->string('escalation_number')->unique();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exception_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('reason');
            $table->enum('status', ['pending', 'acknowledged', 'resolved', 'cancelled'])->default('pending');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('address')->nullable();
            $table->string('service_type')->nullable();
            $table->string('priority')->default('normal');
            $table->enum('status', ['intake', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('intake');
            $table->timestamp('scheduled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('license_number')->nullable();
            $table->json('specialties')->nullable();
            $table->string('availability_status')->default('available');
            $table->string('service_area')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('job_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('service_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['draft', 'dispatched', 'on_site', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            $table->text('completion_notes')->nullable();
            $table->json('safety_checklist')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_task_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_message_id')->constrained('case_messages')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('provider')->default('local-rules');
            $table->string('model')->default('hvac-case-v1');
            $table->string('suggested_title');
            $table->text('suggested_description')->nullable();
            $table->string('suggested_category')->nullable();
            $table->string('suggested_priority')->nullable();
            $table->unsignedInteger('suggested_sla_hours')->default(24);
            $table->json('response')->nullable();
            $table->unsignedTinyInteger('confidence')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_task_drafts');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('job_tickets');
        Schema::dropIfExists('technicians');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('escalations');
        Schema::dropIfExists('exceptions');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('case_messages');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('module_settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'is_active']);
        });
    }
};
