<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AssignUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->first()->id;
        $editorRoleId = DB::table('roles')->where('name', 'editor')->first()->id;
        $customerRoleId = DB::table('roles')->where('name', 'customer')->first()->id;

        // Assign roles based on existing role_id
        $users = User::all();
        
        foreach ($users as $user) {
            // Clear existing role assignments
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            
            // Assign role based on role_id
            switch ($user->role_id) {
                case 1: // Admin
                    DB::table('model_has_roles')->insert([
                        'role_id' => $adminRoleId,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user->id,
                    ]);
                    break;
                case 2: // Editor
                    DB::table('model_has_roles')->insert([
                        'role_id' => $editorRoleId,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user->id,
                    ]);
                    break;
                case 3: // Customer
                default:
                    DB::table('model_has_roles')->insert([
                        'role_id' => $customerRoleId,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user->id,
                    ]);
                    break;
            }
        }

        $this->command->info('✅ User roles assigned successfully!');
        $this->command->info('Total users processed: ' . $users->count());
    }
}











