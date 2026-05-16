<?php

namespace App\Policies;

use App\Models\Fine;
use App\Models\User;

class FinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('fines.view');
    }

    public function view(User $user, Fine $fine): bool
    {
        if ($user->member && $user->member->id === $fine->member_id) return true; // View own
        return $user->hasPermission('fines.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('fines.create');
    }

    public function update(User $user, Fine $fine): bool
    {
        return $user->hasPermission('fines.edit');
    }

    public function waive(User $user, Fine $fine): bool
    {
        return $user->hasPermission('fines.waive');
    }
}
