<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $authors = $query->orderBy('name')->paginate(15);

        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'bio'       => 'nullable|string|max:2000',
            'birth_year'=> 'nullable|integer',
            'death_year'=> 'nullable|integer|gte:birth_year',
        ]);

        Author::create($validated);

        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'bio'       => 'nullable|string|max:2000',
            'birth_year'=> 'nullable|integer',
            'death_year'=> 'nullable|integer|gte:birth_year',
        ]);

        $author->update($validated);

        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->count() > 0) {
            return back()->with('error', 'Cannot delete an author that has books in the catalog.');
        }

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully.');
    }
}
