<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Product Management
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Category Management
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // Brand Management
            'view-brands',
            'create-brands',
            'edit-brands',
            'delete-brands',
            
            // Order Management
            'view-orders',
            'edit-orders',
            'delete-orders',
            'manage-order-status',
            
            // Media Management
            'view-media',
            'upload-media',
            'delete-media',
            
            // Settings
            'view-settings',
            'edit-settings',
            
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Frontend
            'view-frontend',
            'manage-cart',
            'place-orders',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Create roles and assign permissions
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $adminRole->syncPermissions(Permission::all());

        $editorRole = Role::updateOrCreate(
            ['name' => 'editor', 'guard_name' => 'web'],
            ['name' => 'editor', 'guard_name' => 'web']
        );
        $editorRole->syncPermissions([
            'view-dashboard',
            'view-products',
            'create-products',
            'edit-products',
            'view-categories',
            'create-categories',
            'edit-categories',
            'view-brands',
            'create-brands',
            'edit-brands',
            'view-orders',
            'edit-orders',
            'manage-order-status',
            'view-media',
            'upload-media',
        ]);

        $customerRole = Role::updateOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'customer', 'guard_name' => 'web']
        );
        $customerRole->syncPermissions([
            'view-frontend',
            'manage-cart',
            'place-orders',
        ]);
    }
}
