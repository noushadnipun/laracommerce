<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productImages = [
            'iphone-15-pro' => [
                'product_image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop',
            ],
            'samsung-galaxy-s24-ultra' => [
                'product_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
            ],
            'macbook-pro-16-inch' => [
                'product_image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&h=600&fit=crop',
            ],
            'dell-xps-15' => [
                'product_image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop',
            ],
            'nike-air-max-270' => [
                'product_image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600&fit=crop',
            ],
            'adidas-ultraboost-22' => [
                'product_image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=600&fit=crop',
            ],
            'zara-summer-dress' => [
                'product_image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&h=600&fit=crop',
            ],
            'hm-casual-t-shirt' => [
                'product_image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=600&fit=crop',
            ],
            'ikea-hemnes-bookcase' => [
                'product_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
            ],
            'ikea-malm-bed-frame' => [
                'product_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&h=500&fit=crop',
                'featured_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
            ],
        ];

        foreach ($productImages as $slug => $images) {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                $product->update($images);
            }
        }

        $this->command->info('Product images updated successfully!');
    }
}













