<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Ensure the user has the required permission (or super_admin).
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! Auth::check() || ! Auth::user()->hasPermission($permission)) {
            return response()->json(['message' => 'Forbidden. Missing required permission: ' . $permission], 403);
        }

        return $next($request);
    }
}
