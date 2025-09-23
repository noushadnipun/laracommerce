<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $tables = [
            'stock_movements',
            'product_reviews',
            'product_statistics',
            'product_wishlists',
            'inventory',
            'products',
        ];
        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
            } catch (\Throwable $e) {
                try {
                    DB::table($table)->delete();
                } catch (\Throwable $ignored) {}
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('Products and related tables cleared.');
    }
}



