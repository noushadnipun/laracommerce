<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'title',
        'description',
        'slug',
        'code',
        'regular_price',
        'sale_price',
        'purchase_price',
        'attribute',
        'refundable',
        'shipping_type',
        'shipping_cost',
        'total_stock',
        'current_stock',
        'product_image',
        'featured_image',
        'visibility',
        'remote_images'
    ];
    
    //store with array
     protected $casts = [
        'product_image' => 'array',
        'attribute' => 'array',
        'remote_images' => 'array',
    ];

    /**
     * Mutator to ensure remote_images is always stored as JSON array
     */
    public function setRemoteImagesAttribute($value)
    {
        if (is_string($value)) {
            // If it's a string, try to decode it first
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $this->attributes['remote_images'] = json_encode($decoded);
            } else {
                // If it's not valid JSON, treat it as a single URL
                $this->attributes['remote_images'] = json_encode([$value]);
            }
        } elseif (is_array($value)) {
            $this->attributes['remote_images'] = json_encode($value);
        } else {
            $this->attributes['remote_images'] = json_encode([]);
        }
    }

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the brand that owns the product
     */
    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }

    /**
     * Get a best-available image URL by product ID.
     * Priority: model helper → featured_image (Media or uploads path) → first product_image → first remote_images → placeholder
     */
    public static function imageUrlById($productId)
    {
        try {
            $placeholder = asset('public/frontend/images/no-images.jpg');
            if (empty($productId)) {
                return $placeholder;
            }

            $product = static::find($productId);
            if (!$product) {
                return $placeholder;
            }

            // 1) Prefer existing model helper if available
            if (method_exists($product, 'getFeaturedImageUrl')) {
                $url = $product->getFeaturedImageUrl();
                if (!empty($url)) {
                    return $url;
                }
            }

            // 2) featured_image via Media or uploads path
            if (!empty($product->featured_image)) {
                $url = class_exists('App\\Models\\Media') ? (\App\Models\Media::fileLocation($product->featured_image) ?? null) : null;
                if (!empty($url)) {
                    return $url;
                }
                return asset('uploads/products/' . $product->featured_image);
            }

            // 3) first of product_image array
            if (is_array($product->product_image) && count($product->product_image) > 0) {
                return asset('uploads/products/' . $product->product_image[0]);
            }

            // 4) first of remote_images array
            if (is_array($product->remote_images) && count($product->remote_images) > 0) {
                return $product->remote_images[0];
            }

            return $placeholder;
        } catch (\Throwable $e) {
            return asset('public/frontend/images/no-images.jpg');
        }
    }

    /**
     * Get the wishlists for the product
     */
    public function wishlists()
    {
        return $this->hasMany(\App\Models\Product\ProductWishlist::class);
    }

    /**
     * Get the reviews for the product
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Product\ProductReview::class);
    }

    /**
     * Get the approved reviews for the product
     */
    public function approvedReviews()
    {
        return $this->hasMany(\App\Models\Product\ProductReview::class)->where('is_approved', true);
    }

    /**
     * Get the size guides for the product
     */
    public function sizeGuides()
    {
        return $this->hasMany(\App\Models\Product\ProductSizeGuide::class);
    }

    /**
     * Get the statistics for the product
     */
    public function statistics()
    {
        return $this->hasOne(\App\Models\Product\ProductStatistics::class);
    }

    /**
     * Get all images (local + remote)
     */
    public function getAllImages()
    {
        $images = [];
        
        // Prefer remote images first
        if ($this->remote_images) {
            if (is_array($this->remote_images)) {
                foreach ($this->remote_images as $image) {
                    if ($image) {
                        $images[] = [
                            'url' => $image,
                            'type' => 'remote'
                        ];
                    }
                }
            } elseif (is_string($this->remote_images)) {
                $decoded = json_decode($this->remote_images, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $image) {
                        if ($image) {
                            $images[] = [
                                'url' => $image,
                                'type' => 'remote'
                            ];
                        }
                    }
                }
            }
        }

        // Then add local images
        if ($this->product_image && is_array($this->product_image)) {
            foreach ($this->product_image as $image) {
                if ($image) {
                    $images[] = [
                        'url' => asset('public/uploads/products/' . $image),
                        'type' => 'local'
                    ];
                }
            }
        }
        
        return $images;
    }

    /**
     * Get featured image URL with fallback
     */
    public function getFeaturedImageUrl()
    {
        // Prefer remote images
        if ($this->remote_images) {
            $remoteImages = [];
            if (is_array($this->remote_images)) {
                $remoteImages = $this->remote_images;
            } elseif (is_string($this->remote_images)) {
                $decoded = json_decode($this->remote_images, true);
                if (is_array($decoded)) {
                    $remoteImages = $decoded;
                }
            }
            if (!empty($remoteImages[0])) {
                return $remoteImages[0];
            }
        }

        // Fallbacks to local
        if ($this->featured_image) {
            return asset('public/uploads/products/' . $this->featured_image);
        }
        if ($this->product_image && is_array($this->product_image) && !empty($this->product_image[0])) {
            return asset('public/uploads/products/' . $this->product_image[0]);
        }

        return asset('public/frontend/images/no-images.svg');
    }

    /**
     * Check if product has images
     */
    public function hasImages()
    {
        $hasLocalImages = ($this->product_image && is_array($this->product_image) && !empty($this->product_image[0]));
        $hasFeaturedImage = $this->featured_image;
        
        $hasRemoteImages = false;
        if ($this->remote_images) {
            if (is_array($this->remote_images) && !empty($this->remote_images[0])) {
                $hasRemoteImages = true;
            } elseif (is_string($this->remote_images)) {
                $decoded = json_decode($this->remote_images, true);
                if (is_array($decoded) && !empty($decoded[0])) {
                    $hasRemoteImages = true;
                }
            }
        }
        
        return $hasLocalImages || $hasRemoteImages || $hasFeaturedImage;
    }

    /**
     * Get Product by category ID
     */
    public static function productByCatId($category_id){
        if(!empty($category_id)){
            return Product::orderBy('created_at', 'DESC')->where('visibility', '1')->whereRaw("FIND_IN_SET($category_id, category_id)");
        }
    }


    /**
     * Get Product by Category ID where has Comma
     */
    public static function productByCatIdHasComma($category_id){
        return Product::orderBy('created_at', 'DESC')->where('visibility', '1')->where('category_id', $category_id);
    }

    /**
     * Get average rating for the product
     */
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get review count for the product
     */
    public function getReviewCount()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get average rating for a product by ID (static method)
     */
    public static function getAverageRatingById($productId)
    {
        $product = self::find($productId);
        return $product ? $product->getAverageRating() : 0;
    }

    /**
     * Get review count for a product by ID (static method)
     */
    public static function getReviewCountById($productId)
    {
        $product = self::find($productId);
        return $product ? $product->getReviewCount() : 0;
    }

    /**
     * Get or create statistics for the product
     */
    public function getOrCreateStatistics()
    {
        try {
            return \App\Models\Product\ProductStatistics::firstOrCreate(
                ['product_id' => $this->id],
                [
                    'views' => 0,
                    'clicks' => 0,
                    'cart_adds' => 0,
                    'wishlist_adds' => 0,
                    'compare_adds' => 0,
                    'shares' => 0,
                    'total_sales' => 0,
                    'total_revenue' => 0,
                    'average_rating' => 0
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error
            if ($e->getCode() == 23000) {
                // Try to find existing record
                return \App\Models\Product\ProductStatistics::where('product_id', $this->id)->first();
            }
            throw $e;
        }
    }

    /**
     * Track product view
     */
    public function trackView()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('views');
        $stats->update(['last_viewed_at' => now()]);
    }

    /**
     * Track product click
     */
    public function trackClick()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('clicks');
    }

    /**
     * Track cart add
     */
    public function trackCartAdd()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('cart_adds');
    }

    /**
     * Track wishlist add
     */
    public function trackWishlistAdd()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('wishlist_adds');
    }

    /**
     * Track compare add
     */
    public function trackCompareAdd()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('compare_adds');
    }

    /**
     * Track share
     */
    public function trackShare()
    {
        $stats = $this->getOrCreateStatistics();
        $stats->increment('shares');
    }

    /**
     * Get the inventory for the product.
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get the stock movements for the product.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get current stock from inventory.
     */
    public function getCurrentStockAttribute()
    {
        return $this->inventory ? $this->inventory->current_stock : 0;
    }

    /**
     * Get total stock from inventory.
     */
    public function getTotalStockAttribute()
    {
        return $this->inventory ? $this->inventory->total_stock : 0;
    }

    /**
     * Check if product is low stock.
     */
    public function isLowStock()
    {
        return $this->inventory ? $this->inventory->isLowStock() : false;
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock()
    {
        return $this->inventory ? $this->inventory->isOutOfStock() : true;
    }
}
