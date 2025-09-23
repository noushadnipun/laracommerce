<?php

namespace App\Components\Cart;

use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Helpers\Cart\CartHelper;

class CartComponent
{
    /**
     * Get cart data
     */
    public static function getCartData()
    {
        $cart = Session::get('cart', []);
        $cartData = [
            'items' => [],
            'total_items' => 0,
            'total_price' => 0,
            'subtotal' => 0,
            'shipping' => 0,
            'tax' => 0,
            'discount' => 0,
            'grand_total' => 0
        ];

        foreach ($cart as $id => $details) {
            $product = Product::with('inventory')->find($details['id']);
            
            if ($product) {
                $itemTotal = $details['price'] * $details['qty'];
                
                $cartData['items'][] = [
                    'id' => $id,
                    'product_id' => $details['id'],
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['qty'],
                    'total' => $itemTotal,
                    'image' => $details['image'] ?? '/images/no-image.jpg',
                    'attributes' => $details['attribute'] ?? [],
                    'product' => $product,
                    'stock_status' => self::getStockStatus($product, $details['qty'])
                ];
                
                $cartData['total_items'] += $details['qty'];
                $cartData['subtotal'] += $itemTotal;
            }
        }

        // Calculate shipping, tax, discount, and grand total
        $cartData = self::calculateTotals($cartData);
        
        return $cartData;
    }

    /**
     * Add item to cart
     */
    public static function addToCart($productId, $quantity = 1, $attributes = [])
    {
        $product = Product::with('inventory')->find($productId);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Check stock availability
        if ($product->isOutOfStock()) {
            return ['success' => false, 'message' => 'This product is currently out of stock'];
        }

        if ($product->inventory && $product->inventory->current_stock < $quantity) {
            return ['success' => false, 'message' => 'Only ' . $product->inventory->current_stock . ' items available in stock'];
        }

        $cart = Session::get('cart', []);
        $cartKey = $productId . '_' . md5(serialize($attributes));

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['qty'] + $quantity;
            
            // Check stock again for new quantity
            if ($product->inventory && $product->inventory->current_stock < $newQuantity) {
                return ['success' => false, 'message' => 'Only ' . $product->inventory->current_stock . ' items available in stock'];
            }
            
            $cart[$cartKey]['qty'] = $newQuantity;
        } else {
            $cart[$cartKey] = [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $quantity,
                'image' => $product->featured_image ?? '/images/no-image.jpg',
                'attribute' => $attributes
            ];
        }

        Session::put('cart', $cart);
        
        return ['success' => true, 'message' => 'Product added to cart successfully', 'cart_count' => count($cart)];
    }

    /**
     * Update cart item quantity
     */
    public static function updateCartItem($cartKey, $quantity)
    {
        $cart = Session::get('cart', []);
        
        if (!isset($cart[$cartKey])) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }

        $product = Product::with('inventory')->find($cart[$cartKey]['id']);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Check stock availability
        if ($product->inventory && $product->inventory->current_stock < $quantity) {
            return ['success' => false, 'message' => 'Only ' . $product->inventory->current_stock . ' items available in stock'];
        }

        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $cart[$cartKey]['qty'] = $quantity;
        }

        Session::put('cart', $cart);
        
        return ['success' => true, 'message' => 'Cart updated successfully'];
    }

    /**
     * Remove item from cart
     */
    public static function removeFromCart($cartKey)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put('cart', $cart);
            return ['success' => true, 'message' => 'Item removed from cart'];
        }
        
        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    /**
     * Clear cart
     */
    public static function clearCart()
    {
        Session::forget('cart');
        return ['success' => true, 'message' => 'Cart cleared successfully'];
    }

    /**
     * Get cart count
     */
    public static function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['qty'];
        }
        
        return $count;
    }

    /**
     * Validate cart
     */
    public static function validateCart()
    {
        return CartHelper::validateCart();
    }

    /**
     * Get stock status for product
     */
    private static function getStockStatus($product, $requestedQuantity)
    {
        if (!$product->inventory) {
            return [
                'status' => 'unknown',
                'message' => 'Stock information not available',
                'available' => 0
            ];
        }

        if ($product->isOutOfStock()) {
            return [
                'status' => 'out_of_stock',
                'message' => 'Out of Stock',
                'available' => 0
            ];
        }

        if ($product->inventory->current_stock < $requestedQuantity) {
            return [
                'status' => 'low_stock',
                'message' => 'Only ' . $product->inventory->current_stock . ' available',
                'available' => $product->inventory->current_stock
            ];
        }

        return [
            'status' => 'in_stock',
            'message' => 'In Stock (' . $product->inventory->current_stock . ' available)',
            'available' => $product->inventory->current_stock
        ];
    }

    /**
     * Calculate totals
     */
    private static function calculateTotals($cartData)
    {
        // Get shipping cost
        $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
        $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
        $cartData['shipping'] = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;

        // Get discount (from coupon)
        $cartData['discount'] = 0;
        if (Session::has('couponApplied')) {
            $coupon = Session::get('couponApplied');
            $cartData['discount'] = $coupon['discount_amount'] ?? 0;
        }

        // Calculate tax (if applicable)
        $cartData['tax'] = 0; // You can implement tax calculation here

        // Calculate grand total
        $cartData['grand_total'] = $cartData['subtotal'] + $cartData['shipping'] + $cartData['tax'] - $cartData['discount'];
        $cartData['total_price'] = $cartData['grand_total'];

        return $cartData;
    }

    /**
     * Get cart summary for mini cart
     */
    public static function getCartSummary()
    {
        $cartData = self::getCartData();
        
        return [
            'count' => $cartData['total_items'],
            'total' => $cartData['total_price'],
            'items' => array_slice($cartData['items'], 0, 3) // Show only first 3 items
        ];
    }
}

