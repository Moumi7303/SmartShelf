<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('reservations.view');
    }

    public function view(User $user, Reservation $reservation): bool
    {
        if ($user->member && $user->member->id === $reservation->member_id) return true; // View own
        return $user->hasPermission('reservations.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('reservations.create');
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.edit');
    }

    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->hasPermission('reservations.approve');
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        if ($user->member && $user->member->id === $reservation->member_id) return true; // Cancel own
        return $user->hasPermission('reservations.cancel');
    }
}
