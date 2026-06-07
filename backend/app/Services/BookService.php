<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookService
{
    /**
     * Get paginated books with filters.
     */
    public function getBooks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Book::with(['category', 'author', 'publisher']);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        if (!empty($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new book with auto-generated barcode.
     */
    public function createBook(array $data): Book
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['barcode'])) {
                $data['barcode'] = Book::generateBarcode();
            }

            $book = Book::create($data);

            // Handle cover image upload
            if (isset($data['cover_image_file'])) {
                $path = $data['cover_image_file']->store('book-covers', 'public');
                $book->update(['cover_image' => $path]);
            }

            return $book->load(['category', 'author', 'publisher']);
        });
    }

    /**
     * Update a book.
     */
    public function updateBook(Book $book, array $data): Book
    {
        return DB::transaction(function () use ($book, $data) {
            if (isset($data['cover_image_file'])) {
                // Delete old cover
                if ($book->cover_image) {
                    \Storage::disk('public')->delete($book->cover_image);
                }
                $path = $data['cover_image_file']->store('book-covers', 'public');
                $data['cover_image'] = $path;
            }
            unset($data['cover_image_file']);

            $book->update($data);
            return $book->fresh(['category', 'author', 'publisher']);
        });
    }

    /**
     * Delete a book (soft delete).
     */
    public function deleteBook(Book $book): bool
    {
        return $book->delete();
    }

    /**
     * Check availability of a book across branches.
     */
    public function checkAvailability(Book $book, ?int $branchId = null): array
    {
        $query = $book->copies();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $total = $query->count();
        $available = (clone $query)->where('availability_status', 'available')->count();

        return [
            'total_copies'     => $total,
            'available_copies' => $available,
            'is_available'     => $available > 0,
        ];
    }

    /**
     * Get book statistics for dashboard.
     */
    public function getStats(?int $branchId = null): array
    {
        $bookQuery = Book::query();
        $copyQuery = BookCopy::query();

        if ($branchId) {
            $bookQuery->byBranch($branchId);
            $copyQuery->where('branch_id', $branchId);
        }

        return [
            'total_titles'    => $bookQuery->count(),
            'total_copies'    => $copyQuery->count(),
            'available'       => (clone $copyQuery)->where('availability_status', 'available')->count(),
            'checked_out'     => (clone $copyQuery)->where('availability_status', 'checked_out')->count(),
            'reserved'        => (clone $copyQuery)->where('availability_status', 'reserved')->count(),
            'damaged'         => (clone $copyQuery)->where('availability_status', 'damaged')->count(),
        ];
    }
}
