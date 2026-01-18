<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Session;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Services\PaymentMethodService;
use App\Helpers\Checkout\CheckoutHelper;
use App\Helpers\Cart\CartHelper;

class CheckoutController extends Controller
{
    /**
     * Adjust stock for a product safely and log the movement.
     */
    protected function adjustStock(int $productId, int $qty, string $direction, int $orderId, string $note = ''): void
    {
        // direction: 'out' = deduct, 'in' = add back
        $qty = max(0, $qty);
        if ($qty === 0) {
            return;
        }
        $inv = Inventory::where('product_id', $productId)->lockForUpdate()->first();
        if ($inv) {
            if ($direction === 'out') {
                $inv->current_stock = max(0, ((int)$inv->current_stock) - $qty);
            } else {
                $inv->current_stock = ((int)$inv->current_stock) + $qty;
            }
            $inv->save();

            // Log stock movement if model exists
            try {
                if (class_exists(StockMovement::class)) {
                    StockMovement::create([
                        'product_id'    => $productId,
                        'quantity'      => $qty,
                        'type'          => $direction === 'out' ? 'order_deduct' : 'order_restock',
                        'reference'     => 'order:'.$orderId,
                        'note'          => $note,
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('StockMovement log failed: '.$e->getMessage());
            }
        }
    }

    //Index Function
    public function index(){
        session()->put('url.intended',route('frontend_checkout_index'));
        
        if(config('checkout.require_login') && !Auth::check()){
            return redirect()->route('frontend_customer_login');
        }
        
        if(CartHelper::isEmpty()){
            return redirect()->route('frontend_cart_index');
        }
        
        // Validate cart items stock before checkout
        $cartValidation = CartHelper::validateCart();
        if (!$cartValidation['valid']) {
            return redirect()->route('frontend_cart_index')->with('error', 'Some items in your cart are no longer available: ' . implode(', ', $cartValidation['errors']));
        }
        
        $checkoutSummary = CheckoutHelper::getCheckoutSummary();
        $paymentMethods = PaymentMethodService::getEnabledMethods();
        
        return view('frontend.product.checkout', compact('checkoutSummary', 'paymentMethods'));
    }

    public function getSession($orderID){
        $totalAmounts = 0;
        $od = [];
        if(session('cart')){
            foreach((array)session('cart') as $id => $details){
                $od['user_id'] = Auth::user()->id;
                $od['order_id'] = $orderID;
                $od['product_id'] = $details['id'];
                $od['qty'] = $details['qty'];
                $od['price'] = $details['price']*$details['qty'];
                $totalAmounts += $details['price']*$details['qty']; 
            }
        }
        return $od;
    }

     public function getTotalAmount(){
        $totalAmounts = 0;
        if(session('cart')){
            foreach((array)session('cart') as $id => $details){
                $totalAmounts += $details['price']*$details['qty']; 
            }
        }
        return $totalAmounts;
    }

    /**
     * Calculate shipping cost
     */
    protected function calculateShippingCost(Request $request): float
    {
        // For now, return 0. You can implement shipping calculation logic here
        // Example: flat rate, weight-based, distance-based, etc.
        return 0.0;
    }

    /**
     * Calculate tax amount
     */
    protected function calculateTaxAmount(float $subtotal): float
    {
        // For now, return 0. You can implement tax calculation logic here
        // Example: percentage-based tax, fixed tax, etc.
        return 0.0;
    }

    /**
     * Calculate discount amount
     */
    protected function calculateDiscountAmount(): float
    {
        // Check for applied coupons or discounts
        $coupon = session('couponApplied');
        if ($coupon && isset($coupon['couponAnountWithSign'])) {
            return abs($coupon['couponAnountWithSign']); // Convert to positive amount
        }
        return 0.0;
    }

    public function checkout(Request $request){
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'thana' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required',
        ]);

        // Validate payment method
        if (!PaymentMethodService::validateMethod($request->payment_method)) {
            return redirect()->back()->with('error', 'Invalid payment method selected.');
        }

        // Validate cart again before checkout
        $cartValidation = CartHelper::validateCart();
        if (!$cartValidation['valid']) {
            return redirect()->back()->with('error', 'Some items in your cart are no longer available: ' . implode(', ', $cartValidation['errors']));
        }

        try {
            $result = DB::transaction(function() use ($request) {
                // Create order
                $order = $this->createOrder($request);
                
                // Create order details with snapshots
                $this->createOrderDetails($order);
                
                // Process payment
                $paymentResult = PaymentMethodService::processPayment($request, $order);
                
                return [
                    'order' => $order,
                    'payment' => $paymentResult
                ];
            });

            // Handle payment result
            if ($result['payment']['success']) {
                if (isset($result['payment']['redirect_url'])) {
                    return redirect($result['payment']['redirect_url']);
                } else {
                    return redirect()->route('frontend_checkout_success')
                        ->with('success', $result['payment']['message']);
                }
            } else {
                return redirect()->back()->with('error', $result['payment']['message']);
            }

        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Create order from request
     */
    protected function createOrder(Request $request): ProductOrder
    {
        $order = new ProductOrder();
        $order->order_code = 'ORD-' . time() . '-' . rand(1000, 9999);
        $order->user_id = Auth::check() ? Auth::user()->id : null;
        $order->customer_name = $request->name;
        $order->customer_phone = $request->phone;
        $order->customer_address = $request->address;
        $order->customer_thana = $request->thana;
        $order->customer_city = $request->city;
        $order->customer_postal_code = $request->postal_code;
        $order->customer_country = $request->country;
        // Calculate amounts properly
        $subtotal = $this->getTotalAmount(); // Products only
        $shippingCost = $this->calculateShippingCost($request); // Add shipping calculation
        $taxAmount = $this->calculateTaxAmount($subtotal);
        $discountAmount = $this->calculateDiscountAmount();
        $finalAmount = $subtotal + $shippingCost + $taxAmount - $discountAmount;
        
        $order->total_amount = $subtotal; // Products only (subtotal)
        $order->shipping_cost = $shippingCost;
        $order->tax_amount = $taxAmount;
        $order->discount_amount = $discountAmount;
        $order->final_amount = $finalAmount; // Total including shipping, tax, discounts
        $order->currency = 'BDT';
        $order->order_status = 'pending';
        $order->payment_status = 'Pending';
        $order->delivery_status = 'Pending';
        $order->tran_id = $request->payment_method === 'ssl' ? uniqid() : '';
        $order->save();

        return $order;
    }

    /**
     * Create order details with product snapshots
     */
    protected function createOrderDetails(ProductOrder $order): void
    {
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $product = \App\Models\Product::with(['brand', 'category'])->find($details['id']);
                
                $od = new ProductOrderDetails();
                $od->user_id = $order->user_id;
                $od->order_id = $order->id;
                $od->product_id = $details['id'];
                $od->attribute = $details['attribute'] ?? null;
                $od->qty = $details['qty'];
                $od->price = $details['price'] * $details['qty'];
                
                // Snapshot product details for data integrity
                if ($product) {
                    $od->product_title = $product->title;
                    $od->product_code = $product->code;
                    $od->featured_image = method_exists($product,'getFeaturedImageUrl') ? $product->getFeaturedImageUrl() : null;
                    $od->unit_price = isset($details['price']) ? (float)$details['price'] : (($product->sale_price ?? $product->regular_price)/100);
                    $od->currency = 'BDT';
                    $od->line_total = (float)$od->unit_price * (int)$od->qty;
                    $od->brand_name = optional($product->brand)->name;
                    $od->category_name = optional($product->category)->name;
                }
                $od->save();
            }
        }
    }

    //Return after from SSL Commerz if Payment Success
    public function checkoutSuccess(Request $request){
        session()->regenerate();
        
        try {
            // Log the incoming request for debugging
            \Log::info('Checkout Success Handler Called', [
                'all_params' => $request->all(),
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl()
            ]);
            
            $result = PaymentMethodService::handlePaymentSuccess($request, 'ssl');
            
            \Log::info('PaymentMethodService Result', ['result' => $result]);
            
            if ($result['success']) {
                // For guest users, redirect to a success page instead of dashboard
                if (!Auth::check()) {
                    return redirect()->route('frontend_index')
                        ->with('success', 'Payment successful! Your order has been placed. You will receive an email confirmation shortly.');
                }
                
                return redirect()->route('customer_dashboard')
                    ->with('success', 'Payment successful! Your order has been placed.');
            } else {
                // For sandbox mode, if payment status is already Paid, treat as success
                $tran_id = $request->input('tran_id');
                if ($tran_id) {
                    $order = \App\Models\ProductOrder::where('tran_id', $tran_id)->first();
                    if ($order && $order->payment_status === 'Paid') {
                        \Log::info('Order already paid, treating as success', ['order_id' => $order->id]);
                        
                        if (!Auth::check()) {
                            return redirect()->route('frontend_index')
                                ->with('success', 'Payment successful! Your order has been placed.');
                        }
                        
                        return redirect()->route('customer_dashboard')
                            ->with('success', 'Payment successful! Your order has been placed.');
                    }
                }
                
                // For guest users, redirect to homepage with error
                if (!Auth::check()) {
                    return redirect()->route('frontend_index')
                        ->with('error', 'Payment failed: ' . $result['message']);
                }
                
                return redirect()->route('customer_dashboard')
                    ->with('error', 'Payment failed: ' . $result['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Payment Success Handler Error: ' . $e->getMessage());
            
            // For guest users, redirect to homepage with error
            if (!Auth::check()) {
                return redirect()->route('frontend_index')
                    ->with('error', 'Payment processing error. Please contact support.');
            }
            
            return redirect()->route('customer_dashboard')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment Failed
    public function checkoutFailed(Request $request){
        session()->regenerate();
        
        try {
            $result = PaymentMethodService::handlePaymentFailure($request, 'ssl');
            
            // For guest users, redirect to homepage
            if (!Auth::check()) {
                return redirect()->route('frontend_index')
                    ->with('error', 'Payment failed. Please try again.');
            }
            
            return redirect()->route('customer_dashboard')
                ->with('error', 'Payment failed. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Payment Failed Handler Error: ' . $e->getMessage());
            
            // For guest users, redirect to homepage
            if (!Auth::check()) {
                return redirect()->route('frontend_index')
                    ->with('error', 'Payment processing error. Please contact support.');
            }
            
            return redirect()->route('customer_dashboard')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment Cancel
    public function checkoutCancel(Request $request){
        session()->regenerate();
        
        try {
            $tran_id = $request->input('tran_id');
            $order = ProductOrder::where('tran_id', $tran_id)->first();
            
            if ($order) {
                $order->payment_status = 'Cancelled';
                $order->order_status = 'cancelled';
                $order->save();
            }

            // For guest users, redirect to homepage
            if (!Auth::check()) {
                return redirect()->route('frontend_index')
                    ->with('warning', 'Payment was cancelled. You can try again.');
            }

            return redirect()->route('customer_dashboard')
                ->with('warning', 'Payment was cancelled. You can try again.');
        } catch (\Exception $e) {
            \Log::error('Payment Cancel Handler Error: ' . $e->getMessage());
            
            // For guest users, redirect to homepage
            if (!Auth::check()) {
                return redirect()->route('frontend_index')
                    ->with('error', 'Payment processing error. Please contact support.');
            }
            
            return redirect()->route('customer_dashboard')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment IPN
    public function ipn(Request $request){
        try {
            $sslc = new \App\Library\SslCommerz\SslCommerzNotification();
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');

            $order = ProductOrder::where('tran_id', $tran_id)->first();
            if (!$order) {
                return response('Order not found', 404);
            }

            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            
            if ($validation) {
                $order->payment_status = 'Paid';
                $order->save();
                return response('Payment validated', 200);
            } else {
                return response('Payment validation failed', 400);
            }
        } catch (\Exception $e) {
            \Log::error('IPN Handler Error: ' . $e->getMessage());
            return response('IPN processing error', 500);
        }
    }
}