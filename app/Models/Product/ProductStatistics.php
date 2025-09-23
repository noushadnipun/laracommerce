<?php

namespace App\Models\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatistics extends Model
{
    use HasFactory;

    protected $table = 'product_statistics';

    protected $fillable = [
        'product_id',
        'views',
        'clicks',
        'cart_adds',
        'wishlist_adds',
        'compare_adds',
        'shares',
        'reviews_count',
        'average_rating',
        'total_sales',
        'total_revenue',
        'last_viewed_at',
        'last_sold_at'
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'last_viewed_at' => 'date',
        'last_sold_at' => 'date'
    ];

    /**
     * Get the product that owns the statistics
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get or create statistics for a product
     */
    public static function getOrCreate($productId)
    {
        return self::firstOrCreate(
            ['product_id' => $productId],
            [
                'views' => 0,
                'clicks' => 0,
                'cart_adds' => 0,
                'wishlist_adds' => 0,
                'compare_adds' => 0,
                'shares' => 0,
                'reviews_count' => 0,
                'average_rating' => 0,
                'total_sales' => 0,
                'total_revenue' => 0
            ]
        );
    }

    /**
     * Increment view count
     */
    public static function incrementViews($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('views');
        $stats->update(['last_viewed_at' => now()]);
        return $stats;
    }

    /**
     * Increment click count
     */
    public static function incrementClicks($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('clicks');
        return $stats;
    }

    /**
     * Increment cart adds
     */
    public static function incrementCartAdds($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('cart_adds');
        return $stats;
    }

    /**
     * Increment wishlist adds
     */
    public static function incrementWishlistAdds($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('wishlist_adds');
        return $stats;
    }

    /**
     * Increment compare adds
     */
    public static function incrementCompareAdds($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('compare_adds');
        return $stats;
    }

    /**
     * Increment shares
     */
    public static function incrementShares($productId)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('shares');
        return $stats;
    }

    /**
     * Update sales data
     */
    public static function updateSales($productId, $quantity, $revenue)
    {
        $stats = self::getOrCreate($productId);
        $stats->increment('total_sales', $quantity);
        $stats->increment('total_revenue', $revenue);
        $stats->update(['last_sold_at' => now()]);
        return $stats;
    }

    /**
     * Update review statistics
     */
    public static function updateReviewStats($productId, $reviewCount, $averageRating)
    {
        $stats = self::getOrCreate($productId);
        $stats->update([
            'reviews_count' => $reviewCount,
            'average_rating' => $averageRating
        ]);
        return $stats;
    }

    /**
     * Get top viewed products
     */
    public static function getTopViewed($limit = 10)
    {
        return self::with('product')
                   ->orderBy('views', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get top selling products
     */
    public static function getTopSelling($limit = 10)
    {
        return self::with('product')
                   ->orderBy('total_sales', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get most wished products
     */
    public static function getMostWished($limit = 10)
    {
        return self::with('product')
                   ->orderBy('wishlist_adds', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get trending products (combination of views, clicks, and sales)
     */
    public static function getTrending($limit = 10)
    {
        return self::with('product')
                   ->orderByRaw('(views * 0.3 + clicks * 0.2 + cart_adds * 0.3 + total_sales * 0.2) DESC')
                   ->limit($limit)
                   ->get();
    }
}
