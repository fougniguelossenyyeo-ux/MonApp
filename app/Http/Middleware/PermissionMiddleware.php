<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Si super admin → accès total
        if ($user->role && $user->role->super_admin) {
            return $next($request);
        }

        // Vérifie la permission
        if (! $user->role || ! $user->role->permissions->contains('nom', $permission)) {
            abort(403);
        }

        return $next($request);
    }
}
