<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            static::logAudit('created', $model);
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                static::logAudit('updated', $model, $model->getChanges(), $model->getOriginal());
            }
        });

        static::deleted(function ($model) {
            static::logAudit('deleted', $model);
        });
    }

    protected static function logAudit(string $action, $model, array $newValues = [], array $oldValues = []): void
    {
        try {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'model_type'  => get_class($model),
                'model_id'    => $model->getKey(),
                'old_values'  => !empty($oldValues) ? json_encode(
                    array_intersect_key($oldValues, $newValues)
                ) : null,
                'new_values'  => !empty($newValues) ? json_encode($newValues) : null,
                'ip_address'  => request()?->ip(),
                'user_agent'  => substr(request()?->userAgent() ?? '', 0, 500),
            ]);
        } catch (\Throwable $e) {
            // Silently fail — audit logging should never break the app
            logger()->warning('Audit log failed: ' . $e->getMessage());
        }
    }
}
