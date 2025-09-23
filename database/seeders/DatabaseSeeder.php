<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * 
     * @@ Create New Seeder : php artisan make:seeder CreateUsersSeeder
     * @@ Run command : php artisan db:seed --class=DatabaseSeeder
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        /**
         * FrontEndSettings DB Seeder
         */
        $this->call(FrontendSettingsSeeder::class);
        //Term
        $this->call(TermsSeeder::class);
        //Term Taxonomy
        $this->call(TermTaxonomySeeder::class);
        //Role & Permissions
        $this->call(RolePermissionSeeder::class);
        
        //Ecommerce Data
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductBrandSeeder::class);
        // Core ecommerce seeds only
        $this->call(StoreSettingsSeeder::class);
        $this->call(ProductCouponSeeder::class);
        
        //User Create
        $this->call(CreateUsersSeeder::class);
    }
}
