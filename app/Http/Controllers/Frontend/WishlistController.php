<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductWishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to wishlist'
            ], 401);
        }

        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Check if already in wishlist
        if (ProductWishlist::isInWishlist($userId, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ], 400);
        }

        // Add to wishlist
                ProductWishlist::addToWishlist($userId, $productId);

                // Track wishlist add
                $product->trackWishlistAdd();

                return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully',
            'wishlist_count' => ProductWishlist::where('user_id', $userId)->count()
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage wishlist'
            ], 401);
        }

        $productId = $request->input('product_id');
        $userId = Auth::id();

        $removed = ProductWishlist::removeFromWishlist($userId, $productId);

        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
                'wishlist_count' => ProductWishlist::where('user_id', $userId)->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in wishlist'
        ], 404);
    }

    /**
     * Toggle wishlist status
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage wishlist'
            ], 401);
        }

        $productId = $request->input('product_id');
        $userId = Auth::id();

        if (ProductWishlist::isInWishlist($userId, $productId)) {
            return $this->remove($request);
        } else {
            return $this->add($request);
        }
    }

    /**
     * Get user's wishlist
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('frontend_customer_login');
        }

        $wishlistItems = ProductWishlist::getUserWishlistProducts(Auth::id());
        
        return view('frontend.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'in_wishlist' => false
            ]);
        }

        $productId = $request->input('product_id');
        $userId = Auth::id();

        return response()->json([
            'in_wishlist' => ProductWishlist::isInWishlist($userId, $productId)
        ]);
    }
}
