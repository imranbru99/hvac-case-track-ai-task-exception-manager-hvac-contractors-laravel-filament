<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'notes',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer): void {
            if (blank($customer->slug)) {
                $customer->slug = app(SlugService::class)->unique($customer->name, 'customers');
            }
        });
    }

    public function caseMessages(): HasMany
    {
        return $this->hasMany(CaseMessage::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
