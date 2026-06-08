<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::with('book');
        
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        return response()->json($query->paginate(20));
    }

    public function download(Ebook $ebook)
    {
        // Simple permission check could be added here
        
        if (!Storage::exists($ebook->file_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::download($ebook->file_path, $ebook->book->title . '.pdf');
    }

    public function stream(Ebook $ebook)
    {
        if (!Storage::exists($ebook->file_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::response($ebook->file_path);
    }
}
