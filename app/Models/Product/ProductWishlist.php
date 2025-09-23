<?php

namespace App\Models\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = [
        'user_id',
        'product_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that belongs to the wishlist
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the wishlist
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add product to user's wishlist
     */
    public static function addToWishlist($userId, $productId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    /**
     * Remove product from user's wishlist
     */
    public static function removeFromWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->delete();
    }

    /**
     * Toggle product in user's wishlist
     */
    public static function toggleWishlist($userId, $productId)
    {
        $wishlist = self::where('user_id', $userId)
                       ->where('product_id', $productId)
                       ->first();

        if ($wishlist) {
            $wishlist->delete();
            return false; // Removed
        } else {
            self::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return true; // Added
        }
    }

    /**
     * Check if product is in user's wishlist
     */
    public static function isInWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    /**
     * Get user's wishlist products
     */
    public static function getUserWishlist($userId)
    {
        return self::where('user_id', $userId)
                   ->with('product')
                   ->get()
                   ->pluck('product')
                   ->filter(); // Remove null values
    }

    /**
     * Get user's wishlist products with proper null handling
     */
    public static function getUserWishlistProducts($userId)
    {
        return self::where('user_id', $userId)
                   ->whereHas('product') // Only get wishlist items where product exists
                   ->with('product')
                   ->get()
                   ->pluck('product');
    }

    /**
     * Get wishlist count for user
     */
    public static function getWishlistCount($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Get products in wishlist with pagination
     */
    public static function getWishlistProducts($userId, $perPage = 12)
    {
        return self::where('user_id', $userId)
                   ->with(['product.category', 'product.brand'])
                   ->paginate($perPage);
    }

    /**
     * Clean up orphaned wishlist entries (where product no longer exists)
     */
    public static function cleanupOrphanedEntries()
    {
        return self::whereDoesntHave('product')->delete();
    }
}





