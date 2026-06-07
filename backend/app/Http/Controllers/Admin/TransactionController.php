<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\BookCopy;
use App\Models\Book;
use App\Models\Branch;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    public function index(Request $request)
    {
        $transactions = $this->transactionService->getTransactions(
            $request->only(['search', 'status', 'member_id', 'branch_id'])
        );

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $members = Member::with('user')->where('membership_status', 'active')->get();
        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.transactions.create', compact('members', 'branches'));
    }

    /**
     * Issue a book (create a new loan transaction).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'    => 'required|exists:members,id',
            'book_copy_id' => 'required|exists:book_copies,id',
        ]);

        try {
            $this->transactionService->issueBook($validated['member_id'], $validated['book_copy_id']);
            return redirect()->route('admin.transactions.index')->with('success', 'Book issued successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['member.user', 'bookCopy.book', 'bookCopy.branch', 'issuedByUser', 'returnedToUser', 'fines.payments']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Process a book return.
     */
    public function returnBook(Request $request, Transaction $transaction)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $this->transactionService->returnBook($transaction, $request->input('remarks'));
            return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Book returned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Renew a loan.
     */
    public function renew(Transaction $transaction)
    {
        try {
            $this->transactionService->renewLoan($transaction);
            return redirect()->route('admin.transactions.show', $transaction)->with('success', 'Loan renewed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * AJAX: Get available book copies for a branch.
     */
    public function getAvailableCopies(Request $request)
    {
        $branchId = $request->input('branch_id');
        $search = $request->input('search');

        $query = BookCopy::with('book')
            ->where('availability_status', 'available');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($search) {
            $query->whereHas('book', fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%"));
        }

        $copies = $query->limit(20)->get();

        return response()->json($copies);
    }
}
