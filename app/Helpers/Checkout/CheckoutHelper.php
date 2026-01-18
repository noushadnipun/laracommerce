<?php

namespace App\Helpers\Checkout;

use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Models\StoreSettings;
use App\Helpers\Cart\CartHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutHelper
{
    /**
     * Process checkout
     */
    public static function processCheckout($request)
    {
        // Validate cart
        $cartValidation = CartHelper::validateCart();
        if (!$cartValidation['valid']) {
            return ['success' => false, 'message' => 'Cart validation failed', 'errors' => $cartValidation['errors']];
        }
        
        $cart = $cartValidation['cart'];
        if (empty($cart)) {
            return ['success' => false, 'message' => 'Cart is empty'];
        }
        
        // Calculate totals
        $subTotal = CartHelper::getCartTotal();
        $shippingCost = self::calculateShippingCost($request);
        $couponDiscount = self::getCouponDiscount();
        $taxAmount = self::calculateTax($subTotal);
        $finalAmount = $subTotal + $shippingCost + $taxAmount - $couponDiscount;
        
        DB::beginTransaction();
        try {
            // Create order
            $order = self::createOrder($request, $subTotal, $shippingCost, $couponDiscount, $taxAmount, $finalAmount);
            
            // Create order details
            self::createOrderDetails($order->id, $cart);
            
            // Clear cart
            CartHelper::clearCart();
            
            DB::commit();
            
            return [
                'success' => true, 
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'order_code' => $order->order_code
            ];
            
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()];
        }
    }

    /**
     * Create order
     */
    private static function createOrder($request, $subTotal, $shippingCost, $couponDiscount, $taxAmount, $finalAmount)
    {
        $order = new ProductOrder();
        $order->order_code = self::generateOrderCode();
        $order->user_id = Auth::id();
        $order->customer_name = $request->name;
        $order->customer_phone = $request->phone;
        $order->customer_address = $request->address;
        $order->customer_thana = $request->thana;
        $order->customer_city = $request->city;
        $order->customer_postal_code = $request->postal_code ?? '';
        $order->customer_country = $request->country ?? 'Bangladesh';
        $order->total_amount = $subTotal;
        $order->shipping_cost = $shippingCost;
        $order->tax_amount = $taxAmount;
        $order->discount_amount = $couponDiscount;
        $order->final_amount = $finalAmount;
        $order->currency = 'BDT';
        $order->note = $request->note ?? '';
        $order->payment_type = self::getPaymentMethod($request->payment_method);
        $order->payment_status = 'Pending';
        $order->delivery_status = 'Pending';
        $order->order_status = 'pending';
        $order->save();
        
        return $order;
    }

    /**
     * Create order details
     */
    private static function createOrderDetails($orderId, $cart)
    {
        foreach ($cart as $item) {
            $orderDetail = new ProductOrderDetails();
            $orderDetail->user_id = Auth::id();
            $orderDetail->order_id = $orderId;
            $orderDetail->product_id = $item['id'];
            $orderDetail->attribute = $item['attribute'] ?? [];
            $orderDetail->qty = $item['qty'];
            $orderDetail->price = $item['price'] * $item['qty'];
            $orderDetail->save();
        }
    }

    /**
     * Generate order code
     */
    private static function generateOrderCode()
    {
        return '#OD-' . date('Ymd') . '-' . str_pad(ProductOrder::count() + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate shipping cost
     */
    private static function calculateShippingCost($request)
    {
        $shippingType = StoreSettings::where('meta_name', 'shipping_type')->first();
        $shippingRate = StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
        
        if ($shippingType && $shippingType->meta_value === 'flat_rate') {
            return $shippingRate ? (float)$shippingRate->meta_value : 0;
        }
        
        return 0;
    }

    /**
     * Get coupon discount
     */
    private static function getCouponDiscount()
    {
        $coupon = session('couponApplied');
        return $coupon ? $coupon['couponAnountWithSign'] : 0;
    }

    /**
     * Calculate tax
     */
    private static function calculateTax($subTotal)
    {
        // Implement tax calculation based on business rules
        return 0;
    }

    /**
     * Get payment method
     */
    private static function getPaymentMethod($method)
    {
        $methods = [
            'cash' => 'Cash On Delivery',
            'ssl' => 'SSL Commerz Payment Gateway',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
        ];
        
        return $methods[$method] ?? 'Cash On Delivery';
    }

    /**
     * Get checkout summary
     */
    public static function getCheckoutSummary()
    {
        $cart = CartHelper::getCartSummary();
        $shippingCost = self::calculateShippingCost(request());
        $couponDiscount = self::getCouponDiscount();
        $taxAmount = self::calculateTax($cart['total']);
        $finalAmount = $cart['total'] + $shippingCost + $taxAmount - $couponDiscount;
        
        return [
            'subtotal' => $cart['total'],
            'shipping_cost' => $shippingCost,
            'tax_amount' => $taxAmount,
            'coupon_discount' => $couponDiscount,
            'final_amount' => $finalAmount,
            'formatted_subtotal' => CartHelper::formatAmount($cart['total']),
            'formatted_shipping' => CartHelper::formatAmount($shippingCost),
            'formatted_tax' => CartHelper::formatAmount($taxAmount),
            'formatted_discount' => CartHelper::formatAmount($couponDiscount),
            'formatted_final' => CartHelper::formatAmount($finalAmount),
        ];
    }

    /**
     * Validate checkout data
     */
    public static function validateCheckoutData($request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'thana' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|in:cash,ssl,bkash,nagad,rocket',
        ];
        
        $messages = [
            'name.required' => 'Customer name is required',
            'phone.required' => 'Phone number is required',
            'address.required' => 'Address is required',
            'thana.required' => 'Thana is required',
            'city.required' => 'City is required',
            'payment_method.required' => 'Payment method is required',
        ];
        
        $validator = validator($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return ['valid' => false, 'errors' => $validator->errors()];
        }
        
        return ['valid' => true, 'errors' => []];
    }

    /**
     * Get payment methods (deprecated - use PaymentMethodService instead)
     */
    public static function getPaymentMethods()
    {
        return \App\Services\PaymentMethodService::getEnabledMethods();
    }
}