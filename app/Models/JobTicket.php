<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class JobTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'service_request_id',
        'technician_id',
        'status',
        'scheduled_start',
        'scheduled_end',
        'completion_notes',
        'safety_checklist',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_start' => 'datetime',
            'scheduled_end' => 'datetime',
            'safety_checklist' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (JobTicket $ticket): void {
            if (blank($ticket->ticket_number)) {
                $ticket->ticket_number = 'JT-'.strtoupper(Str::random(8));
            }
        });
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
