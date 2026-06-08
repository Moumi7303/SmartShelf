<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category', 'publisher']);

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->has('branch_id')) {
            $query->byBranch($request->branch_id);
        }

        $books = $query->paginate($request->get('per_page', 20));

        // Append availability label
        $books->getCollection()->transform(function ($book) {
            $book->availability_label = $book->availability_label;
            return $book;
        });

        return response()->json($books);
    }

    public function show(Book $book)
    {
        $book->load(['author', 'category', 'publisher', 'copies.branch']);
        $book->availability_label = $book->availability_label;
        
        return response()->json($book);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'isbn' => 'nullable|string|max:20',
            'edition' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'shelf_location' => 'nullable|string|max:100',
        ]);

        $book = Book::create(array_merge($validated, [
            'barcode' => Book::generateBarcode()
        ]));

        return response()->json($book, 201);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author_id' => 'sometimes|required|exists:authors,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'publisher_id' => 'sometimes|required|exists:publishers,id',
            'isbn' => 'nullable|string|max:20',
            'edition' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer',
            'description' => 'nullable|string',
            'shelf_location' => 'nullable|string|max:100',
            'status' => 'sometimes|in:available,lost,damaged,maintenance',
        ]);

        $book->update($validated);

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, 204);
    }
}
