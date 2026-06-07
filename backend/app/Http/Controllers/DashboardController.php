<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Branch;
use App\Models\Fine;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BookService;
use App\Services\FineService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private BookService $bookService,
        private FineService $fineService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $branchId = $user->isSuperAdmin() ? null : $user->branch_id;

        return match ($user->role?->name) {
            'super_admin'    => $this->superAdminDashboard(),
            'branch_admin'   => $this->branchAdminDashboard($branchId),
            'librarian'      => $this->librarianDashboard($branchId),
            'student_member' => $this->studentDashboard($user),
            default          => $this->guestDashboard(),
        };
    }

    private function superAdminDashboard()
    {
        $stats = [
            'total_books'        => Book::count(),
            'total_copies'       => BookCopy::count(),
            'total_members'      => Member::count(),
            'total_users'        => User::count(),
            'total_branches'     => Branch::count(),
            'active_loans'       => Transaction::where('status', 'issued')->count(),
            'overdue_loans'      => Transaction::overdue()->count(),
            'pending_reservations' => Reservation::pending()->count(),
            'unpaid_fines'       => Fine::unpaid()->sum('total_amount'),
            'total_fine_collected' => Fine::paid()->sum('total_amount'),
        ];

        $recentTransactions = Transaction::with(['member.user', 'bookCopy.book'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $branches = Branch::withCount(['users', 'bookCopies'])->get();

        // Monthly circulation data for chart
        $monthlyData = $this->getMonthlyCirculationData();

        return view('dashboard.super-admin', compact('stats', 'recentTransactions', 'branches', 'monthlyData'));
    }

    private function branchAdminDashboard(int $branchId)
    {
        $stats = [
            'total_copies'       => BookCopy::where('branch_id', $branchId)->count(),
            'available_copies'   => BookCopy::where('branch_id', $branchId)->where('availability_status', 'available')->count(),
            'active_loans'       => Transaction::whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))->where('status', 'issued')->count(),
            'overdue_loans'      => Transaction::whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))->overdue()->count(),
            'branch_members'     => User::where('branch_id', $branchId)->count(),
            'unpaid_fines'       => Fine::whereHas('transaction.bookCopy', fn ($q) => $q->where('branch_id', $branchId))->unpaid()->sum('total_amount'),
        ];

        $recentTransactions = Transaction::with(['member.user', 'bookCopy.book'])
            ->whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.branch-admin', compact('stats', 'recentTransactions'));
    }

    private function librarianDashboard(int $branchId)
    {
        $stats = [
            'active_loans'       => Transaction::whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))->where('status', 'issued')->count(),
            'overdue_loans'      => Transaction::whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))->overdue()->count(),
            'pending_reservations' => Reservation::pending()->count(),
            'returns_today'      => Transaction::whereDate('return_date', Carbon::today())->where('status', 'returned')->count(),
            'issues_today'       => Transaction::whereDate('issue_date', Carbon::today())->count(),
        ];

        $overdueLoans = Transaction::with(['member.user', 'bookCopy.book'])
            ->overdue()
            ->whereHas('bookCopy', fn ($q) => $q->where('branch_id', $branchId))
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        $pendingReservations = Reservation::with(['member.user', 'book'])
            ->pending()
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard.librarian', compact('stats', 'overdueLoans', 'pendingReservations'));
    }

    private function studentDashboard(User $user)
    {
        $member = $user->member;

        $stats = [
            'active_loans'      => 0,
            'overdue_loans'     => 0,
            'reservations'      => 0,
            'unpaid_fines'      => 0,
        ];

        $myLoans = collect();
        $myReservations = collect();
        $myFines = collect();

        if ($member) {
            $stats['active_loans'] = Transaction::where('member_id', $member->id)->whereIn('status', ['issued', 'overdue'])->count();
            $stats['overdue_loans'] = Transaction::where('member_id', $member->id)->overdue()->count();
            $stats['reservations'] = Reservation::where('member_id', $member->id)->active()->count();
            $stats['unpaid_fines'] = Fine::where('member_id', $member->id)->unpaid()->sum('total_amount');

            $myLoans = Transaction::with(['bookCopy.book'])
                ->where('member_id', $member->id)
                ->whereIn('status', ['issued', 'overdue'])
                ->orderBy('due_date', 'asc')
                ->get();

            $myReservations = Reservation::with(['book'])
                ->where('member_id', $member->id)
                ->active()
                ->get();

            $myFines = Fine::with(['transaction.bookCopy.book'])
                ->where('member_id', $member->id)
                ->unpaid()
                ->get();
        }

        return view('dashboard.student', compact('stats', 'myLoans', 'myReservations', 'myFines', 'member'));
    }

    private function guestDashboard()
    {
        $stats = [
            'total_books' => Book::count(),
            'categories'  => \App\Models\Category::count(),
        ];

        $recentBooks = Book::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard.guest', compact('stats', 'recentBooks'));
    }

    private function getMonthlyCirculationData(): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $months[] = [
                'month'   => $date->format('M Y'),
                'issued'  => Transaction::whereYear('issue_date', $date->year)->whereMonth('issue_date', $date->month)->count(),
                'returned' => Transaction::whereYear('return_date', $date->year)->whereMonth('return_date', $date->month)->where('status', 'returned')->count(),
            ];
        }
        return $months;
    }
}
