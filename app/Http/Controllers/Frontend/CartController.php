<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\ProductCoupon;

class CartController extends Controller
{
    public function index(){
        return view('frontend.product.cart');
    }

    /**
     * Store if save with IP Address in DB into ProductCarts table
     */
    public function stores(Request $request){
        /*
        if(Auth::check()){
            $cart = ProductCart::where('product_id', $request->product_id)
                            ->where('user_id', Auth::user()->id)
                            ->first();
        } else {
            $cart = ProductCart::where('product_id', $request->product_id)
                            ->where('ip_address', request()->ip())
                            ->first();
        }
        if(!empty($cart)){
            $cart->increment('qty');
        } else {
            $cart = new ProductCart();
            if(Auth::check()){
                $cart->user_id = Auth::user()->id;
            }
            $cart->ip_address = request()->ip();
            $cart->product_id = $request->product_id;
            //$cart->save();
             session()->put($cart);
        }
        return redirect()->back()->with('success', 'product has added to cart');
        */
        
       
    }

    /**
     * If cart save into session 
     * Products Cart BD table not work with this
     */

    public function store(Request $request){
        $product = Product::where('id', $request->product_id)->first();
        $id = $product->id;

        if(!$product){
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty then this the first product
        if(!$cart){
            $cart = [
                $id => [
                  'id' => $product->id,
                  'name' => $product->title,
                  'slug' => $product->slug,
                  'attribute' => $request['attribute'],
                  'qty' => !empty($request->quantity) ? $request->quantity : '1',
                  'price' => !empty($product->sale_price) ? $product->sale_price : $product->regular_price,
                  'featured_image' => $product->featured_image,
                ],
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }
       
        //if cart not empty then check if this product exist then increment quantity
        
        if(isset($cart[$id])){
            
            if(!empty($request->quantity) && $cart[$id]['attribute'] == $request['attribute'] ){
                $cart[$id]['qty'] = $request->quantity + $cart[$id]['qty'];
                $cart[$id]['attribute'] = $request['attribute'];
            } elseif(!empty($request->quantity) && $cart[$id]['attribute'] != $request['attribute'] ){
                $cart[] = [
                     'id' => $product->id,
                    'name' => $product->title,
                    'slug' => $product->slug,
                    'attribute' => $request['attribute'],
                    'qty' => !empty($request->quantity) ? $request->quantity : '1',
                    'price' => !empty($product->sale_price) ? $product->sale_price : $product->regular_price,
                    'featured_image' => $product->featured_image,
                ];
            } else{
                $cart[$id]['qty']++;
                $cart[$id]['attribute'] = $request['attribute'];
            } 
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
           
        }
        

        // if item not exist in cart then add to cart with quantity = 1
        
        $cart[$id] = [
             'id' => $product->id,
            'name' => $product->title,
            'slug' => $product->slug,
            'attribute' => $request['attribute'],
            'qty' => !empty($request->quantity) ? $request->quantity : '1',
            'price' => !empty($product->sale_price) ? $product->sale_price : $product->regular_price,
            'featured_image' => $product->featured_image,
        ];
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }


    //Update cart as A single Product
    public function update(Request $request)
    {
        if($request->id)
        {
            $product = Product::where('id', $request->id)->first();
            $cart = session()->get('cart');
            //$cart[$request->id]["quantity"] = $request->quantity;
            $cart[$request->id] = [
                 'id' => $product->id,
                'name' => $product->title,
                'slug' => $product->slug,
                'attribute' => $cart[$request->id]['attribute'],
                'qty' => $request->quantity,
                'price' => !empty($product->sale_price) ? $product->sale_price : $product->regular_price,
                'featured_image' => $product->featured_image,
            ];
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
            //return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }
    //Update Cart As a Multiple Product
    public function multipleUpdate(Request $request){
        $productID = $request->id;
        $pId = $request->pid;
        $productQuantity = $request->qty;
        foreach($productQuantity as $key => $quantity){
            $product = Product::where('id', $pId[$key])->first();
            $cart = session()->get('cart');
            $cart[$productID[$key]] = [
                 'id' => $product->id,
                'name' => $product->title,
                'slug' => $product->slug,
                'attribute' => $cart[$productID[$key]]['attribute'],
                'qty' => $quantity,
                'price' => !empty($product->sale_price) ? $product->sale_price : $product->regular_price,
                'featured_image' => $product->featured_image,
            ];
            session()->put('cart', $cart);
        };
        return redirect()->back()->with('success', 'Cart updated successfully');
    }

    //Remove Cart Item from cart
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('delete', 'Product removed successfully from cart');
        }
    }

    /**
     * Coupon Apply
     */

    public function couponApply(Request $request){
        $coupon = ProductCoupon::where('code', $request->coupon)->where('status', '1')->first();
        if(!empty($coupon)){
            $couponApplied = session()->get('coupon');
            $getSession = session('cart');
            $subTotalCartPrice = 0;
            foreach($getSession as $id => $details){
                $subTotalCartPrice += $details['price'] * $details['qty'];
            }

            if($coupon->type == 'fixed'){
                $amountAfterApplyCoupon =  $subTotalCartPrice - $coupon->amount;
                $couponAnountWithSign = \App\Helpers\Frontend\ProductView::priceSign($coupon->amount);
            }
            if($coupon->type == 'percentage_off'){
                $amountAfterApplyCoupon =  $subTotalCartPrice - ($subTotalCartPrice*$coupon->amount/100);
                $couponAnountWithSign = $coupon->amount.'%';
            }

            $couponApplied = [
              'coupon' => [
                    'subTotalCartPrice' =>  $subTotalCartPrice,
                    'couponCode' =>  $request->coupon,
                    'couponAmount' =>  $coupon->amount,
                    'couponAnountWithSign' => $couponAnountWithSign,
                    'amountAfterApplyCoupon' => $amountAfterApplyCoupon,
              ],
            ];
            session()->put('couponApplied', $couponApplied);
            return redirect()->back()->with('success', 'Coupon has been appied.');
        } else{
            $couponApplied = session()->get('couponApplied');
            unset($couponApplied['coupon']);
            session()->put('couponApplied', $couponApplied);
            return redirect()->back()->with('delete', 'Invalid Coupon.');
        }

    }

    public function couponApplyDestroy(request $request){
        $couponApplied = session()->get('couponApplied');
        unset($couponApplied['coupon']);
        session()->put('couponApplied', $couponApplied);
        return redirect()->back()->with('delete', 'Applied Coupon Removed.');
    }


}
