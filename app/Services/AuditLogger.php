<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public function __construct(protected ModuleManager $modules) {}

    public function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        mixed $before = null,
        mixed $after = null,
        ?int $userId = null,
        array $meta = []
    ): ?AuditLog {
        if (! $this->modules->enabled('audit_log')) {
            return null;
        }

        return AuditLog::query()->create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before' => $before,
            'after' => $after,
            'ip_address' => Request::ip(),
            'meta' => $meta,
        ]);
    }
}
