<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Role;

class RoleObserver
{
    public function created(Role $role): void
    {
        AuditLog::log('created', 'roles', null, null, $role->toArray());
    }

    public function updated(Role $role): void
    {
        AuditLog::log('updated', 'roles', null, $role->getOriginal(), $role->getChanges());
    }

    public function deleted(Role $role): void
    {
        AuditLog::log('deleted', 'roles', null, $role->toArray(), null);
    }
}
