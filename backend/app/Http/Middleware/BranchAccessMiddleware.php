<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BranchAccessMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures branch admins cannot access other branches' endpoints.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // If the route has a branch_id parameter, check it against the user's branch
        $routeBranchId = $request->route('branch_id') ?? $request->route('branch')?->id;

        if ($routeBranchId && (int) $user->branch_id !== (int) $routeBranchId) {
            return response()->json(['message' => 'Forbidden. You do not have access to this branch.'], 403);
        }

        return $next($request);
    }
}
