<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Kalo super_admin, BIARKAN LEWAT TANPA SYARAT
        if ($request->user()->role === 'super_admin') {
            return $next($request);
        }

        if (! in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Gak ada akses!'], 403);
        }

        return $next($request);
    }
}
