<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CaseMessage extends Model
{
    protected $fillable = [
        'reference',
        'channel',
        'direction',
        'from_name',
        'from_email',
        'from_phone',
        'subject',
        'body',
        'ai_category',
        'ai_priority',
        'ai_score',
        'ai_summary',
        'ai_tags',
        'suggested_sla_hours',
        'status',
        'customer_id',
        'assigned_to',
        'consent_sms',
        'consent_email',
        'raw_payload',
        'classified_at',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'ai_tags' => 'array',
            'raw_payload' => 'array',
            'consent_sms' => 'boolean',
            'consent_email' => 'boolean',
            'classified_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CaseMessage $message): void {
            if (blank($message->reference)) {
                $message->reference = 'MSG-'.strtoupper(Str::random(8));
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function latestDraft(): HasOne
    {
        return $this->hasOne(AiTaskDraft::class)->latestOfMany();
    }

    public function drafts(): HasMany
    {
        return $this->hasMany(AiTaskDraft::class);
    }
}
