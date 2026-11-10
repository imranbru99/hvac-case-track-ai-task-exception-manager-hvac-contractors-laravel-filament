<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Escalation extends Model
{
    protected $fillable = [
        'escalation_number',
        'task_id',
        'exception_id',
        'from_user_id',
        'to_user_id',
        'level',
        'reason',
        'status',
        'acknowledged_at',
        'resolved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'acknowledged_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Escalation $escalation): void {
            if (blank($escalation->escalation_number)) {
                $escalation->escalation_number = 'ESC-'.strtoupper(Str::random(8));
            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function exception(): BelongsTo
    {
        return $this->belongsTo(CaseException::class, 'exception_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
