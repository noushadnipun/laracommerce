<?php

namespace App\Components\Checkout;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Models\UserAddressBook;
use App\Components\Cart\CartComponent;
use App\Helpers\Checkout\CheckoutHelper;

class CheckoutComponent
{
    /**
     * Get checkout data
     */
    public static function getCheckoutData()
    {
        $cartData = CartComponent::getCartData();
        
        if (empty($cartData['items'])) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }

        // Validate cart
        $cartValidation = CartComponent::validateCart();
        if (!$cartValidation['valid']) {
            return ['success' => false, 'message' => 'Some items in your cart are no longer available: ' . implode(', ', $cartValidation['errors'])];
        }

        $user = Auth::user();
        $addresses = [];
        
        if ($user) {
            $addresses = UserAddressBook::where('user_id', $user->id)->get();
        }

        return [
            'success' => true,
            'cart' => $cartData,
            'user' => $user,
            'addresses' => $addresses,
            'payment_methods' => self::getPaymentMethods(),
            'shipping_methods' => self::getShippingMethods()
        ];
    }

    /**
     * Process checkout
     */
    public static function processCheckout($request)
    {
        try {
            // Validate request
            $validation = self::validateCheckoutRequest($request);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            // Get and validate cart
            $cartData = CartComponent::getCartData();
            if (empty($cartData['items'])) {
                return ['success' => false, 'message' => 'Cart is empty'];
            }

            $cartValidation = CartComponent::validateCart();
            if (!$cartValidation['valid']) {
                return ['success' => false, 'message' => 'Some items in your cart are no longer available: ' . implode(', ', $cartValidation['errors'])];
            }

            // Create order
            $order = self::createOrder($request, $cartData);
            if (!$order) {
                return ['success' => false, 'message' => 'Failed to create order'];
            }

            // Create order details
            $orderDetails = self::createOrderDetails($order, $cartData);
            if (!$orderDetails) {
                return ['success' => false, 'message' => 'Failed to create order details'];
            }

            // Process payment
            if ($request->payment_method === 'ssl') {
                return self::processSSLPayment($order);
            } else {
                // Cash on delivery or other payment methods
                Session::forget('cart');
                Session::forget('couponApplied');
                
                return [
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'redirect' => route('frontend_customer_dashboard', '#orders')
                ];
            }

        } catch (\Exception $e) {
            \Log::error('Checkout Process Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Checkout process failed. Please try again.'];
        }
    }

    /**
     * Create order
     */
    private static function createOrder($request, $cartData)
    {
        $user = Auth::user();
        
        $order = new ProductOrder();
        $order->user_id = $user ? $user->id : null;
        $order->tran_id = 'TXN_' . time() . '_' . rand(1000, 9999);
        $order->customer_name = $request->customer_name;
        $order->customer_email = $request->customer_email;
        $order->customer_phone = $request->customer_phone;
        $order->customer_address = $request->customer_address;
        $order->customer_city = $request->customer_city ?? 'Dhaka';
        $order->customer_state = $request->customer_state ?? 'Dhaka';
        $order->customer_postcode = $request->customer_postcode ?? '1000';
        $order->customer_country = 'Bangladesh';
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'Pending';
        $order->order_status = 'pending';
        $order->subtotal = $cartData['subtotal'];
        $order->shipping_cost = $cartData['shipping'];
        $order->tax_amount = $cartData['tax'];
        $order->discount_amount = $cartData['discount'];
        $order->total_amount = $cartData['grand_total'];
        $order->currency = 'BDT';
        $order->notes = $request->notes ?? '';
        
        if ($order->save()) {
            return $order;
        }
        
        return null;
    }

    /**
     * Create order details
     */
    private static function createOrderDetails($order, $cartData)
    {
        $success = true;
        
        foreach ($cartData['items'] as $item) {
            $orderDetail = new ProductOrderDetails();
            $orderDetail->user_id = $order->user_id;
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $item['product_id'];
            $orderDetail->attribute = $item['attributes'];
            $orderDetail->qty = $item['quantity'];
            $orderDetail->price = $item['total'];
            
            if (!$orderDetail->save()) {
                $success = false;
                break;
            }
        }
        
        return $success;
    }

    /**
     * Process SSL payment
     */
    private static function processSSLPayment($order)
    {
        try {
            $sslc = new \App\Library\SslCommerz\SslCommerzNotification();
            
            $paymentData = [
                'total_amount' => $order->total_amount,
                'currency' => 'BDT',
                'tran_id' => $order->tran_id,
                'product_category' => 'E-commerce',
                'product_name' => 'Order #' . $order->id,
                'product_profile' => 'physical-goods',
                'cus_name' => $order->customer_name,
                'cus_email' => $order->customer_email,
                'cus_add1' => $order->customer_address,
                'cus_add2' => '',
                'cus_city' => $order->customer_city,
                'cus_state' => $order->customer_state,
                'cus_postcode' => $order->customer_postcode,
                'cus_country' => 'Bangladesh',
                'cus_phone' => $order->customer_phone,
                'cus_fax' => '',
                'ship_name' => $order->customer_name,
                'ship_add1' => $order->customer_address,
                'ship_add2' => '',
                'ship_city' => $order->customer_city,
                'ship_state' => $order->customer_state,
                'ship_postcode' => $order->customer_postcode,
                'ship_country' => 'Bangladesh',
                'shipping_method' => 'NO',
                'value_a' => $order->id,
                'value_b' => 'order',
                'value_c' => 'checkout',
                'value_d' => 'laracommerce'
            ];
            
            $payment_options = $sslc->makePayment($paymentData, 'hosted');
            
            if (!is_array($payment_options)) {
                return ['success' => false, 'message' => 'Payment initiation failed. Please try again.'];
            }
            
            return [
                'success' => true,
                'message' => 'Redirecting to payment gateway...',
                'payment_options' => $payment_options
            ];
            
        } catch (\Exception $e) {
            \Log::error('SSL Payment Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment system error. Please try again later.'];
        }
    }

    /**
     * Validate checkout request
     */
    private static function validateCheckoutRequest($request)
    {
        $required = ['customer_name', 'customer_email', 'customer_phone', 'customer_address', 'payment_method'];
        
        foreach ($required as $field) {
            if (empty($request->$field)) {
                return ['valid' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'];
            }
        }
        
        if (!filter_var($request->customer_email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Please enter a valid email address'];
        }
        
        return ['valid' => true];
    }

    /**
     * Get payment methods
     */
    private static function getPaymentMethods()
    {
        return [
            'ssl' => [
                'name' => 'SSL Commerz',
                'description' => 'Pay with bKash, Nagad, Rocket, or Card',
                'icon' => 'fas fa-credit-card',
                'enabled' => true
            ],
            'cod' => [
                'name' => 'Cash on Delivery',
                'description' => 'Pay when you receive the order',
                'icon' => 'fas fa-money-bill-wave',
                'enabled' => true
            ]
        ];
    }

    /**
     * Get shipping methods
     */
    private static function getShippingMethods()
    {
        return [
            'standard' => [
                'name' => 'Standard Shipping',
                'description' => '5-7 business days',
                'cost' => 50,
                'enabled' => true
            ],
            'express' => [
                'name' => 'Express Shipping',
                'description' => '2-3 business days',
                'cost' => 100,
                'enabled' => true
            ]
        ];
    }

    /**
     * Save customer address
     */
    public static function saveAddress($request)
    {
        if (!Auth::check()) {
            return ['success' => false, 'message' => 'Please login to save address'];
        }

        $address = new UserAddressBook();
        $address->user_id = Auth::id();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postcode = $request->postcode;
        $address->country = $request->country ?? 'Bangladesh';
        $address->is_default = $request->is_default ?? false;

        if ($address->save()) {
            return ['success' => true, 'message' => 'Address saved successfully', 'address' => $address];
        }

        return ['success' => false, 'message' => 'Failed to save address'];
    }

    /**
     * Get order summary
     */
    public static function getOrderSummary($orderId)
    {
        $order = ProductOrder::with(['orderDetails.product'])->find($orderId);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        return [
            'success' => true,
            'order' => $order,
            'items' => $order->orderDetails,
            'status' => [
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'order_status_label' => ucfirst($order->order_status),
                'payment_status_label' => ucfirst($order->payment_status)
            ]
        ];
    }
}

