<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function inventory(Request $request)
    {
        $format = $request->input('export', 'html');
        
        if ($format === 'pdf') {
            return back()->with('info', 'PDF Export feature is coming soon.');
        } elseif ($format === 'excel') {
            return back()->with('info', 'Excel Export feature is coming soon.');
        }

        $branches = \App\Models\Branch::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();

        $bookQuery = \App\Models\Book::with(['author', 'category'])->withCount([
            'copies as total_copies' => function ($q) use ($request) {
                if ($request->branch_id) $q->where('branch_id', $request->branch_id);
            },
            'copies as available_copies' => function ($q) use ($request) {
                if ($request->branch_id) $q->where('branch_id', $request->branch_id);
                $q->where('availability_status', 'available');
            },
            'copies as issued_copies' => function ($q) use ($request) {
                if ($request->branch_id) $q->where('branch_id', $request->branch_id);
                $q->where('availability_status', 'issued');
            },
            'copies as lost_copies' => function ($q) use ($request) {
                if ($request->branch_id) $q->where('branch_id', $request->branch_id);
                $q->where('availability_status', 'lost');
            },
            'copies as damaged_copies' => function ($q) use ($request) {
                if ($request->branch_id) $q->where('branch_id', $request->branch_id);
                $q->where('availability_status', 'damaged');
            }
        ]);

        if ($request->category_id) {
            $bookQuery->where('category_id', $request->category_id);
        }

        if ($request->branch_id) {
            $bookQuery->whereHas('copies', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        $inventory = $bookQuery->paginate(15)->withQueryString();

        $inventory->getCollection()->transform(function($book) {
            return (object)[
                'title' => $book->title,
                'author_name' => $book->author->name ?? 'Unknown',
                'category_name' => $book->category->name ?? 'Unknown',
                'total_copies' => $book->total_copies,
                'available_copies' => $book->available_copies,
                'issued_copies' => $book->issued_copies,
                'lost_copies' => $book->lost_copies,
                'damaged_copies' => $book->damaged_copies,
            ];
        });

        $copyQuery = \App\Models\BookCopy::query();
        if ($request->branch_id) $copyQuery->where('branch_id', $request->branch_id);
        if ($request->category_id) $copyQuery->whereHas('book', fn($q) => $q->where('category_id', $request->category_id));

        $stats = [
            'total_books' => $inventory->total(),
            'total_copies' => (clone $copyQuery)->count(),
            'available_copies' => (clone $copyQuery)->where('availability_status', 'available')->count(),
            'unavailable_copies' => (clone $copyQuery)->whereIn('availability_status', ['issued', 'lost', 'damaged'])->count(),
        ];

        return view('admin.reports.inventory', compact('branches', 'categories', 'stats', 'inventory'));
    }

    public function circulation(Request $request)
    {
        // To be implemented fully in Phase 8
        return view('admin.reports.circulation');
    }

    public function fines(Request $request)
    {
        // To be implemented fully in Phase 8
        return view('admin.reports.fines');
    }
}
