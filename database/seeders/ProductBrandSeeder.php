<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductBrand;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'Zara',
                'slug' => 'zara',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'H&M',
                'slug' => 'hm',
                'image' => null,
                'visibility' => 1,
            ],
            [
                'name' => 'IKEA',
                'slug' => 'ikea',
                'image' => null,
                'visibility' => 1,
            ],
        ];

        foreach ($brands as $brand) {
            ProductBrand::updateOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }
    }
}
