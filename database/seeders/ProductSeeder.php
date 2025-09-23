<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electronics - Smartphones
            [
                'user_id' => 1, // Admin user
                'category_id' => '2', // Smartphones
                'brand_id' => 1, // Apple
                'title' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera system and A17 Pro chip',
                'short_description' => 'Premium smartphone with titanium design',
                'slug' => 'iphone-15-pro',
                'code' => 'IPH15PRO-128',
                'regular_price' => 99900, // In cents
                'sale_price' => 89900, // In cents
                'purchase_price' => 80000, // In cents
                'total_stock' => '50',
                'current_stock' => '50',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            [
                'user_id' => 1,
                'category_id' => '2', // Smartphones
                'brand_id' => 2, // Samsung
                'title' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with S Pen and advanced AI features',
                'short_description' => 'Flagship Android phone with S Pen',
                'slug' => 'samsung-galaxy-s24-ultra',
                'code' => 'SGS24U-256',
                'regular_price' => 119900, // In cents
                'sale_price' => 109900, // In cents
                'purchase_price' => 95000, // In cents
                'total_stock' => '30',
                'current_stock' => '30',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            // Electronics - Laptops
            [
                'user_id' => 1,
                'category_id' => '3', // Laptops
                'brand_id' => 1, // Apple
                'title' => 'MacBook Pro 16-inch',
                'description' => 'Powerful laptop for professionals with M3 Pro chip',
                'short_description' => 'Professional laptop with M3 Pro chip',
                'slug' => 'macbook-pro-16-inch',
                'code' => 'MBP16-M3PRO',
                'regular_price' => 249900, // In cents
                'sale_price' => 229900, // In cents
                'purchase_price' => 200000, // In cents
                'total_stock' => '20',
                'current_stock' => '20',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            [
                'user_id' => 1,
                'category_id' => '3', // Laptops
                'brand_id' => 6, // Dell
                'title' => 'Dell XPS 15',
                'description' => 'Premium Windows laptop with 4K display and Intel i7 processor',
                'short_description' => 'Premium Windows laptop with 4K display',
                'slug' => 'dell-xps-15',
                'code' => 'DLLXPS15-I7',
                'regular_price' => 189900, // In cents
                'sale_price' => 169900, // In cents
                'purchase_price' => 150000, // In cents
                'total_stock' => '25',
                'current_stock' => '25',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            // Fashion - Men's Clothing
            [
                'user_id' => 1,
                'category_id' => '5', // Men's Clothing
                'brand_id' => 3, // Nike
                'title' => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes with Max Air cushioning',
                'short_description' => 'Comfortable running shoes',
                'slug' => 'nike-air-max-270',
                'code' => 'NAM270-BLK-10',
                'regular_price' => 15000, // In cents
                'sale_price' => 12000, // In cents
                'purchase_price' => 10000, // In cents
                'total_stock' => '100',
                'current_stock' => '100',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            [
                'user_id' => 1,
                'category_id' => '5', // Men's Clothing
                'brand_id' => 4, // Adidas
                'title' => 'Adidas Ultraboost 22',
                'description' => 'High-performance running shoes with Boost technology',
                'short_description' => 'High-performance running shoes',
                'slug' => 'adidas-ultraboost-22',
                'code' => 'AUB22-WHT-9',
                'regular_price' => 18000, // In cents
                'sale_price' => 15000, // In cents
                'purchase_price' => 12000, // In cents
                'total_stock' => '80',
                'current_stock' => '80',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            // Fashion - Women's Clothing
            [
                'user_id' => 1,
                'category_id' => '6', // Women's Clothing
                'brand_id' => 8, // Zara
                'title' => 'Zara Summer Dress',
                'description' => 'Elegant summer dress perfect for any occasion',
                'short_description' => 'Elegant summer dress',
                'slug' => 'zara-summer-dress',
                'code' => 'ZSD-SUM-M',
                'regular_price' => 7999, // In cents
                'sale_price' => 5999, // In cents
                'purchase_price' => 4000, // In cents
                'total_stock' => '60',
                'current_stock' => '60',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            [
                'user_id' => 1,
                'category_id' => '6', // Women's Clothing
                'brand_id' => 9, // H&M
                'title' => 'H&M Casual T-Shirt',
                'description' => 'Comfortable cotton t-shirt for everyday wear',
                'short_description' => 'Comfortable cotton t-shirt',
                'slug' => 'hm-casual-t-shirt',
                'code' => 'HMCT-COT-L',
                'regular_price' => 1999, // In cents
                'sale_price' => 1499, // In cents
                'purchase_price' => 1000, // In cents
                'total_stock' => '150',
                'current_stock' => '150',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            // Home & Garden - Furniture
            [
                'user_id' => 1,
                'category_id' => '8', // Furniture
                'brand_id' => 10, // IKEA
                'title' => 'IKEA Hemnes Bookcase',
                'description' => 'Classic bookcase with 5 shelves for storage and display',
                'short_description' => 'Classic 5-shelf bookcase',
                'slug' => 'ikea-hemnes-bookcase',
                'code' => 'IKEA-HB-5S',
                'regular_price' => 19900, // In cents
                'sale_price' => 17900, // In cents
                'purchase_price' => 15000, // In cents
                'total_stock' => '15',
                'current_stock' => '15',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
            [
                'user_id' => 1,
                'category_id' => '8', // Furniture
                'brand_id' => 10, // IKEA
                'title' => 'IKEA Malm Bed Frame',
                'description' => 'Minimalist bed frame with storage drawers',
                'short_description' => 'Minimalist bed frame with storage',
                'slug' => 'ikea-malm-bed-frame',
                'code' => 'IKEA-MBF-Q',
                'regular_price' => 29900, // In cents
                'sale_price' => 24900, // In cents
                'purchase_price' => 20000, // In cents
                'total_stock' => '10',
                'current_stock' => '10',
                'product_image' => null,
                'featured_image' => null,
                'remote_images' => [
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
                ],
                'visibility' => '1',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}



















