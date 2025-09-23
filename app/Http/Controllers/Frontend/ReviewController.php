<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Check if user has already reviewed this product
        if ($userId && ProductReview::hasUserReviewed($userId, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product'
            ], 400);
        }

        // Create review
        $review = ProductReview::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'is_approved' => false // Requires admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully. It will be published after admin approval.',
            'review' => $review
        ]);
    }

    /**
     * Get product reviews
     */
    public function getProductReviews($productId)
    {
        $reviews = ProductReview::getApprovedReviews($productId);
        $averageRating = Product::getAverageRatingById($productId);
        $reviewCount = Product::getReviewCountById($productId);
        $ratingDistribution = ProductReview::getRatingDistribution($productId);

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'review_count' => $reviewCount,
            'rating_distribution' => $ratingDistribution
        ]);
    }

    /**
     * Get review statistics for a product
     */
    public function getReviewStats($productId)
    {
        $averageRating = Product::getAverageRatingById($productId);
        $reviewCount = Product::getReviewCountById($productId);
        $ratingDistribution = ProductReview::getRatingDistribution($productId);

        return response()->json([
            'success' => true,
            'average_rating' => round($averageRating, 1),
            'review_count' => $reviewCount,
            'rating_distribution' => $ratingDistribution
        ]);
    }

    /**
     * Check if user can review product
     */
    public function canReview($productId)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'can_review' => false,
                'message' => 'Please login to write a review'
            ]);
        }

        $hasReviewed = ProductReview::hasUserReviewed($userId, $productId);
        
        return response()->json([
            'can_review' => !$hasReviewed,
            'has_reviewed' => $hasReviewed,
            'message' => $hasReviewed ? 'You have already reviewed this product' : 'You can review this product'
        ]);
    }
}
