<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /**
     * Get paginated reservations with filters.
     */
    public function getReservations(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Reservation::with(['member.user', 'book']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->whereHas('book', fn ($bq) => $bq->where('title', 'like', "%{$term}%"))
                  ->orWhereHas('member.user', fn ($mq) => $mq->where('name', 'like', "%{$term}%"));
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * Create a reservation.
     */
    public function createReservation(int $memberId, int $bookId): Reservation
    {
        return DB::transaction(function () use ($memberId, $bookId) {
            $maxReservations = (int) Setting::getValue('max_reservations', 3);

            // Check max active reservations
            $activeCount = Reservation::where('member_id', $memberId)
                ->active()
                ->count();

            if ($activeCount >= $maxReservations) {
                throw new \Exception("Maximum active reservations ({$maxReservations}) reached.");
            }

            // Check if member already has active reservation for this book
            $existing = Reservation::where('member_id', $memberId)
                ->where('book_id', $bookId)
                ->active()
                ->exists();

            if ($existing) {
                throw new \Exception('You already have an active reservation for this book.');
            }

            // Calculate queue position
            $queuePosition = Reservation::where('book_id', $bookId)
                ->active()
                ->count() + 1;

            $reservation = Reservation::create([
                'member_id'        => $memberId,
                'book_id'          => $bookId,
                'reservation_date' => Carbon::today(),
                'expiry_date'      => null, // Set when approved
                'queue_position'   => $queuePosition,
                'status'           => 'pending',
            ]);

            return $reservation->load(['member.user', 'book']);
        });
    }

    /**
     * Approve a reservation.
     */
    public function approveReservation(Reservation $reservation): Reservation
    {
        $expiryDays = (int) Setting::getValue('reservation_expiry_days', 3);

        $reservation->update([
            'status'      => 'approved',
            'expiry_date' => Carbon::today()->addDays($expiryDays),
        ]);

        Notification::create([
            'user_id'           => $reservation->member->user_id,
            'title'             => 'Reservation Approved',
            'message'           => "Your reservation for \"{$reservation->book->title}\" has been approved. Please collect within {$expiryDays} days.",
            'notification_type' => 'reservation_approved',
        ]);

        return $reservation->fresh(['member.user', 'book']);
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation): Reservation
    {
        $reservation->update(['status' => 'cancelled']);

        // Reorder queue positions for remaining reservations
        Reservation::where('book_id', $reservation->book_id)
            ->active()
            ->where('queue_position', '>', $reservation->queue_position)
            ->decrement('queue_position');

        return $reservation->fresh();
    }

    /**
     * Expire overdue reservations. Called by scheduled command.
     */
    public function expireReservations(): int
    {
        $expired = Reservation::where('status', 'approved')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', Carbon::today())
            ->get();

        foreach ($expired as $reservation) {
            $reservation->update(['status' => 'expired']);

            Notification::create([
                'user_id'           => $reservation->member->user_id,
                'title'             => 'Reservation Expired',
                'message'           => "Your reservation for \"{$reservation->book->title}\" has expired as it was not collected in time.",
                'notification_type' => 'reservation_expired',
            ]);
        }

        return $expired->count();
    }
}
