<?php

namespace App\Helpers;

class PermissionHelper
{
    /**
     * Define permissions for each role
     */
    protected static $permissions = [
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

    /**
     * Get user role based on role_id
     */
    protected static function getUserRole()
    {
        if (!auth()->check()) {
            return null;
        }

        $roleId = auth()->user()->role_id;
        
        switch ($roleId) {
            case 1:
                return 'admin';
            case 2:
                return 'editor';
            case 3:
                return 'customer';
            default:
                return 'customer';
        }
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($role)
    {
        if (!auth()->check()) {
            return false;
        }

        return self::getUserRole() === $role;
    }

    /**
     * Check if user has specific permission
     */
    public static function hasPermission($permission)
    {
        if (!auth()->check()) {
            return false;
        }

        $userRole = self::getUserRole();
        return in_array($permission, self::$permissions[$userRole] ?? []);
    }

    /**
     * Check if user has any of the given roles
     */
    public static function hasAnyRole($roles)
    {
        if (!auth()->check()) {
            return false;
        }

        $userRole = self::getUserRole();
        return in_array($userRole, $roles);
    }

    /**
     * Check if user has any of the given permissions
     */
    public static function hasAnyPermission($permissions)
    {
        if (!auth()->check()) {
            return false;
        }

        $userRole = self::getUserRole();
        $userPermissions = self::$permissions[$userRole] ?? [];

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user's role name
     */
    public static function getUserRoleName()
    {
        return self::getUserRole();
    }

    /**
     * Get user's permissions
     */
    public static function getUserPermissions()
    {
        if (!auth()->check()) {
            return [];
        }

        $userRole = self::getUserRole();
        return self::$permissions[$userRole] ?? [];
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return self::hasRole('admin');
    }

    /**
     * Check if user is editor
     */
    public static function isEditor()
    {
        return self::hasRole('editor');
    }

    /**
     * Check if user is customer
     */
    public static function isCustomer()
    {
        return self::hasRole('customer');
    }
}
