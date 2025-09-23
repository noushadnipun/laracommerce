<?php 
    $totalCartPrice = 0;
    $headTotalCartPrice = 0;
?>
<div class="mini_cart_wrapper">
    <a href="javascript::void(0)">
        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
        @foreach((array) session('cart') as $id => $details)
            <?php $headTotalCartPrice += ($details['price'] ?? 0) * ($details['qty'] ?? 0); ?>
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
                <?php $totalCartPrice += ($details['price'] ?? 0) * ($details['qty'] ?? 0); ?>
                <div class="cart_item">
                    <div class="cart_img">
                       @php $img = \App\Models\Product::imageUrlById($details['id'] ?? null); @endphp
                        <a href="{{route('frontend_single_product', $details['slug'])}}"><img src="{{$img}}" alt="{{$details['name']}}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;"></a>
                    </div>
                    <div class="cart_info">
                        <a href="{{route('frontend_single_product', $details['slug'])}}">{{ $details['name'] }}</a>
                        <p>Qty: {{ $details['qty'] }} x <span> {{App\Helpers\Frontend\ProductView::priceSign($details['price'])}} </span></p>
                    </div>
                    <div class="cart_remove">
                        <form action="{{ route('frontend_cart_remove') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="btn btn-link p-0" title="Remove"><i class="ion-android-close"></i></button>
                        </form>
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