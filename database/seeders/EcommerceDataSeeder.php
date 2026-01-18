<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EcommerceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Ecommerce Data...');
        
        // Run all ecommerce seeders
        $this->call([
            ProductCategorySeeder::class,
            ProductBrandSeeder::class,
            ProductSeeder::class,
            StoreSettingsSeeder::class,
            ProductCouponSeeder::class,
        ]);
        
        $this->command->info('Ecommerce data seeded successfully!');
        $this->command->info('Data Summary:');
        $this->command->info('   - Categories: ' . DB::table('categories')->count());
        $this->command->info('   - Brands: ' . DB::table('product_brands')->count());
        $this->command->info('   - Products: ' . DB::table('products')->count());
        $this->command->info('   - Store Settings: ' . DB::table('store_settings')->count());
        $this->command->info('   - Coupons: ' . DB::table('product_coupons')->count());
    }
}













