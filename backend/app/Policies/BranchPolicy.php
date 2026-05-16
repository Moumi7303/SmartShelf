<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('branches.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        if ($user->hasRole('super_admin')) return true;
        
        return $user->hasPermission('branches.view') && $user->branch_id === $branch->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('branches.create');
    }

    public function update(User $user, Branch $branch): bool
    {
        if ($user->hasRole('super_admin')) return true;
        
        return $user->hasPermission('branches.edit') && $user->branch_id === $branch->id;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.delete') && $user->hasRole('super_admin');
    }
}
