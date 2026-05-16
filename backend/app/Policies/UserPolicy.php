<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) return true; // View self
        if ($user->hasRole('super_admin')) return true;
        
        return $user->hasPermission('users.view') && $user->branch_id === $model->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) return true; // Update self
        if ($user->hasRole('super_admin')) return true;
        
        // Cannot update super admins if you are not one
        if ($model->hasRole('super_admin')) return false;

        return $user->hasPermission('users.edit') && $user->branch_id === $model->branch_id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) return false; // Cannot delete self
        if ($model->hasRole('super_admin')) return false;

        return $user->hasPermission('users.delete');
    }
}
