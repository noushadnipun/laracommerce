<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'user']);
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }
        
        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }
        
        // Search by product name or reviewer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort by date
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $reviews = $query->paginate(20);
        
        // Get statistics
        $stats = [
            'total' => ProductReview::count(),
            'pending' => ProductReview::where('is_approved', false)->count(),
            'approved' => ProductReview::where('is_approved', true)->count(),
            'average_rating' => ProductReview::where('is_approved', true)->avg('rating'),
            'rating_distribution' => ProductReview::where('is_approved', true)
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get()
        ];
        
        return view('admin.review.index', compact('reviews', 'stats'));
    }
    
    /**
     * Show the form for editing a review
     */
    public function edit($id)
    {
        $review = ProductReview::with(['product', 'user'])->findOrFail($id);
        return view('admin.review.edit', compact('review'));
    }
    
    /**
     * Update the specified review
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_approved' => 'boolean'
        ]);
        
        $review = ProductReview::findOrFail($id);
        $review->update($request->all());
        
        return redirect()->route('admin.review.index')
            ->with('success', 'Review updated successfully');
    }
    
    /**
     * Approve a review
     */
    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->approve();
        
        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully'
        ]);
    }
    
    /**
     * Reject a review
     */
    public function reject($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->reject();
        
        return response()->json([
            'success' => true,
            'message' => 'Review rejected and deleted successfully'
        ]);
    }
    
    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'integer|exists:product_reviews,id'
        ]);
        
        $count = ProductReview::whereIn('id', $request->review_ids)
            ->update(['is_approved' => true]);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} reviews approved successfully"
        ]);
    }
    
    /**
     * Bulk reject reviews
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'integer|exists:product_reviews,id'
        ]);
        
        $count = ProductReview::whereIn('id', $request->review_ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "{$count} reviews rejected and deleted successfully"
        ]);
    }
    
    /**
     * Delete a review
     */
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
    
    /**
     * Get review statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_reviews' => ProductReview::count(),
            'pending_reviews' => ProductReview::where('is_approved', false)->count(),
            'approved_reviews' => ProductReview::where('is_approved', true)->count(),
            'average_rating' => round(ProductReview::where('is_approved', true)->avg('rating') ?: 0, 1),
            'recent_reviews' => ProductReview::with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
        
        return response()->json($stats);
    }
}
