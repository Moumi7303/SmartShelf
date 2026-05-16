<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('transactions.view');
    }

    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->member && $user->member->id === $transaction->member_id) return true; // View own
        return $user->hasPermission('transactions.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('transactions.create');
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->hasPermission('transactions.edit');
    }

    public function return(User $user, Transaction $transaction): bool
    {
        return $user->hasPermission('transactions.return');
    }

    public function renew(User $user, Transaction $transaction): bool
    {
        if ($user->member && $user->member->id === $transaction->member_id) return true; // Renew own
        return $user->hasPermission('transactions.renew');
    }
}
