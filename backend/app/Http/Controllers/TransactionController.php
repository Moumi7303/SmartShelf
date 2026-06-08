<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\BookCopy;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['member', 'bookCopy.book']);

        if ($request->has('member_id')) {
            $query->byMember($request->member_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_copy_id' => 'required|exists:book_copies,id',
        ]);

        $copy = BookCopy::findOrFail($request->book_copy_id);

        if ($copy->availability_status !== 'available') {
            return response()->json(['message' => 'Book copy is not available for checkout.'], 400);
        }

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateTransactionCode(),
            'member_id' => $request->member_id,
            'book_copy_id' => $copy->id,
            'issued_by' => $request->user()->id,
            'issue_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(14), // Standard 14-day checkout
            'status' => 'issued',
        ]);

        $copy->update(['availability_status' => 'checked_out']);

        return response()->json($transaction->load('bookCopy.book'), 201);
    }

    public function returnBook(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'returned') {
            return response()->json(['message' => 'Transaction already returned.'], 400);
        }

        $fineAmount = $transaction->calculateFine();

        if ($fineAmount > 0) {
            Fine::create([
                'member_id' => $transaction->member_id,
                'transaction_id' => $transaction->id,
                'total_amount' => $fineAmount,
                'status' => 'unpaid',
            ]);
        }

        $transaction->update([
            'status' => 'returned',
            'return_date' => Carbon::today(),
            'returned_to' => $request->user()->id,
        ]);

        $transaction->bookCopy->update(['availability_status' => 'available']);

        return response()->json(['message' => 'Returned successfully.', 'fine_amount' => $fineAmount]);
    }
}
