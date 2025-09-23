<?php

namespace App\Models\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ProductCompare extends Model
{
    use HasFactory;

    protected $table = 'product_compares';

    protected $fillable = [
        'session_id',
        'product_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that belongs to the compare list
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Add product to compare list (session-based)
     */
    public static function addToCompare($productId)
    {
        $sessionId = Session::getId();
        $compareList = self::getCompareList();

        // Check if product is already in compare list
        if (in_array($productId, $compareList)) {
            return false; // Already exists
        }

        // Check if compare list is full (max 4 products)
        if (count($compareList) >= 4) {
            return false; // List is full
        }

        // Add to session
        $compareList[] = $productId;
        Session::put('compare_list', $compareList);

        return true;
    }

    /**
     * Remove product from compare list
     */
    public static function removeFromCompare($productId)
    {
        $compareList = self::getCompareList();
        $compareList = array_diff($compareList, [$productId]);
        Session::put('compare_list', array_values($compareList));

        return true;
    }

    /**
     * Toggle product in compare list
     */
    public static function toggleCompare($productId)
    {
        $compareList = self::getCompareList();

        if (in_array($productId, $compareList)) {
            self::removeFromCompare($productId);
            return false; // Removed
        } else {
            if (count($compareList) < 4) {
                self::addToCompare($productId);
                return true; // Added
            }
            return false; // List is full
        }
    }

    /**
     * Check if product is in compare list
     */
    public static function isInCompare($productId)
    {
        $compareList = self::getCompareList();
        return in_array($productId, $compareList);
    }

    /**
     * Get compare list from session
     */
    public static function getCompareList()
    {
        return Session::get('compare_list', []);
    }

    /**
     * Get compare products with full data
     */
    public static function getCompareProducts()
    {
        $compareList = self::getCompareList();
        
        if (empty($compareList)) {
            return collect();
        }

        return Product::whereIn('id', $compareList)
                     ->with(['category', 'brand'])
                     ->get();
    }

    /**
     * Clear compare list
     */
    public static function clearCompare()
    {
        Session::forget('compare_list');
        return true;
    }

    /**
     * Get compare count
     */
    public static function getCompareCount()
    {
        return count(self::getCompareList());
    }

    /**
     * Check if compare list is full
     */
    public static function isCompareFull()
    {
        return count(self::getCompareList()) >= 4;
    }

    /**
     * Get compare list for display
     */
    public static function getCompareListForDisplay()
    {
        $products = self::getCompareProducts();
        $compareList = self::getCompareList();
        
        // Maintain the order from session
        $orderedProducts = collect();
        foreach ($compareList as $productId) {
            $product = $products->firstWhere('id', $productId);
            if ($product) {
                $orderedProducts->push($product);
            }
        }
        
        return $orderedProducts;
    }
}





