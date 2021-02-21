<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Session;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetails;
use App\Library\SslCommerz\SslCommerzNotification;
class CheckoutController extends Controller
{
    //Index Function
    public function index(){
        //url()->previous()
        session()->put('url.intended',route('frontend_checkout_index'));
        if(Auth::check() && session('cart') != null){
            return view('frontend.product.checkout');
        }elseif(Auth::check() && session('cart') == null){
            return redirect()->route('frontend_cart_index');
        }else{
            return redirect()->route('frontend_customer_login');
        }
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

        //order Save
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
        $od->save();
        $orderID = $od->id;
        
        //Order Details
        //$o = new ProductOrderDetails();
        //$o->insert($this->getSession($orderID));
        
        if(session('cart')){
            foreach((array)session('cart') as $id => $details){
                $o = new ProductOrderDetails();
                $o->user_id = Auth::user()->id;
                $o->order_id = $orderID;
                $o->product_id = $details['id'];
                $o->attribute = $details['attribute'];
                $o->qty = $details['qty'];
                $o->price = $details['price']*$details['qty'];
                $o->save();
            }
        }
        if($request->payment_method == 'ssl'){
            //sslComerz
            $sslc = new SslCommerzNotification();
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($order, 'hosted');

            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }
        } else {
            Session::forget('cart');
            Session::forget('couponApplied');
            return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Your order has been completed successfully');
        }
        
    }

    //Return after from SSlCommerze if Payment Success
    public function checkoutSuccess(Request $request){
        //echo "Transaction is Successful";
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = ProductOrder::where('tran_id', $tran_id)
            ->select('tran_id', 'payment_status', 'currency', 'total_amount')->first();
        
        if ($order_detials->payment_status == 'Pending') {

            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            
            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                
                $update_product =  ProductOrder::where('tran_id', $tran_id)
                    ->update(['payment_status' => 'Processing']);

                return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Transaction is successfully Completed');
                //echo "<br >Transaction is successfully Completed";
                
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = ProductOrder::where('tran_id', $tran_id)
                    ->update(['payment_status' => 'Failed']);
                echo "validation Fail";
            }
        } else if ($order_detials->payment_status == 'Processing' || $order_detials->payment_status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Transaction is successfully Completed');
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }
    }

    //Return after from SSlCommerze if Payment Failed
    public function checkoutFailed(Request $request){
        $tran_id = $request->input('tran_id');

        $order_detials = ProductOrder::where('tran_id', $tran_id)
            ->select('tran_id', 'payment_status', 'currency', 'total_amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = ProductOrder::where('tran_id', $tran_id)
                ->update(['payment_status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    //Return after from SSlCommerze if Payment Cancel
    public function checkoutCancel(Request $request){
        $tran_id = $request->input('tran_id');

        $order_detials = ProductOrder::where('tran_id', $tran_id)->select('tran_id', 'payment_status', 'currency', 'total_amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = ProductOrder::where('tran_id', $tran_id)->update(['payment_status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Transaction is successfully Completed');
        } else {
            echo "Transaction is Invalid";
        }
    }

    //Return after from SSlCommerze if Payment ipn
    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = ProductOrder::where('tran_id', $tran_id)
                ->select('tran_id', 'payment_status', 'currency', 'total_amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = ProductOrder::where('tran_id', $tran_id)
                        ->update(['payment_status' => 'Processing']);

                    return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Transaction is successfully Completed');
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = ProductOrder::where('tran_id', $tran_id)
                        ->update(['payment_status' => 'Failed']);

                    echo "validation Fail";
                }

            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                return redirect()->route('frontend_customer_dashboard', '#orders')->with('success', 'Transaction is successfully Completed');
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
    //End
}
