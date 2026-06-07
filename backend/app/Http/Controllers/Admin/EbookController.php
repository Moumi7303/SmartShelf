<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::with('book');

        if ($search = $request->input('search')) {
            $query->whereHas('book', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $ebooks = $query->paginate(15)->withQueryString();

        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();
        return view('admin.ebooks.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id'      => 'required|exists:books,id',
            'file'         => 'required|file|mimes:pdf,epub|max:51200', // max 50MB
            'format'       => 'required|string|max:20',
            'access_level' => 'required|in:public,member_only',
        ]);

        $file = $request->file('file');
        $path = $file->store('ebooks', 'local'); // Store locally, securely

        Ebook::create([
            'book_id'      => $validated['book_id'],
            'file_path'    => $path,
            'file_size'    => $file->getSize(),
            'format'       => $validated['format'],
            'access_level' => $validated['access_level'],
            'download_count' => 0,
        ]);

        return redirect()->route('admin.ebooks.index')->with('success', 'eBook uploaded successfully.');
    }

    public function destroy(Ebook $ebook)
    {
        if (Storage::disk('local')->exists($ebook->file_path)) {
            Storage::disk('local')->delete($ebook->file_path);
        }

        $ebook->delete();

        return redirect()->route('admin.ebooks.index')->with('success', 'eBook deleted successfully.');
    }

    /**
     * Download the eBook file.
     */
    public function download(Ebook $ebook)
    {
        $user = auth()->user();

        // Check access level
        if ($ebook->access_level === 'member_only' && !$user?->member) {
            if (!$user?->isStaff()) {
                abort(403, 'This eBook is available to members only.');
            }
        }

        if (!Storage::disk('local')->exists($ebook->file_path)) {
            abort(404, 'File not found.');
        }

        $ebook->increment('download_count');

        return Storage::disk('local')->download(
            $ebook->file_path,
            \Str::slug($ebook->book->title) . '.' . $ebook->format
        );
    }
}
