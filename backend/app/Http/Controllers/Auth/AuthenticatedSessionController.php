<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Invalid login credentials.'
            ], 401);
        }

        $user = Auth::user();

        // Check if user is active
        if ($user->status !== 'active') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Your account is ' . $user->status . '. Please contact administration.'
            ], 403);
        }

        $request->session()->regenerate();

        // Create login log
        LoginLog::create([
            'user_id' => $user->id,
            'login_time' => now(),
            'ip_address' => $request->ip(),
            'device_info' => substr($request->userAgent() ?? 'Unknown Device', 0, 500),
        ]);

        // Load permissions for frontend
        $user->load('role.permissions');

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            // Update the latest login log with logout time
            $log = LoginLog::where('user_id', $user->id)
                ->whereNull('logout_time')
                ->latest('login_time')
                ->first();
                
            if ($log) {
                $log->update(['logout_time' => now()]);
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
