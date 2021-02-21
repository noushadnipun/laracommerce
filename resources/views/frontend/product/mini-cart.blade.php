<?php 
    $totalCartPrice = 0;
    $headTotalCartPrice = 0;
 ?>
<div class="mini_cart_wrapper">
    <a href="javascript::void(0)">
        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
            @foreach((array) session('cart') as $id => $details)
                <?php 
                    $headTotalCartPrice += $details['price'] * $details['qty'];
                ?>
            @endforeach
            {{ App\Helpers\Frontend\ProductView::priceSign($headTotalCartPrice)}}
        <i class="fa fa-angle-down"></i>
    </a>
    <span class="cart_quantity">
        {{ count((array) session('cart')) }}
    </span>
    <!--mini cart-->
    <div class="mini_cart">
        @if(session('cart'))
        @foreach(session('cart') as $id => $details)
        <?php $totalCartPrice += $details['price'] * $details['qty'] ?>
            <div class="cart_item">
                <div class="cart_img">
                    <a href="{{route('frontend_single_product', $details['slug'])}}"><img src="{{App\Models\Media::fileLocation($details['featured_image'])}}" alt=""></a>
                </div>
                <div class="cart_info">
                    <a href="{{route('frontend_single_product', $details['slug'])}}">
                        {{ $details['name'] }}
                    </a>
                    <p>Qty: 
                        {{ $details['qty'] }} x <span> {{App\Helpers\Frontend\ProductView::priceSign($details['price'])}} </span>
                    </p>    
                </div>
                <div dclass="cart_remove">
                    <a href="javascript:void(0)"><i class="ion-android-close remove-from-cart" data-id="{{ $id }}"></i></a>
                </div>
            </div>
        @endforeach
        @endif

        @if(!empty($totalCartPrice))
            <div class="mini_cart_table">
                <div class="cart_total">
                    <span>Sub total:</span>
                    <span class="price">{{ App\Helpers\Frontend\ProductView::priceSign($totalCartPrice) }}</span>
                </div>
            </div>
            @else
            <div class="text-center">Your cart is Empty!</div>
        @endif

        <div class="mini_cart_footer">
            <div class="cart_button">
                <a href="{{route('frontend_cart_index')}}">View cart</a>
            </div>
            <div class="cart_button">
                <a href="{{route('frontend_checkout_index')}}">Checkout</a>
            </div>
        </div>
    </div>
    <!--mini cart end-->
</div>

<script type="text/javascript">
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
    

