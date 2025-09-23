<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productGalleries = [
            'iphone-15-pro' => [
                'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
            ],
            'samsung-galaxy-s24-ultra' => [
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
            ],
            'macbook-pro-16-inch' => [
                'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=500&fit=crop',
            ],
            'nike-air-max-270' => [
                'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&h=500&fit=crop',
            ],
            'zara-summer-dress' => [
                'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
                'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=500&h=500&fit=crop',
            ],
        ];

        foreach ($productGalleries as $slug => $galleryImages) {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                // Convert array to JSON string for attribute field
                $product->update([
                    'attribute' => json_encode(['gallery' => $galleryImages])
                ]);
            }
        }

        $this->command->info('✅ Product galleries updated successfully!');
    }
}
