<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    
    /**
     * Track product click
     */
    public function trackClick(Request $request)
    {
        try {
            \Log::info('Click tracking request received', $request->all());
            
            $productId = $request->input('product_id');
            
            if (!$productId) {
                \Log::warning('No product ID provided in click tracking request');
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }
            
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            // Track the click
            $product->trackClick();
            
            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Product click tracking error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to track click'
            ], 500);
        }
    }
    
    /**
     * Track product click via GET request
     */
    public function trackClickGet($productId)
    {
        try {
            \Log::info('Click tracking GET request received', ['product_id' => $productId]);
            
            if (!$productId) {
                \Log::warning('No product ID provided in click tracking GET request');
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }
            
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            // Track the click
            $product->trackClick();
            
            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Product click tracking GET error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to track click'
            ], 500);
        }
    }
}
