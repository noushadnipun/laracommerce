<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanUnusedBrandsSeeder extends Seeder
{
    public function run(): void
    {
        // Find brand IDs that have zero products
        $unused = DB::table('product_brands as b')
            ->leftJoin('products as p', 'p.brand_id', '=', 'b.id')
            ->select('b.id')
            ->groupBy('b.id')
            ->havingRaw('COUNT(p.id) = 0')
            ->pluck('b.id')
            ->all();

        if (!empty($unused)) {
            DB::table('product_brands')->whereIn('id', $unused)->delete();
        }

        $this->command->info('Deleted '.count($unused).' brands with no products.');
    }
}



