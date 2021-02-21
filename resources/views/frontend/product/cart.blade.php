@extends('frontend.layouts.master')

@section('page-content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="index.html">home</a></li>
                            <li>Shopping Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->

     <!--shopping cart area start -->
    <div class="shopping_cart_area mt-60">
        <div class="container">  
            <?php $totalCartPrice = 0 ?>
            @if(session('cart'))
            <form method="POST" action="{{route('frontend_cart_update_multiple')}}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="table_desc"> 
                            <div class="cart_page table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_remove">Delete</th>
                                            <th class="product_thumb">Image</th>
                                            <th class="product_name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product_quantity">Quantity</th>
                                            <th class="product_total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach(session('cart') as $id => $details)
                                            <?php $totalCartPrice += $details['price'] * $details['qty'] ?>
                                            <input type="hidden" name="id[]" value="{{$id}}"/>
                                            <input type="hidden" name="pid[]" value="{{$details['id']}}"/>
                                            <tr>
                                                <td class="product_remove">
                                                    <a href="javascript:void(0)" class="remove-from-cart" data-id="{{ $id }}" title="Delete">
                                                        <i class="fa fa-trash-o"></i
                                                    ></a>
                                                    <a href="javascript:void(0)" class="update-cart" data-id="{{ $id }}" title="update">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </td>
                                                <td class="product_thumb"><a href="#"><img src="{{App\Models\Media::fileLocation($details['featured_image'])}}" alt=""></a></td>
                                                <td class="product_name">
                                                    <a href="{{route('frontend_single_product', $details['slug'])}}">
                                                        {{ $details['name'] }}
                                                    </a><br/>
                                                    @if(isset($details['attribute']))
                                                        @foreach($details['attribute'] as $attr => $value)
                                                            
                                                            {{$attr}} :
                                                            @foreach($value as $val)
                                                                {{$val}} <br/>
                                                            @endforeach
                                                        @endforeach   
                                                    @endif
                                                </td>
                                                <td class="product-price" data-th="Price">
                                                    {{App\Helpers\Frontend\ProductView::priceSign($details['price'])}}
                                                </td>
                                                <td class="product_quantity" data-th="Quantity">
                                                    <label>Quantity</label> 
                                                    <input min="1" max="100" value="{{$details['qty']}}" type="number" class="quantity" name="qty[]" />
                                                </td>
                                                <td class="product_total">
                                                    {{ App\Helpers\Frontend\ProductView::priceSign( $details['qty']*$details['price'] )}}
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>   
                            </div>  
                            <div class="cart_submit">
                                <button type="submit">update cart</button>
                            </div>   
                        </div>
                    </div>
                </div>
            </form> 

            <!--coupon code area start-->
            <div class="coupon_area">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="coupon_code left">
                            <h3>Coupon</h3>
                            <form action="{{route('frontend_apply_coupon')}}" method="POST">
                                @csrf
                                <div class="coupon_inner">   
                                    <p>Enter your coupon code if you have one.</p>                                
                                    <input placeholder="Coupon code" type="text" name="coupon">
                                    <button type="submit">Apply coupon</button>
                                </div>
                            </form>    
                        </div>
                    </div>
                    @php //dump(session('couponApplied')) 
                    @endphp
                    <div class="col-lg-6 col-md-6">
                        <div class="coupon_code right">
                            <h3>Cart Totals</h3>
                            <div class="coupon_inner">
                                <div class="cart_subtotal">
                                    <p>Subtotal</p>
                                    <p class="cart_amount">
                                        {{ App\Helpers\Frontend\ProductView::priceSign($totalCartPrice) }}
                                    </p>
                                </div>

                                @if(!empty(session('couponApplied')))
                                @php $couponEle = session('couponApplied')['coupon'] @endphp

                                <div class="cart_subtotal mb-3" style="border-bottom: 1px solid #ebebeb;">
                                    <p>Coupon 
                                        <span class="badge badge-success">{{$couponEle['couponCode']}}</span>
                                        <a href="{{route('frontend_apply_coupon_remove')}}" class="d-inline-block">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </p>
                                    <p class="cart_amount">
                                       - {{ $couponEle['couponAnountWithSign'] }}
                                    </p>
                                </div>
                                <div class="cart_subtotal">
                                    <p>Subtotal With Discount 
                                        
                                    </p>
                                    <p class="cart_amount">
                                       {{ App\Helpers\Frontend\ProductView::priceSign($couponEle['amountAfterApplyCoupon']) }}
                                    </p>
                                </div>
                                @endif

                                @php 
                                    $subTotal = !empty($couponEle) ? $couponEle['amountAfterApplyCoupon'] : $totalCartPrice;
                                    //Shipping Cost
                                    $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
                                    $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
                                    $shippingCost = $shippingType['meta_value'] == 'flat_rate' ? $shippingRate['meta_value'] : '0';

                                    //End Shipping Cost
                                    $grandTotal =   $subTotal + $shippingCost; 
                                @endphp

                                <div class="cart_subtotal mb-3" style="border-bottom: 1px solid #ebebeb;">
                                    <p>Shipping</p>
                                    <p class="cart_amount"><span>Flat Rate:</span> {{$shippingCost}}</p>
                                </div>
                                

                                <div class="cart_subtotal">
                                    <p>Grand Total</p>
                                    <p class="cart_amount">
                                        {{ App\Helpers\Frontend\ProductView::priceSign($grandTotal) }}
                                    </p>
                                </div>
                                <div class="checkout_btn">
                                    <a href="{{route('frontend_checkout_index')}}">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--coupon code area end-->
            @else
                <div class="alert alert-danger text-center">
                    Your Cart is Empty.
                </div>
            @endif
        </div>     
    </div>
     <!--shopping cart area end -->
    
@endsection

@section('cusjs')
    <script>
          $(".update-cart").click(function (e) {
           e.preventDefault();
           var ele = $(this);
            $.ajax({
               url: "{{ route('frontend_cart_update') }}",
               method: "post",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("tr").find(".quantity").val()},
               success: function (response) {
                   window.location.reload();
               }
            }).then(function (response) { // a 2xx response
                    var message = response.data.message;
                    // display the message
            }).error(function (error) { // a 4xx response
                    // display an error message
            });
        });



         $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            if(confirm("Are you sure")) {
                $.ajax({
                    url: "{{ route('frontend_cart_remove') }}",
                    method: "DELETE",
                    data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                    success: function (response) {
                        window.location.reload();
                    }
                }).then(function (response) { // a 2xx response
                    var message = response.data.message;
                    // display the message
                }).error(function (error) { // a 4xx response
                    // display an error message
                });
            }
        });
    </script>
@endsection