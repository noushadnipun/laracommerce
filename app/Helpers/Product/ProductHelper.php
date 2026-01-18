<?php

namespace App\Helpers\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\Inventory;

class ProductHelper
{
    /**
     * Get product with all relationships
     */
    public static function getProductWithDetails($id)
    {
        return Product::with(['category', 'brand', 'inventory', 'reviews', 'statistics'])
                     ->find($id);
    }

    /**
     * Get product by slug
     */
    public static function getProductBySlug($slug)
    {
        return Product::with(['category', 'brand', 'inventory', 'reviews', 'statistics'])
                     ->where('slug', $slug)
                     ->where('visibility', 1)
                     ->first();
    }

    /**
     * Get featured products
     */
    public static function getFeaturedProducts($limit = 8)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->where('featured', 1)
                     ->latest()
                     ->limit($limit)
                     ->get();
    }

    /**
     * Get latest products
     */
    public static function getLatestProducts($limit = 8)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->latest()
                     ->limit($limit)
                     ->get();
    }

    /**
     * Get products by category
     */
    public static function getProductsByCategory($categoryId, $limit = 12)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->whereRaw("FIND_IN_SET(?, category_id)", [$categoryId])
                     ->latest()
                     ->paginate($limit);
    }

    /**
     * Get products by brand
     */
    public static function getProductsByBrand($brandId, $limit = 12)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->where('brand_id', $brandId)
                     ->latest()
                     ->paginate($limit);
    }

    /**
     * Search products
     */
    public static function searchProducts($query, $limit = 12)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->where(function($q) use ($query) {
                         $q->where('title', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%")
                           ->orWhere('code', 'like', "%{$query}%");
                     })
                     ->latest()
                     ->paginate($limit);
    }

    /**
     * Get product price
     */
    public static function getProductPrice($product)
    {
        if ($product->sale_price && $product->sale_price > 0) {
            return [
                'regular_price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'discount_percentage' => round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100),
                'formatted_regular' => self::formatPrice($product->regular_price),
                'formatted_sale' => self::formatPrice($product->sale_price),
            ];
        }
        
        return [
            'regular_price' => $product->regular_price,
            'sale_price' => null,
            'discount_percentage' => 0,
            'formatted_regular' => self::formatPrice($product->regular_price),
            'formatted_sale' => null,
        ];
    }

    /**
     * Format price
     */
    public static function formatPrice($price)
    {
        return '৳' . number_format($price, 2);
    }

    /**
     * Get product stock status
     */
    public static function getStockStatus($product)
    {
        if ($product->inventory) {
            $currentStock = $product->inventory->current_stock;
            $lowStockThreshold = $product->inventory->low_stock_threshold;
            
            if ($currentStock <= 0) {
                return [
                    'status' => 'out_of_stock',
                    'label' => 'Out of Stock',
                    'class' => 'text-danger',
                    'badge_class' => 'badge-danger',
                ];
            } elseif ($currentStock <= $lowStockThreshold) {
                return [
                    'status' => 'low_stock',
                    'label' => 'Low Stock',
                    'class' => 'text-warning',
                    'badge_class' => 'badge-warning',
                ];
            } else {
                return [
                    'status' => 'in_stock',
                    'label' => 'In Stock',
                    'class' => 'text-success',
                    'badge_class' => 'badge-success',
                ];
            }
        }
        
        return [
            'status' => 'unknown',
            'label' => 'Stock Unknown',
            'class' => 'text-muted',
            'badge_class' => 'badge-secondary',
        ];
    }

    /**
     * Get product images
     */
    public static function getProductImages($product)
    {
        $images = [];
        
        // Featured image
        if ($product->featured_image) {
            $images[] = [
                'url' => asset('uploads/products/' . $product->featured_image),
                'type' => 'featured',
                'alt' => $product->title,
            ];
        }
        
        // Gallery images
        if ($product->product_image && is_array($product->product_image)) {
            foreach ($product->product_image as $image) {
                $images[] = [
                    'url' => asset('uploads/products/' . $image),
                    'type' => 'gallery',
                    'alt' => $product->title,
                ];
            }
        }
        
        // Remote images
        if ($product->remote_images && is_array($product->remote_images)) {
            foreach ($product->remote_images as $image) {
                $images[] = [
                    'url' => $image,
                    'type' => 'remote',
                    'alt' => $product->title,
                ];
            }
        }
        
        // Default image if no images
        if (empty($images)) {
            $images[] = [
                'url' => asset('public/frontend/images/no-images.svg'),
                'type' => 'default',
                'alt' => 'No Image Available',
            ];
        }
        
        return $images;
    }

    /**
     * Get product attributes
     */
    public static function getProductAttributes($product)
    {
        if (!$product->attribute || !is_array($product->attribute)) {
            return [];
        }
        
        $attributes = [];
        foreach ($product->attribute as $key => $value) {
            $attributes[] = [
                'name' => $key,
                'value' => $value,
                'formatted' => ucfirst($key) . ': ' . $value,
            ];
        }
        
        return $attributes;
    }

    /**
     * Get product rating
     */
    public static function getProductRating($product)
    {
        $rating = $product->getAverageRating();
        $reviewCount = $product->getReviewCount();
        
        return [
            'rating' => $rating,
            'review_count' => $reviewCount,
            'stars' => str_repeat('★', floor($rating)) . str_repeat('☆', 5 - floor($rating)),
            'formatted_rating' => number_format($rating, 1),
        ];
    }

    /**
     * Check if product is in stock
     */
    public static function isInStock($product, $quantity = 1)
    {
        if ($product->inventory) {
            return $product->inventory->current_stock >= $quantity;
        }
        
        return true; // Assume in stock if no inventory record
    }

    /**
     * Get product breadcrumb
     */
    public static function getProductBreadcrumb($product)
    {
        $breadcrumb = [
            ['name' => 'Home', 'url' => route('frontend_index')],
        ];
        
        if ($product->category) {
            $breadcrumb[] = [
                'name' => $product->category->name,
                'url' => route('frontend_single_product_category', $product->category->slug)
            ];
        }
        
        $breadcrumb[] = [
            'name' => $product->title,
            'url' => route('frontend_single_product', $product->slug)
        ];
        
        return $breadcrumb;
    }

    /**
     * Get related products
     */
    public static function getRelatedProducts($product, $limit = 4)
    {
        return Product::with(['category', 'brand', 'inventory'])
                     ->where('visibility', 1)
                     ->where('id', '!=', $product->id)
                     ->where(function($q) use ($product) {
                         if ($product->category_id) {
                             $q->whereRaw("FIND_IN_SET(?, category_id)", [explode(',', $product->category_id)[0]]);
                         }
                         if ($product->brand_id) {
                             $q->orWhere('brand_id', $product->brand_id);
                         }
                     })
                     ->latest()
                     ->limit($limit)
                     ->get();
    }

    /**
     * Get product filters
     */
    public static function getProductFilters()
    {
        return [
            'categories' => ProductCategory::where('visibility', 1)->get(),
            'brands' => ProductBrand::where('visibility', 1)->get(),
            'price_ranges' => [
                ['min' => 0, 'max' => 1000, 'label' => 'Under ৳1,000'],
                ['min' => 1000, 'max' => 5000, 'label' => '৳1,000 - ৳5,000'],
                ['min' => 5000, 'max' => 10000, 'label' => '৳5,000 - ৳10,000'],
                ['min' => 10000, 'max' => 50000, 'label' => '৳10,000 - ৳50,000'],
                ['min' => 50000, 'max' => null, 'label' => 'Above ৳50,000'],
            ],
        ];
    }
}