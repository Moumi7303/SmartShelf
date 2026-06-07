<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCopy;
use App\Models\Branch;
use App\Models\Book;
use Illuminate\Http\Request;

class BookCopyController extends Controller
{
    public function index(Request $request)
    {
        $query = BookCopy::with(['book', 'branch']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhereHas('book', fn ($bq) => $bq->where('title', 'like', "%{$search}%"));
            });
        }

        if ($branchId = $request->input('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        if ($status = $request->input('availability_status')) {
            $query->where('availability_status', $status);
        }

        $copies = $query->paginate(20)->withQueryString();
        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.book-copies.index', compact('copies', 'branches'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();
        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.book-copies.create', compact('books', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'             => 'required|exists:books,id',
            'branch_id'           => 'required|exists:branches,id',
            'barcode'             => 'nullable|string|max:50|unique:book_copies,barcode',
            'purchase_date'       => 'nullable|date',
            'price'               => 'nullable|numeric|min:0',
            'condition_note'      => 'nullable|string|max:500',
            'availability_status' => 'required|in:available,checked_out,reserved,lost,damaged,maintenance',
        ]);

        if (empty($validated['barcode'])) {
            $validated['barcode'] = BookCopy::generateBarcode();
        }

        BookCopy::create($validated);

        return redirect()->route('admin.book-copies.index')->with('success', 'Book copy added successfully.');
    }

    public function show(BookCopy $bookCopy)
    {
        $bookCopy->load(['book', 'branch', 'transactions.member.user']);
        return view('admin.book-copies.show', compact('bookCopy'));
    }

    public function edit(BookCopy $bookCopy)
    {
        $books = Book::orderBy('title')->get();
        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.book-copies.edit', compact('bookCopy', 'books', 'branches'));
    }

    public function update(Request $request, BookCopy $bookCopy)
    {
        $validated = $request->validate([
            'book_id'             => 'required|exists:books,id',
            'branch_id'           => 'required|exists:branches,id',
            'barcode'             => 'required|string|max:50|unique:book_copies,barcode,' . $bookCopy->id,
            'purchase_date'       => 'nullable|date',
            'price'               => 'nullable|numeric|min:0',
            'condition_note'      => 'nullable|string|max:500',
            'availability_status' => 'required|in:available,checked_out,reserved,lost,damaged,maintenance',
        ]);

        // Don't allow changing status to available if it's currently checked out
        if ($bookCopy->availability_status === 'checked_out' && $validated['availability_status'] === 'available') {
            return back()->with('error', 'Cannot manually change status to available while copy is checked out. Please process a return instead.');
        }

        $bookCopy->update($validated);

        return redirect()->route('admin.book-copies.index')->with('success', 'Book copy updated successfully.');
    }

    public function destroy(BookCopy $bookCopy)
    {
        if ($bookCopy->availability_status === 'checked_out') {
            return back()->with('error', 'Cannot delete a book copy that is currently checked out.');
        }

        $bookCopy->delete();

        return redirect()->route('admin.book-copies.index')->with('success', 'Book copy deleted successfully.');
    }
}
