<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::with('manager');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $branches = $query->withCount(['users', 'bookCopies'])->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $managers = User::whereHas('role', fn ($q) => $q->whereIn('name', ['super_admin', 'branch_admin']))
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.branches.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|max:20|unique:branches,code',
            'address'    => 'nullable|string|max:500',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'status'     => 'required|in:active,inactive',
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $branch->load(['manager', 'users.role', 'bookCopies']);
        $stats = [
            'total_users'  => $branch->users()->count(),
            'total_copies' => $branch->bookCopies()->count(),
            'available'    => $branch->bookCopies()->where('availability_status', 'available')->count(),
        ];

        return view('admin.branches.show', compact('branch', 'stats'));
    }

    public function edit(Branch $branch)
    {
        $managers = User::whereHas('role', fn ($q) => $q->whereIn('name', ['super_admin', 'branch_admin']))
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.branches.edit', compact('branch', 'managers'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|max:20|unique:branches,code,' . $branch->id,
            'address'    => 'nullable|string|max:500',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'status'     => 'required|in:active,inactive',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0 || $branch->bookCopies()->count() > 0) {
            return back()->with('error', 'Cannot delete a branch that has users or book copies assigned.');
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
