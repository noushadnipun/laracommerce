@extends('frontend.layouts.master')

@section('page-content')

    <!--Checkout page section-->
    <div class="Checkout_section mt-60">
       <div class="container">
            <div class="row">
               <div class="col-12">
{{-- 
                    <div class="user-actions">
                        <h3> 
                            <i class="fa fa-file-o" aria-hidden="true"></i>
                            Returning customer?
                            <a class="Returning" href="#" data-toggle="collapse" data-target="#checkout_coupon" aria-expanded="true">Click here to enter your code</a>     

                        </h3>
                         <div id="checkout_coupon" class="collapse" data-parent="#accordion">
                            <div class="checkout_info">
                                <form action="#">
                                    <input placeholder="Coupon code" type="text">
                                    <button type="submit">Apply coupon</button>
                                </form>
                            </div>
                        </div>    
                    </div>     --}}
               </div>
            </div>
            <div class="checkout_form">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="account_login_form">
                            <form action="{{route('frontend_customer_address_update')}}" method="post">
                                <h3>Billing Details</h3>
                                @csrf

                                @php
                                    $address = \App\Models\UserAddressBook::where('user_id', Auth::user()->id)->first();
                                @endphp

                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{!empty($address) ? $address->name : ''}}" required>

                                <label>Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" value="{{!empty($address) ? $address->address : ''}}" required>

                                <label>Thana <span class="text-danger">*</span></label>
                                <input type="text" name="thana" value="{{!empty($address) ? $address->thana : ''}}" required>

                                <label>Postal Code <span class="text-danger">*</span></label>
                                <input type="text" name="postal_code" value="{{!empty($address) ? $address->postal_code : ''}}" required>

                                <label>City <span class="text-danger">*</span></label>
                                <input type="text" name="city" value="{{!empty($address) ? $address->city : ''}}" required>

                                <label>Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" value="{{!empty($address) ? $address->country : 'Bangladesh'}}" required>

                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{!empty($address) ? $address->phone : ''}}" required>

                                <br>
                                <div class="save_button primary_btn default_button">
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <form action="{{route('frontend_checkout_done')}}" method="POST">  
                            @csrf  
                            <h3>Your order</h3> 
                            <div class="order_table table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $totalAmount = 0;?>
                                        @if(session('cart'))
                                        @foreach((array)session('cart') as $id => $details)
                                        <tr>
                                            <td> 
                                                {{ $details['name'] }} 
                                                <strong> × {{ $details['qty'] }} </strong>
                                            </td>
                                            <td>
                                                <?php 
                                                    $totalAmount += $details['price'] * $details['qty'];
                                                    echo App\Helpers\Frontend\ProductView::priceSign($details['price'] * $details['qty']);
                                                ?>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Cart Subtotal</th>
                                            <td>{{App\Helpers\Frontend\ProductView::priceSign($totalAmount)}}</td>
                                        </tr>
                                        <!-- Coupon -->
                                        @if(!empty(session('couponApplied')))
                                        @php $couponEle = session('couponApplied')['coupon'] @endphp
                                        <tr>
                                            <td>Coupon <span class="badge badge-success">{{$couponEle['couponCode']}}</span></td>
                                            <td>- {{ $couponEle['couponAnountWithSign'] }}</td>
                                        </tr>

                                        <tr>
                                            <td>Subtotal with discount</td>
                                            <td>{{ App\Helpers\Frontend\ProductView::priceSign($couponEle['amountAfterApplyCoupon']) }}</td>
                                        </tr>
                                        @endif
                                        <!-- End Coupon -->

                                        @php 
                                            $subTotal = !empty($couponEle) ? $couponEle['amountAfterApplyCoupon'] : $totalAmount;
                                            //Shipping Cost
                                            $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
                                            $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
                                            $shippingCost = $shippingType['meta_value'] == 'flat_rate' ? $shippingRate['meta_value'] : '0';

                                            //End Shipping Cost
                                            $grandTotal =   $subTotal + $shippingCost; 
                                        @endphp
                                

                                        <tr>
                                            <th>Shipping</th>
                                            <td><strong>{{$shippingCost}}</strong></td>
                                        </tr>
                                        <tr class="order_total">
                                            <th>Order Total</th>
                                            <td><strong>{{ App\Helpers\Frontend\ProductView::priceSign($grandTotal) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>     
                            </div>
                            <div class="payment_method">
                                <h3>Select Payment Method</h3> 
                               <div class="panel-default">
                                    <input id="payment" name="payment_method" type="radio" data-target="createp_account" value="cash"/>
                                    <label for="payment" data-toggle="collapse" data-target="#method" aria-controls="method">Cash On Delivery</label>

                                    <div id="method" class="collapse one" data-parent="#accordion">
                                        <div class="card-body1">
                                           <p>Please send your Name, address, phone, State / County,  Postcode. We'll contact you.</p>
                                        </div>
                                    </div>
                                </div> 
                               <div class="panel-default">
                                    <input id="payment_defult" name="payment_method" type="radio" data-target="createp_account" value="ssl" />
                                    <label for="payment_defult" data-toggle="collapse" data-target="#collapsedefult" aria-controls="collapsedefult">Sslcommerz Paymeny Gateway <img src="{{asset('assets/img/icon/sslc.png')}}" alt=""></label>

                                    <div id="collapsedefult" class="collapse one" data-parent="#accordion">
                                        <div class="card-body1">
                                           <p>Pay via Bkash, Rocket, Nagad & Bank card.</p> 
                                        </div>
                                    </div>
                                </div>

                                <div class="order_button">
                                    <input type="hidden" name="name" value="{{!empty($address) ? $address->name : ''}}">
                                    <input type="hidden" name="address" value="{{!empty($address) ? $address->address : ''}}">
                                    <input type="hidden" name="thana" value="{{!empty($address) ? $address->thana : ''}}">
                                    <input type="hidden" name="postal_code" value="{{!empty($address) ? $address->postal_code : ''}}">
                                    <input type="hidden" name="city" value="{{!empty($address) ? $address->city : ''}}">
                                    <input type="hidden" name="country" value="{{!empty($address) ? $address->country : 'Bangladesh'}}">
                                    <input type="hidden" name="phone" value="{{!empty($address) ? $address->phone : ''}}">
                                    <input type="hidden" name="shippingCost" value='{{$shippingCost}}'>
                                    <button  type="submit">Proceed to PayPal</button> 
                                </div>    
                            </div> 
                        </form>         
                    </div>
                </div> 
            </div> 
        </div>       
    </div>
    <!--Checkout page section end-->


@endsection