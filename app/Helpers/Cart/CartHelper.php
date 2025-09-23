<?php

namespace App\Helpers\Cart;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartHelper
{
    /**
     * Add product to cart
     */
    public static function addToCart($productId, $quantity = 1, $attributes = [])
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }
        
        // Check stock availability
        if ($product->inventory && $product->inventory->current_stock < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock available'];
        }
        
        $cart = Session::get('cart', []);
        $cartKey = $productId . '_' . md5(serialize($attributes));
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $productId,
                'name' => $product->title,
                'price' => $product->sale_price ?: $product->regular_price,
                'qty' => $quantity,
                'attribute' => $attributes,
                'image' => $product->getFeaturedImageUrl(),
                'slug' => $product->slug,
            ];
        }
        
        Session::put('cart', $cart);
        
        return ['success' => true, 'message' => 'Product added to cart', 'cart_count' => count($cart)];
    }

    /**
     * Update cart item quantity
     */
    public static function updateCart($cartKey, $quantity)
    {
        $cart = Session::get('cart', []);
        
        if (!isset($cart[$cartKey])) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }
        
        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $cart[$cartKey]['qty'] = $quantity;
        }
        
        Session::put('cart', $cart);
        
        return ['success' => true, 'message' => 'Cart updated', 'cart_count' => count($cart)];
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
            return ['success' => true, 'message' => 'Item removed from cart', 'cart_count' => count($cart)];
        }
        
        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    /**
     * Get cart contents
     */
    public static function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Get cart count
     */
    public static function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }

    /**
     * Get cart total
     */
    public static function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        
        return $total;
    }

    /**
     * Clear cart
     */
    public static function clearCart()
    {
        Session::forget('cart');
        return ['success' => true, 'message' => 'Cart cleared'];
    }

    /**
     * Get cart summary
     */
    public static function getCartSummary()
    {
        $cart = self::getCart();
        $total = self::getCartTotal();
        $count = self::getCartCount();
        
        return [
            'items' => $cart,
            'total' => $total,
            'count' => $count,
            'formatted_total' => self::formatAmount($total),
        ];
    }

    /**
     * Format amount
     */
    public static function formatAmount($amount)
    {
        return '৳' . number_format($amount, 2);
    }

    /**
     * Check if cart is empty
     */
    public static function isEmpty()
    {
        return empty(Session::get('cart', []));
    }

    /**
     * Get cart item by key
     */
    public static function getCartItem($cartKey)
    {
        $cart = Session::get('cart', []);
        return $cart[$cartKey] ?? null;
    }

    /**
     * Validate cart items
     */
    public static function validateCart()
    {
        $cart = Session::get('cart', []);
        $errors = [];
        
        foreach ($cart as $key => $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                $errors[] = "Product '{$item['name']}' is no longer available";
                unset($cart[$key]);
                continue;
            }
            
            if ($product->inventory && $product->inventory->current_stock < $item['qty']) {
                $errors[] = "Insufficient stock for '{$item['name']}'";
                $cart[$key]['qty'] = $product->inventory->current_stock;
            }
        }
        
        Session::put('cart', $cart);
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'cart' => $cart
        ];
    }
}