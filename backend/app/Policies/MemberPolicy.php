<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('members.view');
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->member && $user->member->id === $member->id) return true; // View own membership
        return $user->hasPermission('members.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('members.create');
    }

    public function update(User $user, Member $member): bool
    {
        if ($user->member && $user->member->id === $member->id) return true; // Update own membership basics
        return $user->hasPermission('members.edit');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasPermission('members.delete');
    }
}
