<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

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

        // Insert permissions
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'slug' => 'admin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'editor',
                'slug' => 'editor',
                'description' => 'Editor with limited access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer',
                'slug' => 'customer',
                'description' => 'Customer with basic access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }

        // Assign permissions to roles
        $adminRoleId = DB::table('roles')->where('name', 'admin')->first()->id;
        $editorRoleId = DB::table('roles')->where('name', 'editor')->first()->id;
        $customerRoleId = DB::table('roles')->where('name', 'customer')->first()->id;

        // Admin gets all permissions
        $allPermissions = DB::table('permissions')->pluck('id');
        foreach ($allPermissions as $permissionId) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        }

        // Editor permissions
        $editorPermissions = [
            'view-dashboard', 'view-products', 'create-products', 'edit-products',
            'view-categories', 'create-categories', 'edit-categories',
            'view-brands', 'create-brands', 'edit-brands',
            'view-orders', 'edit-orders', 'manage-order-status',
            'view-media', 'upload-media'
        ];

        foreach ($editorPermissions as $permissionName) {
            $permissionId = DB::table('permissions')->where('name', $permissionName)->first()->id;
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $editorRoleId,
            ]);
        }

        // Customer permissions
        $customerPermissions = ['view-frontend', 'manage-cart', 'place-orders'];
        foreach ($customerPermissions as $permissionName) {
            $permissionId = DB::table('permissions')->where('name', $permissionName)->first()->id;
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionId,
                'role_id' => $customerRoleId,
            ]);
        }

        $this->command->info('✅ Custom roles and permissions created successfully!');
    }
}











