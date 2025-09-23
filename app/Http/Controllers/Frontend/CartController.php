<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Helpers\Cart\CartHelper;

class CartController extends Controller
{
    public function index(){
        $cart = CartHelper::getCartSummary();
        return view('frontend.product.cart', compact('cart'));
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
        $product = Product::with('inventory')->where('id', $request->product_id)->first();
        
        if(!$product){
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }
            abort(404);
        }

        $id = $product->id;

        // Check stock availability
        $requestedQuantity = !empty($request->quantity) ? (int)$request->quantity : 1;
        if ($product->isOutOfStock()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This product is currently out of stock.']);
            }
            return redirect()->back()->with('error', 'This product is currently out of stock.');
        }
        
        if ($product->inventory && $product->inventory->current_stock < $requestedQuantity) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Only ' . $product->inventory->current_stock . ' items available in stock.']);
            }
            return redirect()->back()->with('error', 'Only ' . $product->inventory->current_stock . ' items available in stock.');
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
                  'image_url' => $product->getFeaturedImageUrl(),
                ],
            ];
            session()->put('cart', $cart);
            
            // Track cart add
            $product->trackCartAdd();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
            }
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
                  'image_url' => $product->getFeaturedImageUrl(),
                ];
            } else{
                $cart[$id]['qty']++;
                $cart[$id]['attribute'] = $request['attribute'];
            } 
            session()->put('cart', $cart);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
            }
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
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
        }
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }


    //Update cart as A single Product
    public function update(Request $request)
    {
        if($request->id)
        {
            $product = Product::with('inventory')->where('id', $request->id)->first();
            
            if (!$product) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Product not found']);
                }
                return redirect()->back()->with('error', 'Product not found');
            }

            // Check stock availability
            if ($product->isOutOfStock()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'This product is currently out of stock.']);
                }
                return redirect()->back()->with('error', 'This product is currently out of stock.');
            }
            
            if ($product->inventory && $product->inventory->current_stock < $request->quantity) {
                $msg = 'Only ' . $product->inventory->current_stock . ' items available in stock.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }

            $cart = session()->get('cart');
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
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Cart updated successfully']);
            }
            return redirect()->route('frontend_cart_index')->with('success', 'Cart updated successfully');
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }
        return redirect()->route('frontend_cart_index');
    }
    //Update Cart As a Multiple Product
    public function multipleUpdate(Request $request){
        $productID = $request->id;
        $pId = $request->pid;
        $productQuantity = $request->qty;
        $errors = [];
        
        foreach($productQuantity as $key => $quantity){
            $product = Product::with('inventory')->where('id', $pId[$key])->first();
            
            if (!$product) {
                $errors[] = "Product not found";
                continue;
            }

            // Check stock availability
            if ($product->isOutOfStock()) {
                $errors[] = "{$product->title} is currently out of stock.";
                continue;
            }
            
            if ($product->inventory && $product->inventory->current_stock < $quantity) {
                $errors[] = "Only {$product->inventory->current_stock} items available for {$product->title}.";
                $quantity = $product->inventory->current_stock; // Limit to available stock
            }

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
        
        if (!empty($errors)) {
            return redirect()->back()->with('error', implode(' ', $errors));
        }
        
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
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product removed successfully from cart']);
            }
            return redirect()->route('frontend_cart_index')->with('delete', 'Product removed successfully from cart');
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        return redirect()->route('frontend_cart_index');
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
