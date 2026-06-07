<?php

namespace App\Services;

use App\Models\Fine;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FineService
{
    /**
     * Get paginated fines with filters.
     */
    public function getFines(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Fine::with(['transaction.bookCopy.book', 'member.user', 'payments']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->whereHas('member.user', fn ($q) => $q->where('name', 'like', "%{$term}%"));
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * Record a payment against a fine.
     */
    public function recordPayment(Fine $fine, float $amount, string $paymentMethod = 'cash', ?string $reference = null): Payment
    {
        return DB::transaction(function () use ($fine, $amount, $paymentMethod, $reference) {
            if ($fine->status === 'paid') {
                throw new \Exception('This fine has already been fully paid.');
            }

            $remaining = $fine->remaining_amount;

            if ($amount > $remaining) {
                throw new \Exception("Payment amount (\${$amount}) exceeds remaining balance (\${$remaining}).");
            }

            $payment = Payment::create([
                'fine_id'        => $fine->id,
                'amount'         => $amount,
                'payment_method' => $paymentMethod,
                'reference'      => $reference,
                'paid_at'        => now(),
                'received_by'    => Auth::id(),
            ]);

            // Update fine status
            $newRemaining = $remaining - $amount;
            $fine->update([
                'status' => $newRemaining <= 0 ? 'paid' : 'partial',
            ]);

            Notification::create([
                'user_id'           => $fine->member->user_id,
                'title'             => 'Payment Received',
                'message'           => "Payment of \${$amount} received for fine #{$fine->id}. " .
                    ($newRemaining > 0 ? "Remaining: \${$newRemaining}" : "Fine fully paid."),
                'notification_type' => 'payment_received',
            ]);

            return $payment;
        });
    }

    /**
     * Waive a fine.
     */
    public function waiveFine(Fine $fine, ?string $reason = null): Fine
    {
        if ($fine->status === 'paid') {
            throw new \Exception('Cannot waive an already paid fine.');
        }

        $fine->update(['status' => 'waived']);

        Notification::create([
            'user_id'           => $fine->member->user_id,
            'title'             => 'Fine Waived',
            'message'           => "Your fine of \${$fine->total_amount} has been waived." .
                ($reason ? " Reason: {$reason}" : ''),
            'notification_type' => 'fine_waived',
        ]);

        return $fine->fresh();
    }

    /**
     * Get fine statistics for dashboard.
     */
    public function getStats(?int $branchId = null): array
    {
        $query = Fine::query();

        if ($branchId) {
            $query->whereHas('transaction.bookCopy', fn ($q) => $q->where('branch_id', $branchId));
        }

        return [
            'total_fines'     => $query->count(),
            'unpaid_count'    => (clone $query)->unpaid()->count(),
            'unpaid_amount'   => (clone $query)->unpaid()->sum('total_amount'),
            'paid_amount'     => (clone $query)->paid()->sum('total_amount'),
            'waived_count'    => (clone $query)->where('status', 'waived')->count(),
        ];
    }
}
