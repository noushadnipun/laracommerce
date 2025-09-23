<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Helpers\Cart\CartHelper;

class CartComponent extends Component
{
    public $cartData;
    public $totalItems;
    public $totalPrice;
    public $isEmpty;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->loadCartData();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.cart-component');
    }

    /**
     * Load cart data
     */
    private function loadCartData()
    {
        $cart = Session::get('cart', []);
        $this->cartData = [];
        $this->totalItems = 0;
        $this->totalPrice = 0;
        $this->isEmpty = empty($cart);

        if (!$this->isEmpty) {
            foreach ($cart as $id => $details) {
                $product = Product::with('inventory')->find($details['id'] ?? null);

                if ($product) {
                    $itemTotal = ($details['price'] ?? 0) * ($details['qty'] ?? 0);

                    // Resolve best image URL (prefer model helper first)
                    $imageUrl = '';
                    // 1) Product helper
                    if (method_exists($product, 'getFeaturedImageUrl')) {
                        $imageUrl = $product->getFeaturedImageUrl();
                    }
                    // 2) Direct session image fields
                    if (empty($imageUrl)) {
                        if (!empty($details['image'])) {
                            $imageUrl = $details['image'];
                        } elseif (!empty($details['image_url'])) {
                            $imageUrl = $details['image_url'];
                        }
                    }
                    // 3) Featured image via Media or uploads path
                    if (empty($imageUrl)) {
                        $featured = $details['featured_image'] ?? ($product->featured_image ?? null);
                        if (!empty($featured)) {
                            $mediaPath = class_exists('App\\Models\\Media') ? (\App\Models\Media::fileLocation($featured) ?? null) : null;
                            $imageUrl = $mediaPath ?: asset('uploads/products/' . $featured);
                        }
                    }
                    // 4) Product arrays
                    if (empty($imageUrl) && is_array($product->product_image ?? null) && count($product->product_image) > 0) {
                        $imageUrl = asset('uploads/products/' . $product->product_image[0]);
                    }
                    if (empty($imageUrl) && is_array($product->remote_images ?? null) && count($product->remote_images) > 0) {
                        $imageUrl = $product->remote_images[0];
                    }
                    // 5) Fallback
                    if (empty($imageUrl)) {
                        $imageUrl = asset('public/frontend/images/no-images.jpg');
                    }

                    $this->cartData[] = [
                        'id' => $id,
                        'product_id' => $details['id'] ?? null,
                        'name' => $details['name'] ?? ($product->title ?? 'Product'),
                        'price' => $details['price'] ?? 0,
                        'quantity' => $details['qty'] ?? 0,
                        'total' => $itemTotal,
                        'image' => $imageUrl,
                        'attributes' => $details['attribute'] ?? [],
                        'product' => $product,
                        'stock_status' => $this->getStockStatus($product, $details['qty'] ?? 0)
                    ];

                    $this->totalItems += ($details['qty'] ?? 0);
                    $this->totalPrice += $itemTotal;
                }
            }
        }

        // Calculate shipping and other costs
        $this->calculateTotals();
    }

    /**
     * Get stock status for product
     */
    private function getStockStatus($product, $requestedQuantity)
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
    private function calculateTotals()
    {
        // Get shipping cost
        $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
        $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
        $shippingCost = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;

        // Get discount (from coupon)
        $discount = 0;
        if (Session::has('couponApplied')) {
            $coupon = Session::get('couponApplied');
            $discount = $coupon['discount_amount'] ?? 0;
        }

        // Calculate grand total
        $this->totalPrice = $this->totalPrice + $shippingCost - $discount;
    }

    /**
     * Add item to cart
     */
    public function addToCart($productId, $quantity = 1, $attributes = [])
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
    public function updateCartItem($cartKey, $quantity)
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
    public function removeFromCart($cartKey)
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
    public function clearCart()
    {
        Session::forget('cart');
        return ['success' => true, 'message' => 'Cart cleared successfully'];
    }

    /**
     * Get cart count
     */
    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['qty'];
        }
        
        return $count;
    }
}