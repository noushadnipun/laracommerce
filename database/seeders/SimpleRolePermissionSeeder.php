<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles table if not exists
        if (!DB::getSchemaBuilder()->hasTable('roles')) {
            DB::statement('
                CREATE TABLE roles (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    guard_name VARCHAR(255) NOT NULL DEFAULT "web",
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY roles_name_guard_name_unique (name, guard_name)
                )
            ');
        }

        // Create permissions table if not exists
        if (!DB::getSchemaBuilder()->hasTable('permissions')) {
            DB::statement('
                CREATE TABLE permissions (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    guard_name VARCHAR(255) NOT NULL DEFAULT "web",
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY permissions_name_guard_name_unique (name, guard_name)
                )
            ');
        }

        // Create model_has_roles table if not exists
        if (!DB::getSchemaBuilder()->hasTable('model_has_roles')) {
            DB::statement('
                CREATE TABLE model_has_roles (
                    role_id BIGINT UNSIGNED NOT NULL,
                    model_type VARCHAR(255) NOT NULL,
                    model_id BIGINT UNSIGNED NOT NULL,
                    PRIMARY KEY (role_id, model_id, model_type),
                    KEY model_has_roles_model_id_model_type_index (model_id, model_type)
                )
            ');
        }

        // Create model_has_permissions table if not exists
        if (!DB::getSchemaBuilder()->hasTable('model_has_permissions')) {
            DB::statement('
                CREATE TABLE model_has_permissions (
                    permission_id BIGINT UNSIGNED NOT NULL,
                    model_type VARCHAR(255) NOT NULL,
                    model_id BIGINT UNSIGNED NOT NULL,
                    PRIMARY KEY (permission_id, model_id, model_type),
                    KEY model_has_permissions_model_id_model_type_index (model_id, model_type)
                )
            ');
        }

        // Create role_has_permissions table if not exists
        if (!DB::getSchemaBuilder()->hasTable('role_has_permissions')) {
            DB::statement('
                CREATE TABLE role_has_permissions (
                    permission_id BIGINT UNSIGNED NOT NULL,
                    role_id BIGINT UNSIGNED NOT NULL,
                    PRIMARY KEY (permission_id, role_id)
                )
            ');
        }

        // Insert roles
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editor', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                $role
            );
        }

        // Insert permissions
        $permissions = [
            'view-dashboard', 'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-brands', 'create-brands', 'edit-brands', 'delete-brands',
            'view-orders', 'edit-orders', 'delete-orders', 'manage-order-status',
            'view-media', 'upload-media', 'delete-media',
            'view-settings', 'edit-settings',
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-frontend', 'manage-cart', 'place-orders',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Assign all permissions to admin role
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert(
                ['role_id' => $adminRoleId, 'permission_id' => $permissionId],
                ['role_id' => $adminRoleId, 'permission_id' => $permissionId]
            );
        }

        // Assign specific permissions to editor role
        $editorRoleId = DB::table('roles')->where('name', 'editor')->value('id');
        $editorPermissions = [
            'view-dashboard', 'view-products', 'create-products', 'edit-products',
            'view-categories', 'create-categories', 'edit-categories',
            'view-brands', 'create-brands', 'edit-brands',
            'view-orders', 'edit-orders', 'manage-order-status',
            'view-media', 'upload-media'
        ];

        foreach ($editorPermissions as $permission) {
            $permissionId = DB::table('permissions')->where('name', $permission)->value('id');
            if ($permissionId) {
                DB::table('role_has_permissions')->updateOrInsert(
                    ['role_id' => $editorRoleId, 'permission_id' => $permissionId],
                    ['role_id' => $editorRoleId, 'permission_id' => $permissionId]
                );
            }
        }

        // Assign specific permissions to customer role
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        $customerPermissions = ['view-frontend', 'manage-cart', 'place-orders'];

        foreach ($customerPermissions as $permission) {
            $permissionId = DB::table('permissions')->where('name', $permission)->value('id');
            if ($permissionId) {
                DB::table('role_has_permissions')->updateOrInsert(
                    ['role_id' => $customerRoleId, 'permission_id' => $permissionId],
                    ['role_id' => $customerRoleId, 'permission_id' => $permissionId]
                );
            }
        }
    }
}













