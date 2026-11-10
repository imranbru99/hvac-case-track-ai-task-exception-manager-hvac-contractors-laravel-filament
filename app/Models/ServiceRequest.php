<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServiceRequest extends Model
{
    protected $fillable = [
        'request_number',
        'task_id',
        'customer_id',
        'customer_name',
        'address',
        'service_type',
        'priority',
        'status',
        'scheduled_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceRequest $request): void {
            if (blank($request->request_number)) {
                $request->request_number = 'SR-'.strtoupper(Str::random(8));
            }
        });
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function jobTickets(): HasMany
    {
        return $this->hasMany(JobTicket::class);
    }
}
