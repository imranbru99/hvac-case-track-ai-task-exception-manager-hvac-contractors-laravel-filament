<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CaseException extends Model
{
    protected $table = 'exceptions';

    protected $fillable = [
        'exception_number',
        'task_id',
        'opened_by',
        'owner_id',
        'type',
        'severity',
        'status',
        'title',
        'details',
        'resolution',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CaseException $exception): void {
            if (blank($exception->exception_number)) {
                $exception->exception_number = 'EXC-'.strtoupper(Str::random(8));
            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function opener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function escalations(): HasMany
    {
        return $this->hasMany(Escalation::class, 'exception_id');
    }
}
