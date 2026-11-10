<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiTaskDraft extends Model
{
    protected $fillable = [
        'case_message_id',
        'created_by',
        'provider',
        'model',
        'suggested_title',
        'suggested_description',
        'suggested_category',
        'suggested_priority',
        'suggested_sla_hours',
        'response',
        'confidence',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'response' => 'array',
        ];
    }

    public function caseMessage(): BelongsTo
    {
        return $this->belongsTo(CaseMessage::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
