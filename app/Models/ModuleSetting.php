<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'category',
        'enabled',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'meta' => 'array',
        ];
    }
}
