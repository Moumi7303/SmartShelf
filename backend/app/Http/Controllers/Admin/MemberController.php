<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['user.role', 'user.branch']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('membership_id', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('membership_status', $status);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $branches = Branch::active()->orderBy('name')->get();
        return view('admin.members.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'nullable|string|max:20',
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'branch_id'  => 'required|exists:branches,id',
            'student_id' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:255',
            'semester'   => 'nullable|string|max:50',
            'address'    => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            $studentRole = Role::where('name', 'student_member')->first();

            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'] ?? null,
                'password'          => Hash::make($validated['password']),
                'role_id'           => $studentRole->id,
                'branch_id'         => $validated['branch_id'],
                'status'            => 'active',
                'email_verified_at' => now(),
            ]);

            Member::create([
                'user_id'           => $user->id,
                'membership_id'     => 'MEM-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'student_id'        => $validated['student_id'] ?? null,
                'department'        => $validated['department'] ?? null,
                'semester'          => $validated['semester'] ?? null,
                'address'           => $validated['address'] ?? null,
                'membership_status' => 'active',
                'joined_at'         => now(),
                'expires_at'        => now()->addMonths(12),
            ]);
        });

        return redirect()->route('admin.members.index')->with('success', 'Member registered successfully.');
    }

    public function show(Member $member)
    {
        $member->load([
            'user.role',
            'user.branch',
            'transactions.bookCopy.book',
            'reservations.book',
            'fines.payments',
        ]);

        return view('admin.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $member->load('user');
        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.members.edit', compact('member', 'branches'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email,' . $member->user_id,
            'phone'             => 'nullable|string|max:20',
            'branch_id'         => 'required|exists:branches,id',
            'student_id'        => 'nullable|string|max:100',
            'department'        => 'nullable|string|max:255',
            'semester'          => 'nullable|string|max:50',
            'address'           => 'nullable|string|max:500',
            'membership_status' => 'required|in:active,expired,suspended',
        ]);

        DB::transaction(function () use ($validated, $member) {
            $member->user->update([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'] ?? null,
                'branch_id' => $validated['branch_id'],
            ]);

            $member->update([
                'student_id'        => $validated['student_id'],
                'department'        => $validated['department'],
                'semester'          => $validated['semester'],
                'address'           => $validated['address'],
                'membership_status' => $validated['membership_status'],
            ]);
        });

        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $activeLoans = $member->transactions()->whereIn('status', ['issued', 'overdue'])->count();
        if ($activeLoans > 0) {
            return back()->with('error', 'Cannot delete a member with active loans.');
        }

        DB::transaction(function () use ($member) {
            $member->user->delete(); // Soft delete
            $member->delete();
        });

        return redirect()->route('admin.members.index')->with('success', 'Member removed successfully.');
    }

    /**
     * Renew a member's membership.
     */
    public function renewMembership(Member $member)
    {
        $member->update([
            'membership_status' => 'active',
            'expires_at'        => now()->addMonths(12),
        ]);

        return back()->with('success', 'Membership renewed for 12 months.');
    }
}
