<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product\ProductCompare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompareController extends Controller
{
    /**
     * Add product to compare
     */
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::with(['brand', 'category'])->find($productId);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $compareList = Session::get('compare_list', []);
        
        // Use ProductCompare model
        $added = ProductCompare::addToCompare($productId);
        
        if ($added) {
            // Track compare add
            $product->trackCompareAdd();
        }
        
        if (!$added) {
            $compareList = ProductCompare::getCompareList();
            if (in_array($productId, $compareList)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in compare list'
                ], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You can compare maximum 4 products at a time'
                ], 400);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to compare list',
            'compare_count' => count(ProductCompare::getCompareList())
        ]);
    }
    
    /**
     * Remove product from compare
     */
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        
        $removed = ProductCompare::removeFromCompare($productId);
        
        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from compare list',
                'compare_count' => count(ProductCompare::getCompareList())
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Product not found in compare list'
        ], 404);
    }
    
    /**
     * Get compare list
     */
    public function index()
    {
        $products = ProductCompare::getCompareProducts();
        
        return view('frontend.compare.index', compact('products'));
    }
    
    /**
     * Clear compare list
     */
    public function clear()
    {
        ProductCompare::clearCompareList();
        
        return response()->json([
            'success' => true,
            'message' => 'Compare list cleared'
        ]);
    }
    
    /**
     * Check if product is in compare list
     */
    public function check(Request $request)
    {
        $productId = $request->input('product_id');
        $compareList = ProductCompare::getCompareList();
        
        return response()->json([
            'in_compare' => ProductCompare::isInCompare($productId),
            'compare_count' => count($compareList)
        ]);
    }
}
