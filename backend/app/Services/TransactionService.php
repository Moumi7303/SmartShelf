<?php

namespace App\Services;

use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Member;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Get paginated transactions with filters.
     */
    public function getTransactions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Transaction::with(['member.user', 'bookCopy.book', 'issuedByUser', 'fines']);

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('transaction_code', 'like', "%{$term}%")
                  ->orWhereHas('member.user', fn ($mq) => $mq->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('bookCopy.book', fn ($bq) => $bq->where('title', 'like', "%{$term}%"));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['branch_id'])) {
            $query->whereHas('bookCopy', fn ($q) => $q->where('branch_id', $filters['branch_id']));
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    /**
     * Issue a book to a member.
     */
    public function issueBook(int $memberId, int $bookCopyId): Transaction
    {
        return DB::transaction(function () use ($memberId, $bookCopyId) {
            $member = Member::findOrFail($memberId);
            $bookCopy = BookCopy::findOrFail($bookCopyId);
            $maxLoans = (int) Setting::getValue('max_loans_per_member', 5);
            $loanDays = (int) Setting::getValue('loan_period_days', 14);
            $blockOnFine = Setting::getValue('block_on_unpaid_fine', true);

            // Validate member status
            if ($member->membership_status !== 'active') {
                throw new \Exception('Member does not have an active membership.');
            }

            // Check unpaid fines
            if ($blockOnFine && $member->total_unpaid_fines > 0) {
                throw new \Exception('Member has unpaid fines. Please clear outstanding fines first.');
            }

            // Check max loan limit
            $activeLoans = Transaction::where('member_id', $memberId)
                ->whereIn('status', ['issued', 'overdue'])
                ->count();

            if ($activeLoans >= $maxLoans) {
                throw new \Exception("Member has reached the maximum loan limit ({$maxLoans}).");
            }

            // Check book copy availability
            if ($bookCopy->availability_status !== 'available') {
                throw new \Exception('This book copy is not available for lending.');
            }

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'member_id'        => $memberId,
                'book_copy_id'     => $bookCopyId,
                'issued_by'        => Auth::id(),
                'issue_date'       => Carbon::today(),
                'due_date'         => Carbon::today()->addDays($loanDays),
                'renewal_count'    => 0,
                'status'           => 'issued',
            ]);

            // Update book copy status
            $bookCopy->update(['availability_status' => 'checked_out']);

            // Create notification for the member
            Notification::create([
                'user_id'           => $member->user_id,
                'title'             => 'Book Issued',
                'message'           => "Book \"{$bookCopy->book->title}\" has been issued to you. Due date: {$transaction->due_date->format('M d, Y')}.",
                'notification_type' => 'book_issued',
            ]);

            return $transaction->load(['member.user', 'bookCopy.book']);
        });
    }

    /**
     * Return a book.
     */
    public function returnBook(Transaction $transaction, ?string $remarks = null): Transaction
    {
        return DB::transaction(function () use ($transaction, $remarks) {
            if ($transaction->status === 'returned') {
                throw new \Exception('This book has already been returned.');
            }

            $returnDate = Carbon::today();
            $isOverdue = $transaction->due_date->lt($returnDate);

            $transaction->update([
                'return_date'  => $returnDate,
                'returned_to'  => Auth::id(),
                'status'       => 'returned',
                'remarks'      => $remarks,
            ]);

            // Update book copy status
            $transaction->bookCopy->update(['availability_status' => 'available']);

            // Auto-generate fine if overdue
            if ($isOverdue) {
                $dailyRate = (float) Setting::getValue('daily_fine_rate', 1.00);
                $maxFine = (float) Setting::getValue('max_fine_amount', 50.00);
                $overdueDays = $transaction->due_date->diffInDays($returnDate);
                $totalAmount = min($overdueDays * $dailyRate, $maxFine);

                Fine::create([
                    'transaction_id' => $transaction->id,
                    'member_id'      => $transaction->member_id,
                    'overdue_days'   => $overdueDays,
                    'daily_rate'     => $dailyRate,
                    'total_amount'   => $totalAmount,
                    'status'         => 'unpaid',
                ]);

                // Notify member about fine
                Notification::create([
                    'user_id'           => $transaction->member->user_id,
                    'title'             => 'Overdue Fine',
                    'message'           => "A fine of \${$totalAmount} has been charged for returning \"{$transaction->bookCopy->book->title}\" {$overdueDays} days late.",
                    'notification_type' => 'fine_generated',
                ]);
            }

            // Notification for return
            Notification::create([
                'user_id'           => $transaction->member->user_id,
                'title'             => 'Book Returned',
                'message'           => "Book \"{$transaction->bookCopy->book->title}\" has been returned successfully.",
                'notification_type' => 'book_returned',
            ]);

            return $transaction->fresh(['member.user', 'bookCopy.book', 'fines']);
        });
    }

    /**
     * Renew a loan.
     */
    public function renewLoan(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {
            $maxRenewals = (int) Setting::getValue('max_renewals', 2);
            $renewalDays = (int) Setting::getValue('renewal_period_days', 7);

            if ($transaction->status !== 'issued') {
                throw new \Exception('Only active loans can be renewed.');
            }

            if ($transaction->renewal_count >= $maxRenewals) {
                throw new \Exception("Maximum renewals ({$maxRenewals}) reached.");
            }

            // Check if there are pending reservations for this book
            $hasPendingReservation = $transaction->bookCopy->book->reservations()
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($hasPendingReservation) {
                throw new \Exception('This book has pending reservations and cannot be renewed.');
            }

            $transaction->update([
                'due_date'      => $transaction->due_date->addDays($renewalDays),
                'renewal_count' => $transaction->renewal_count + 1,
            ]);

            Notification::create([
                'user_id'           => $transaction->member->user_id,
                'title'             => 'Loan Renewed',
                'message'           => "Your loan for \"{$transaction->bookCopy->book->title}\" has been renewed. New due date: {$transaction->due_date->format('M d, Y')}.",
                'notification_type' => 'loan_renewed',
            ]);

            return $transaction->fresh(['member.user', 'bookCopy.book']);
        });
    }

    /**
     * Get circulation statistics for dashboard.
     */
    public function getStats(?int $branchId = null): array
    {
        $query = Transaction::query();

        if ($branchId) {
            $query->whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId));
        }

        return [
            'total_transactions' => $query->count(),
            'active_loans'       => (clone $query)->where('status', 'issued')->count(),
            'overdue'            => (clone $query)->overdue()->count(),
            'returned_today'     => (clone $query)->where('status', 'returned')->whereDate('return_date', Carbon::today())->count(),
            'issued_today'       => (clone $query)->whereDate('issue_date', Carbon::today())->count(),
        ];
    }

    /**
     * Mark overdue transactions. Called by scheduled command.
     */
    public function markOverdueTransactions(): int
    {
        $overdue = Transaction::where('status', 'issued')
            ->where('due_date', '<', Carbon::today())
            ->get();

        foreach ($overdue as $transaction) {
            $transaction->update(['status' => 'overdue']);

            Notification::create([
                'user_id'           => $transaction->member->user_id,
                'title'             => 'Book Overdue',
                'message'           => "Your book \"{$transaction->bookCopy->book->title}\" is overdue. Please return it as soon as possible to avoid fines.",
                'notification_type' => 'overdue_notice',
            ]);
        }

        return $overdue->count();
    }
}
