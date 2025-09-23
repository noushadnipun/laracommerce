<?php

namespace App\Models\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'comment',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean'
    ];

    /**
     * Get the product that owns the review
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Check if user can review this product
     */
    public static function canUserReview($productId, $userId = null)
    {
        if (!$userId) {
            return true; // Guest users can review
        }

        return !self::where('product_id', $productId)
                   ->where('user_id', $userId)
                   ->exists();
    }

    /**
     * Get review distribution for a product
     */
    public static function getReviewDistribution($productId)
    {
        $reviews = self::where('product_id', $productId)
                      ->where('is_approved', true)
                      ->get();

        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        foreach ($reviews as $review) {
            $distribution[$review->rating]++;
        }

        return $distribution;
    }

    /**
     * Get rating distribution for a product (alias for getReviewDistribution)
     */
    public static function getRatingDistribution($productId)
    {
        return self::getReviewDistribution($productId);
    }

    /**
     * Get approved reviews for a product
     */
    public static function getApprovedReviews($productId)
    {
        return self::where('product_id', $productId)
                   ->where('is_approved', true)
                   ->with('user')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Check if user has already reviewed a product
     */
    public static function hasUserReviewed($userId, $productId)
    {
        if (!$userId) {
            return false; // Guest users haven't reviewed
        }

        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    /**
     * Approve the review
     */
    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    /**
     * Reject the review
     */
    public function reject()
    {
        $this->update(['is_approved' => false]);
    }
}
