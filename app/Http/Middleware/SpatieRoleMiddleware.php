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

        $user = auth()->user();
        
        // Handle multiple roles separated by pipe (|)
        $roles = explode('|', $role);
        
        // Check if user has any of the required roles
        $hasRole = false;
        foreach ($roles as $requiredRole) {
            if ($user->hasRole($requiredRole)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            abort(403, 'Insufficient permissions. Required role: ' . $role);
        }

        return $next($request);
    }
}