<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $guestRole = Role::where('name', 'guest_user')->first();
        $defaultBranch = Branch::first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $guestRole ? $guestRole->id : 5, // Default to guest_user
            'branch_id' => $defaultBranch ? $defaultBranch->id : 1, // Fallback to first branch
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Registration successful. You can now login.',
            'user' => $user,
        ], 201);
    }
}
