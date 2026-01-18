<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

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
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Permission Management
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            
            // Product Management
            'view products',
            'create products',
            'edit products',
            'delete products',
            'import products',
            'export products',
            
            // Category Management
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Brand Management
            'view brands',
            'create brands',
            'edit brands',
            'delete brands',
            
            // Order Management
            'view orders',
            'edit orders',
            'delete orders',
            'export orders',
            
            // Customer Management
            'view customers',
            'edit customers',
            'delete customers',
            
            // Statistics
            'view statistics',
            'export statistics',
            
            // Settings
            'view settings',
            'edit settings',
            
            // Media Management
            'view media',
            'upload media',
            'delete media',
            
            // Menu Management
            'view menus',
            'create menus',
            'edit menus',
            'delete menus',
            
            // Coupon Management
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            
            // Review Management
            'view reviews',
            'edit reviews',
            'delete reviews',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin'], ['slug' => 'super-admin', 'description' => 'Super Administrator with all permissions']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['slug' => 'admin', 'description' => 'Administrator with most permissions']);
        $adminRole->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view roles', 'create roles', 'edit roles',
            'view permissions', 'create permissions', 'edit permissions',
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view brands', 'create brands', 'edit brands', 'delete brands',
            'view orders', 'edit orders', 'export orders',
            'view customers', 'edit customers',
            'view statistics', 'export statistics',
            'view settings', 'edit settings',
            'view media', 'upload media', 'delete media',
            'view menus', 'create menus', 'edit menus', 'delete menus',
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',
            'view reviews', 'edit reviews', 'delete reviews',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['slug' => 'manager', 'description' => 'Manager with limited admin permissions']);
        $managerRole->givePermissionTo([
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view brands', 'create brands', 'edit brands',
            'view orders', 'edit orders',
            'view customers', 'edit customers',
            'view statistics',
            'view media', 'upload media',
            'view menus', 'edit menus',
            'view coupons', 'create coupons', 'edit coupons',
            'view reviews', 'edit reviews',
        ]);

        $editorRole = Role::firstOrCreate(['name' => 'editor'], ['slug' => 'editor', 'description' => 'Editor with content management permissions']);
        $editorRole->givePermissionTo([
            'view products', 'create products', 'edit products',
            'view categories', 'create categories', 'edit categories',
            'view brands', 'create brands', 'edit brands',
            'view orders',
            'view customers',
            'view media', 'upload media',
            'view menus', 'edit menus',
            'view reviews', 'edit reviews',
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['slug' => 'customer', 'description' => 'Customer role for frontend users']);
        // Customers don't need admin permissions

        // Create a super admin user if it doesn't exist
        $superAdmin = User::where('email', 'admin@admin.com')->first();
        if (!$superAdmin) {
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'phone' => '12345678',
            ]);
        }
        
        $superAdmin->assignRole('super-admin');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Super Admin created: admin@admin.com / password');
    }
}