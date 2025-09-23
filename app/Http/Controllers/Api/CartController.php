<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Cart\CartComponent;

class CartController extends Controller
{
    /**
     * Get cart data
     */
    public function index()
    {
        try {
            $cartData = CartComponent::getCartData();
            
            return response()->json([
                'success' => true,
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'attributes' => 'nullable|array'
        ]);

        try {
            $result = CartComponent::addToCart(
                $request->product_id,
                $request->quantity,
                $request->attributes ?? []
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'cart_count' => $result['cart_count']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $cartKey)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        try {
            $result = CartComponent::updateCartItem($cartKey, $request->quantity);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy($cartKey)
    {
        try {
            $result = CartComponent::removeFromCart($cartKey);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        try {
            $result = CartComponent::clearCart();

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart count
     */
    public function count()
    {
        try {
            $count = CartComponent::getCartCount();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart summary
     */
    public function summary()
    {
        try {
            $summary = CartComponent::getCartSummary();

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate cart
     */
    public function validate()
    {
        try {
            $validation = CartComponent::validateCart();

            return response()->json([
                'success' => true,
                'valid' => $validation['valid'],
                'errors' => $validation['errors'] ?? [],
                'data' => $validation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}