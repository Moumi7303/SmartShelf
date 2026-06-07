<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookService $bookService) {}

    public function index(Request $request)
    {
        $books = $this->bookService->getBooks($request->only(['search', 'category_id', 'branch_id', 'status', 'sort_by', 'sort_dir']));
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('admin.books.create', compact('categories', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'isbn'             => 'nullable|string|max:20|unique:books,isbn',
            'category_id'      => 'required|exists:categories,id',
            'author_id'        => 'required|exists:authors,id',
            'publisher_id'     => 'nullable|exists:publishers,id',
            'edition'          => 'nullable|string|max:50',
            'language'         => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'description'      => 'nullable|string|max:2000',
            'shelf_location'   => 'nullable|string|max:100',
            'status'           => 'required|in:available,unavailable',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $this->bookService->createBook($validated);

        return redirect()->route('admin.books.index')->with('success', 'Book added to catalog successfully.');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'author', 'publisher', 'copies.branch', 'ebooks', 'reservations.member.user']);
        $availability = $this->bookService->checkAvailability($book);

        return view('admin.books.show', compact('book', 'availability'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories', 'authors', 'publishers'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'isbn'             => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'category_id'      => 'required|exists:categories,id',
            'author_id'        => 'required|exists:authors,id',
            'publisher_id'     => 'nullable|exists:publishers,id',
            'edition'          => 'nullable|string|max:50',
            'language'         => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'description'      => 'nullable|string|max:2000',
            'shelf_location'   => 'nullable|string|max:100',
            'status'           => 'required|in:available,unavailable',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $this->bookService->updateBook($book, $validated);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $activeCopies = $book->copies()->where('availability_status', 'checked_out')->count();
        if ($activeCopies > 0) {
            return back()->with('error', 'Cannot delete a book that has copies currently checked out.');
        }

        $this->bookService->deleteBook($book);

        return redirect()->route('admin.books.index')->with('success', 'Book removed from catalog.');
    }
}
