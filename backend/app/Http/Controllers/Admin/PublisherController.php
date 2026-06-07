<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::withCount('books');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $publishers = $query->orderBy('name')->paginate(15);

        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:publishers,name',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Publisher::create($validated);

        return redirect()->route('admin.publishers.index')->with('success', 'Publisher created successfully.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $publisher->update($validated);

        return redirect()->route('admin.publishers.index')->with('success', 'Publisher updated successfully.');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return back()->with('error', 'Cannot delete a publisher that has books in the catalog.');
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')->with('success', 'Publisher deleted successfully.');
    }
}
