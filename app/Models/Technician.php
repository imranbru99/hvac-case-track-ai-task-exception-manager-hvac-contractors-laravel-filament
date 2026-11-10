<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'license_number',
        'specialties',
        'availability_status',
        'service_area',
        'phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobTickets(): HasMany
    {
        return $this->hasMany(JobTicket::class);
    }
}
