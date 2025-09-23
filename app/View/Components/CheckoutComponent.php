<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductOrder;
use App\Models\UserAddressBook;
use App\Helpers\Cart\CartHelper;

class CheckoutComponent extends Component
{
    public $cartData;
    public $user;
    public $addresses;
    public $paymentMethods;
    public $shippingMethods;
    public $isValid;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->loadCheckoutData();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.checkout-component');
    }

    /**
     * Load checkout data
     */
    private function loadCheckoutData()
    {
        $this->user = Auth::user();
        $this->addresses = [];
        $this->isValid = true;

        // Load cart data
        $cart = Session::get('cart', []);
        $this->cartData = [
            'items' => [],
            'total_items' => 0,
            'total_price' => 0,
            'subtotal' => 0,
            'shipping' => 0,
            'tax' => 0,
            'discount' => 0,
            'grand_total' => 0
        ];

        if (empty($cart)) {
            $this->isValid = false;
            return;
        }

        // Validate cart
        $cartValidation = CartHelper::validateCart();
        if (!$cartValidation['valid']) {
            $this->isValid = false;
            return;
        }

        foreach ($cart as $id => $details) {
            $product = \App\Models\Product::with('inventory')->find($details['id']);
            
            if ($product) {
                $itemTotal = $details['price'] * $details['qty'];
                
                $this->cartData['items'][] = [
                    'id' => $id,
                    'product_id' => $details['id'],
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['qty'],
                    'total' => $itemTotal,
                    'image' => $details['image'] ?? '/images/no-image.jpg',
                    'attributes' => $details['attribute'] ?? [],
                    'product' => $product
                ];
                
                $this->cartData['total_items'] += $details['qty'];
                $this->cartData['subtotal'] += $itemTotal;
            }
        }

        // Calculate totals
        $this->calculateTotals();

        // Load user addresses
        if ($this->user) {
            $this->addresses = UserAddressBook::where('user_id', $this->user->id)->get();
        }

        // Load payment methods
        $this->paymentMethods = $this->getPaymentMethods();

        // Load shipping methods
        $this->shippingMethods = $this->getShippingMethods();
    }

    /**
     * Calculate totals
     */
    private function calculateTotals()
    {
        // Get shipping cost
        $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
        $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
        $this->cartData['shipping'] = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;

        // Get discount (from coupon)
        $this->cartData['discount'] = 0;
        if (Session::has('couponApplied')) {
            $coupon = Session::get('couponApplied');
            $this->cartData['discount'] = $coupon['discount_amount'] ?? 0;
        }

        // Calculate tax (if applicable)
        $this->cartData['tax'] = 0;

        // Calculate grand total
        $this->cartData['grand_total'] = $this->cartData['subtotal'] + $this->cartData['shipping'] + $this->cartData['tax'] - $this->cartData['discount'];
        $this->cartData['total_price'] = $this->cartData['grand_total'];
    }

    /**
     * Get payment methods
     */
    private function getPaymentMethods()
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
    private function getShippingMethods()
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
}