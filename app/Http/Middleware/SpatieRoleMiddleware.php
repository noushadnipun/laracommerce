<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpatieRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has the required role using your existing role system
        $user = auth()->user();
        
        // Check role by role_id (your existing system)
        if ($role === 'admin' && $user->role_id != 1) {
            abort(403, 'Admin access required.');
        }
        
        if ($role === 'editor' && $user->role_id != 2) {
            abort(403, 'Editor access required.');
        }
        
        if ($role === 'customer' && $user->role_id != 3) {
            abort(403, 'Customer access required.');
        }

        return $next($request);
    }
}