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
use App\Library\SslCommerz\SslCommerzNotification;
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
        
        if(!Auth::check()){
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
        $paymentMethods = CheckoutHelper::getPaymentMethods();
        
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

    //Hand cash 
    /*
    //CheckOut Function
    public function checkout(Request $request){
        $order = new ProductOrder();
        $order->customer_name = Auth::user()->name;
        $order->phone = 'o9';
        $order->user_shipment_id = $request->shipping_id;
        $order->total_amount = '98';
        $order->note = $request->note;
        $order->payment_status = '';
        $order->payment_type = '98';
        $order->delivery_status = '98';
        $order->save();
        $orderID = $order->id;
        //Order Details
    }
    */

    public function checkout(Request $request){


        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'thana' => 'required',
            'city' => 'required',
            'payment_method' => 'required',
        ]);

        // Validate cart items stock before processing checkout
        $cartValidation = CartHelper::validateCart();
        if (!$cartValidation['valid']) {
            return redirect()->route('frontend_cart_index')->with('error', 'Some items in your cart are no longer available: ' . implode(', ', $cartValidation['errors']));
        }

        if($request->payment_method == 'cash'){
            $paymentMethod = 'Cash On Delivery';
        }
        if($request->payment_method == 'ssl'){
            $paymentMethod = 'Sslcommerz Paymeny Gateway';
        }

       
        //Coupon 
        if(!empty(session('couponApplied'))){
            $couponEle = session('couponApplied')['coupon'];
            //$couponEle['amountAfterApplyCoupon'];
        }
        $couponCode = !empty($couponEle) ? $couponEle['couponCode'] : '';
        $couponAmount = !empty($couponEle) ?  $couponEle['couponAnountWithSign'] : '';
        $subTotal = !empty($couponEle) ? $couponEle['amountAfterApplyCoupon'] : $this->getTotalAmount();
        //Shipping Cost
       
        $shippingCost = $request->shippingCost;

        //End Shipping Cost
        $grandTotal =   $subTotal + $shippingCost; 
        //End Coupon & Shippping


        $order = [];
        $order['cus_name'] = $request->name;
        $order['cus_email'] = 'customer@mail.com';
        $order['cus_add1'] = $request->address;
        $order['cus_add2'] = 'Customer Address';
        $order['cus_city'] = $request->city;
        $order['cus_state'] = $request->thana;
        $order['cus_postcode'] = $request->postal_code;
        $order['cus_country'] = $request->country;
        $order['cus_phone'] = $request->phone;
        $order['cus_fax'] = "";
            # SHIPMENT INFORMATION
        $order['ship_name'] = "Store Test";
        $order['ship_add1'] = "Dhaka";
        $order['ship_add2'] = "Dhaka";
        $order['ship_city'] = "Dhaka";
        $order['ship_state'] = "Dhaka";
        $order['ship_postcode'] = "1000";
        $order['ship_phone'] = "";
        $order['ship_country'] = "Bangladesh";

        $order['shipping_method'] = "NO";
        $order['product_name'] = "Computer";
        $order['product_category'] = "Goods";
        $order['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $order['value_a'] = "ref001";
        $order['value_b'] = "ref002";
        $order['value_c'] = "ref003";
        $order['value_d'] = "ref004";
        
        $order['phone'] = '89';
        $order['user_shipment_id'] = '';
        $order['total_amount'] = $grandTotal;
        $order['note'] = $request->note;
        $order['payment_status'] = '';
        $order['payment_type'] = '98';
        $order['delivery_status'] = '98';
        $order['currency'] = 'BDT';
        $order['tran_id'] = $request->payment_method == 'ssl' ? uniqid() : '';
        $order['shipping_method'] = "NO";
        $order['product_name'] = "Computer";
        $order['product_category'] = "Goods";
        $order['product_profile'] = "physical-goods";

        // Atomic order create + items + soft-reserve (if online payment)
        DB::transaction(function () use (&$od, $order, $shippingCost, $couponCode, $couponAmount, $subTotal) {
            $od = new ProductOrder();
            $today = mktime(0,date("m"),date("d"),date("Y"),date("i"),date("his"));
            $od->order_code = '#OD-'.date("Ymd", $today);
            $od->user_id = Auth::user()->id;
            $od->customer_name = $order['cus_name'];
            $od->customer_phone = $order['cus_phone'];
            $od->customer_address = $order['cus_add1'];
            $od->customer_thana = $order['cus_state'];
            $od->customer_postal_code = $order['cus_postcode'];
            $od->customer_city = $order['cus_city'];
            $od->customer_country = $order['cus_country'];
            $od->total_amount = $order['total_amount'];
            $od->shipping_cost = $shippingCost;
            $od->use_coupone = $couponCode;
            $od->coupone_discount = $couponAmount;
            $od->currency = $order['currency'];
            $od->note = $order['note'];
            $od->tran_id = $order['tran_id'];
            $od->payment_type = $paymentMethod;
            $od->payment_status = 'Pending';
            $od->delivery_status = 'Pending';
            $od->order_status = 'pending';
            $od->final_amount = $subTotal + $shippingCost;
            $od->tax_amount = 0;
            $od->discount_amount = $couponAmount ?: 0;
            $od->save();

            $orderID = $od->id;

            if(session('cart')){
                foreach((array)session('cart') as $id => $details){
                    $o = new ProductOrderDetails();
                    $o->user_id = Auth::user()->id;
                    $o->order_id = $orderID;
                    $o->product_id = $details['id'];
                    $o->attribute = (!empty($details['attribute']) && is_array($details['attribute'])) ? $details['attribute'] : [];
                    $o->qty = $details['qty'];
                    $o->price = $details['price']*$details['qty'];
                    $o->save();

                    // Soft-reserve if online payment
                    if ($order['tran_id']) {
                        $inv = Inventory::where('product_id', $details['id'])->lockForUpdate()->first();
                        if ($inv) {
                            $inv->reserved_stock = (int)$inv->reserved_stock + (int)$details['qty'];
                            $inv->save();
                        }
                    }
                }
            }
        });
        if($request->payment_method == 'ssl'){
            // SSL Commerz Payment
            try {
            $sslc = new SslCommerzNotification();
                
                // Prepare payment data
                $paymentData = [
                    'total_amount' => $od->total_amount,
                    'currency' => 'BDT',
                    'tran_id' => $od->tran_id,
                    'product_category' => 'E-commerce',
                    'product_name' => 'Order #' . $od->id,
                    'product_profile' => 'physical-goods',
                    'cus_name' => $od->customer_name,
                    'cus_email' => Auth::user()->email ?? 'customer@mail.com',
                    'cus_add1' => $od->customer_address,
                    'cus_add2' => '',
                    'cus_city' => $od->customer_city ?? 'Dhaka',
                    'cus_state' => $od->customer_thana ?? 'Dhaka',
                    'cus_postcode' => $od->customer_postal_code ?? '1000',
                    'cus_country' => 'Bangladesh',
                    'cus_phone' => $od->customer_phone,
                    'cus_fax' => '',
                    'ship_name' => $od->customer_name,
                    'ship_add1' => $od->customer_address,
                    'ship_add2' => '',
                    'ship_city' => $od->customer_city ?? 'Dhaka',
                    'ship_state' => $od->customer_thana ?? 'Dhaka',
                    'ship_postcode' => $od->customer_postal_code ?? '1000',
                    'ship_country' => 'Bangladesh',
                    'shipping_method' => 'NO',
                    'value_a' => $od->id,
                    'value_b' => 'order',
                    'value_c' => 'checkout',
                    'value_d' => 'laracommerce'
                ];
                
                // Initiate payment
                $payment_options = $sslc->makePayment($paymentData, 'hosted');

                // Handle both possible return types from the SDK
                if ($payment_options instanceof \Illuminate\Http\RedirectResponse) {
                    return $payment_options;
                }

                if (is_array($payment_options)) {
                    if (!empty($payment_options['GatewayPageURL'])) {
                        return redirect()->away($payment_options['GatewayPageURL']);
                    }
                    \Log::error('SSLCommerz: Missing GatewayPageURL in response', ['response' => $payment_options]);
                    return redirect()->back()->with('error', 'Payment gateway did not return a redirect URL.');
                }

                \Log::error('SSLCommerz: Unexpected response type from makePayment', ['type' => gettype($payment_options)]);
                return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
                
            } catch (\Exception $e) {
                // Log error and redirect back
                \Log::error('SSL Commerz Payment Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Payment system error. Please try again later.');
            }
        } else {
            // Cash on Delivery or other payment methods
            DB::transaction(function () use ($orderID) {
                // Deduct stock immediately for COD
                if(session('cart')){
                    foreach((array)session('cart') as $id => $details){
                        $this->adjustStock((int)$details['id'], (int)$details['qty'], 'out', (int)$orderID, 'COD placement');
                    }
                }
            });
            Session::forget('cart');
            Session::forget('couponApplied');
            return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Your order has been completed successfully');
        }
        
    }

    //Return after from SSL Commerz if Payment Success
    public function checkoutSuccess(Request $request){
        $request->session()->regenerate();
        try {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

            if (!$tran_id || !$amount) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Invalid payment response.');
            }

        $sslc = new SslCommerzNotification();

            // Check order status in order table against the transaction id
            $order_details = ProductOrder::where('tran_id', $tran_id)
                ->select('id', 'tran_id', 'payment_status', 'currency', 'total_amount', 'customer_name')
                ->first();
            
            if (!$order_details) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Order not found.');
            }
            
            if ($order_details->payment_status == 'Pending') {
                // Validate the transaction with SSL Commerz
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            
            if ($validation == TRUE) {
                    // Payment successful - update order status
                    ProductOrder::where('tran_id', $tran_id)
                        ->update([
                            'payment_status' => 'Paid',
                            'updated_at' => now(),
                            'delivery_status' => 'pending',
                            'order_status' => 'processing',
                            'currency' => $currency,
                            'total_amount' => $amount,
                        ]);

                    // Deduct stock now that payment is successful
                    DB::transaction(function () use ($tran_id) {
                        try{
                            $order = ProductOrder::where('tran_id', $tran_id)->first();
                            if($order){
                                $items = ProductOrderDetails::where('order_id', $order->id)->get();
                                foreach($items as $item){
                                    // convert reservation into deduction
                                    $inv = Inventory::where('product_id', $item->product_id)->lockForUpdate()->first();
                                    if ($inv) {
                                        $inv->reserved_stock = max(0, (int)$inv->reserved_stock - (int)$item->qty);
                                        $inv->save();
                                    }
                                    $this->adjustStock((int)$item->product_id, (int)$item->qty, 'out', (int)$order->id, 'SSL payment success');
                                }
                            }
                        }catch(\Throwable $e){
                            \Log::warning('Stock adjust (SSL success) failed: '.$e->getMessage());
                        }
                    });
                    // Clear cart and coupon session
                    Session::forget('cart');
                    Session::forget('couponApplied');

                    // Send success notification (you can add email/SMS here)
                    \Log::info('Payment successful for order: ' . $order_details->id);

                    return redirect()->route('frontend_customer_dashboard', '#orders')
                        ->with('success', 'Payment successful! Your order has been confirmed.');
                    
                } else {
                    // Payment validation failed
                    ProductOrder::where('tran_id', $tran_id)
                        ->update([
                            'payment_status' => 'Failed',
                            'updated_at' => now()
                        ]);

                    \Log::warning('Payment validation failed for order: ' . $order_details->id);

                    return redirect()->route('frontend_customer_dashboard', '#orders')
                        ->with('error', 'Payment validation failed. Please contact support.');
                }
            } else if (in_array($order_details->payment_status, ['Processing', 'Complete'])) {
                // Order already processed
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('success', 'Payment already processed successfully.');
            } else {
                // Invalid transaction status
                \Log::error('Invalid payment status for order: ' . $order_details->id . ' Status: ' . $order_details->payment_status);
                
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('error', 'Invalid payment status. Please contact support.');
            }
            
        } catch (\Exception $e) {
            \Log::error('SSL Commerz Success Handler Error: ' . $e->getMessage());
            
            return redirect()->route('frontend_customer_dashboard', '#orders')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment Failed
    public function checkoutFailed(Request $request){
        $request->session()->regenerate();
        try {
        $tran_id = $request->input('tran_id');

            if (!$tran_id) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Invalid payment response.');
            }

            $order_details = ProductOrder::where('tran_id', $tran_id)
                ->select('id', 'tran_id', 'payment_status', 'currency', 'total_amount')
                ->first();

            if (!$order_details) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Order not found.');
            }

            if ($order_details->payment_status == 'Pending') {
                // Update order status to failed
                ProductOrder::where('tran_id', $tran_id)
                    ->update([
                        'payment_status' => 'Failed',
                        'updated_at' => now()
                    ]);

                \Log::info('Payment failed for order: ' . $order_details->id);

                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('error', 'Payment failed. Please try again or contact support.');
                    
            } else if (in_array($order_details->payment_status, ['Processing', 'Complete', 'Paid'])) {
                // If for any reason stock was already deducted, add it back
                try{
                    $order = ProductOrder::where('tran_id', $tran_id)->first();
                    if($order){
                        $items = ProductOrderDetails::where('order_id', $order->id)->get();
                        foreach($items as $item){
                            $inv = \App\Models\Inventory::where('product_id', $item->product_id)->lockForUpdate()->first();
                            if($inv){
                                $inv->current_stock = ((int)$inv->current_stock) + ((int)$item->qty);
                                $inv->save();
                            }
                        }
                    }
                }catch(\Exception $e){
                    \Log::warning('Stock readjust (fail) failed: '.$e->getMessage());
                }
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('success', 'Payment already processed successfully.');
        } else {
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('error', 'Invalid payment status. Please contact support.');
            }
            
        } catch (\Exception $e) {
            \Log::error('SSL Commerz Failed Handler Error: ' . $e->getMessage());
            
            return redirect()->route('frontend_customer_dashboard', '#orders')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment Cancel
    public function checkoutCancel(Request $request){
        $request->session()->regenerate();
        try {
        $tran_id = $request->input('tran_id');

            if (!$tran_id) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Invalid payment response.');
            }

            $order_details = ProductOrder::where('tran_id', $tran_id)
                ->select('id', 'tran_id', 'payment_status', 'currency', 'total_amount')
                ->first();

            if (!$order_details) {
                return redirect()->route('frontend_customer_dashboard')->with('error', 'Order not found.');
            }

            if ($order_details->payment_status == 'Pending') {
                // Update order status to canceled
                ProductOrder::where('tran_id', $tran_id)
                    ->update([
                        'payment_status' => 'Canceled',
                        'updated_at' => now()
                    ]);

                \Log::info('Payment canceled for order: ' . $order_details->id);

                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('warning', 'Payment was canceled. You can try again later.');
                    
            } else if (in_array($order_details->payment_status, ['Processing', 'Complete', 'Paid'])) {
                // Readjust stock on cancellation after a successful/processing payment
                DB::transaction(function () use ($tran_id) {
                    try{
                        $order = ProductOrder::where('tran_id', $tran_id)->first();
                        if($order){
                            $items = ProductOrderDetails::where('order_id', $order->id)->get();
                            foreach($items as $item){
                                $this->adjustStock((int)$item->product_id, (int)$item->qty, 'in', (int)$order->id, 'Order cancelled');
                            }
                        }
                    }catch(\Throwable $e){
                        \Log::warning('Stock readjust (cancel) failed: '.$e->getMessage());
                    }
                });
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('success', 'Payment already processed successfully.');
        } else {
                return redirect()->route('frontend_customer_dashboard', '#orders')
                    ->with('error', 'Invalid payment status. Please contact support.');
            }
            
        } catch (\Exception $e) {
            \Log::error('SSL Commerz Cancel Handler Error: ' . $e->getMessage());
            
            return redirect()->route('frontend_customer_dashboard', '#orders')
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    //Return after from SSL Commerz if Payment IPN
    public function ipn(Request $request)
    {
        $request->session()->regenerate();
        try {
            // Received all the payment information from the gateway
            if (!$request->input('tran_id')) {
                \Log::error('IPN: No transaction ID provided');
                return response('Invalid Data', 400);
            }

            $tran_id = $request->input('tran_id');

            // Check order status in order table against the transaction id
            $order_details = ProductOrder::where('tran_id', $tran_id)
                ->select('id', 'tran_id', 'payment_status', 'currency', 'total_amount')
                ->first();

            if (!$order_details) {
                \Log::error('IPN: Order not found for transaction ID: ' . $tran_id);
                return response('Order not found', 404);
            }

            if ($order_details->payment_status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->total_amount, $order_details->currency);
                
                if ($validation == TRUE) {
                    // IPN worked - update order status
                    ProductOrder::where('tran_id', $tran_id)
                        ->update([
                            'payment_status' => 'Processing',
                            'updated_at' => now()
                        ]);

                    \Log::info('IPN: Payment successful for order: ' . $order_details->id);
                    
                    // You can add email/SMS notification here
                    return response('Transaction is successfully Completed', 200);
                    
                } else {
                    // IPN worked, but transaction validation failed
                    ProductOrder::where('tran_id', $tran_id)
                        ->update([
                            'payment_status' => 'Failed',
                            'updated_at' => now()
                        ]);

                    \Log::warning('IPN: Payment validation failed for order: ' . $order_details->id);
                    return response('Transaction validation failed', 400);
                }
                
            } else if (in_array($order_details->payment_status, ['Processing', 'Complete'])) {
                // Order already processed
                \Log::info('IPN: Order already processed: ' . $order_details->id);
                return response('Transaction is already successfully Completed', 200);
                
            } else {
                // Invalid transaction status
                \Log::error('IPN: Invalid payment status for order: ' . $order_details->id . ' Status: ' . $order_details->payment_status);
                return response('Invalid Transaction', 400);
            }
            
        } catch (\Exception $e) {
            \Log::error('IPN Handler Error: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }
    //End
}

