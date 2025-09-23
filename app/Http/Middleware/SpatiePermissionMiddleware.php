<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpatiePermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Define permissions based on role_id
        $permissions = [
            'admin' => [
                'view-dashboard', 'view-products', 'create-products', 'edit-products', 'delete-products',
                'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
                'view-brands', 'create-brands', 'edit-brands', 'delete-brands',
                'view-orders', 'edit-orders', 'delete-orders', 'manage-order-status',
                'view-media', 'upload-media', 'delete-media',
                'view-settings', 'edit-settings', 'view-users', 'create-users', 'edit-users', 'delete-users',
                'view-frontend', 'manage-cart', 'place-orders'
            ],
            'editor' => [
                'view-dashboard', 'view-products', 'create-products', 'edit-products',
                'view-categories', 'create-categories', 'edit-categories',
                'view-brands', 'create-brands', 'edit-brands',
                'view-orders', 'edit-orders', 'manage-order-status',
                'view-media', 'upload-media'
            ],
            'customer' => [
                'view-frontend', 'manage-cart', 'place-orders'
            ]
        ];

        // Get user role based on role_id
        $userRole = 'customer'; // default
        if ($user->role_id == 1) {
            $userRole = 'admin';
        } elseif ($user->role_id == 2) {
            $userRole = 'editor';
        }

        // Check if user has permission
        if (!in_array($permission, $permissions[$userRole] ?? [])) {
            abort(403, 'Insufficient permissions.');
        }

        return $next($request);
    }
}