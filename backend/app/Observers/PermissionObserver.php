<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Permission;

class PermissionObserver
{
    public function created(Permission $permission): void
    {
        AuditLog::log('created', 'permissions', null, null, $permission->toArray());
    }

    public function updated(Permission $permission): void
    {
        AuditLog::log('updated', 'permissions', null, $permission->getOriginal(), $permission->getChanges());
    }

    public function deleted(Permission $permission): void
    {
        AuditLog::log('deleted', 'permissions', null, $permission->toArray(), null);
    }
}
