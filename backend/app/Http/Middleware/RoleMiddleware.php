<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Ensure the user has the required role (or super_admin).
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check() || (! Auth::user()->hasRole($role) && ! Auth::user()->hasRole('super_admin'))) {
            return response()->json(['message' => 'Forbidden. Insufficient role.'], 403);
        }

        return $next($request);
    }
}
