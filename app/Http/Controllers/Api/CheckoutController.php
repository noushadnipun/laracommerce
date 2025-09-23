<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Checkout\CheckoutComponent;

class CheckoutController extends Controller
{
    /**
     * Get checkout data
     */
    public function index()
    {
        try {
            $checkoutData = CheckoutComponent::getCheckoutData();

            if ($checkoutData['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $checkoutData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $checkoutData['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get checkout data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'customer_city' => 'nullable|string|max:100',
            'customer_state' => 'nullable|string|max:100',
            'customer_postcode' => 'nullable|string|max:20',
            'payment_method' => 'required|in:ssl,cod',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $result = CheckoutComponent::processCheckout($request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'order_id' => $result['order_id'] ?? null,
                    'redirect' => $result['redirect'] ?? null,
                    'payment_options' => $result['payment_options'] ?? null
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
                'message' => 'Checkout process failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save customer address
     */
    public function saveAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean'
        ]);

        try {
            $result = CheckoutComponent::saveAddress($request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'address' => $result['address']
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
                'message' => 'Failed to save address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order summary
     */
    public function orderSummary($orderId)
    {
        try {
            $result = CheckoutComponent::getOrderSummary($orderId);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get order summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment methods
     */
    public function paymentMethods()
    {
        try {
            $methods = CheckoutComponent::getPaymentMethods();

            return response()->json([
                'success' => true,
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get shipping methods
     */
    public function shippingMethods()
    {
        try {
            $methods = CheckoutComponent::getShippingMethods();

            return response()->json([
                'success' => true,
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get shipping methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}