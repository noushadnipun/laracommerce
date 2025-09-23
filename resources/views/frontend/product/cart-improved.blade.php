@extends('frontend.layouts.master')

@section('title', 'Shopping Cart')

@section('page-content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="container">   
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('frontend_home') }}">Home</a></li>
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
        <!-- Display Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('cart'))
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 col-md-12">
                <div class="cart_items_wrapper">
                    <div class="cart_header d-flex justify-content-between align-items-center mb-4">
                        <h3 class="cart_title">
                            <i class="fas fa-shopping-cart"></i> Shopping Cart
                            <span class="cart_count badge badge-primary ml-2">{{ count(session('cart')) }} items</span>
                        </h3>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-cart-clear>
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </div>

                    <div class="cart_items">
                        @foreach(session('cart') as $id => $details)
                            @php
                                $product = \App\Models\Product::with('inventory')->find($details['id']);
                                $totalCartPrice += $details['price'] * $details['qty'];
                            @endphp
                            <div class="cart_item" data-cart-key="{{ $id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-sm-3">
                                        <div class="cart_item_image">
                                            <img src="{{ $details['image'] ?? '/images/no-image.jpg' }}" 
                                                 alt="{{ $details['name'] }}" 
                                                 class="img-fluid rounded">
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="col-md-4 col-sm-9">
                                        <div class="cart_item_details">
                                            <h5 class="product_name">
                                                <a href="{{ route('frontend_single_product', $product->slug ?? '#') }}">
                                                    {{ $details['name'] }}
                                                </a>
                                            </h5>
                                            
                                            @if($product && $product->inventory)
                                                @if($product->isOutOfStock())
                                                    <small class="text-danger">
                                                        <i class="fa fa-times-circle"></i> Out of Stock
                                                    </small>
                                                @elseif($product->inventory->current_stock < $details['qty'])
                                                    <small class="text-warning">
                                                        <i class="fa fa-exclamation-triangle"></i> Only {{ $product->inventory->current_stock }} available
                                                    </small>
                                                @else
                                                    <small class="text-success">
                                                        <i class="fa fa-check-circle"></i> In Stock ({{ $product->inventory->current_stock }} available)
                                                    </small>
                                                @endif
                                            @endif

                                            @if(!empty($details['attribute']))
                                                <div class="product_attributes mt-2">
                                                    @foreach($details['attribute'] as $key => $value)
                                                        <small class="attribute_item">
                                                            <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                                        </small>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2 col-sm-6">
                                        <div class="cart_item_price">
                                            <span class="price">{{ App\Helpers\Frontend\ProductView::priceSign($details['price']) }}</span>
                                        </div>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-2 col-sm-6">
                                        <div class="cart_item_quantity">
                                            <div class="quantity_input_group">
                                                <button type="button" class="quantity_btn quantity_decrease" 
                                                        data-cart-key="{{ $id }}" 
                                                        data-quantity="{{ $details['qty'] - 1 }}">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       class="quantity_input" 
                                                       value="{{ $details['qty'] }}" 
                                                       min="1" 
                                                       max="{{ $product->inventory->current_stock ?? 999 }}">
                                                <button type="button" class="quantity_btn quantity_increase" 
                                                        data-cart-key="{{ $id }}" 
                                                        data-quantity="{{ $details['qty'] + 1 }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total & Actions -->
                                    <div class="col-md-2 col-sm-12">
                                        <div class="cart_item_total">
                                            <div class="item_total_price">
                                                <strong>{{ App\Helpers\Frontend\ProductView::priceSign($details['price'] * $details['qty']) }}</strong>
                                            </div>
                                            <div class="item_actions mt-2">
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-sm" 
                                                        data-cart-update 
                                                        data-cart-key="{{ $id }}">
                                                    <i class="fas fa-sync"></i> Update
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm ml-1" 
                                                        data-cart-remove 
                                                        data-cart-key="{{ $id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4 col-md-12">
                <div class="cart_summary_wrapper">
                    <div class="cart_summary">
                        <h4 class="summary_title">
                            <i class="fas fa-calculator"></i> Order Summary
                        </h4>

                        <div class="summary_details">
                            <div class="summary_row d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span data-subtotal>{{ App\Helpers\Frontend\ProductView::priceSign($totalCartPrice) }}</span>
                            </div>

                            @php 
                                $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
                                $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
                                $shippingCost = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;
                            @endphp

                            <div class="summary_row d-flex justify-content-between">
                                <span>Shipping:</span>
                                <span data-shipping>{{ App\Helpers\Frontend\ProductView::priceSign($shippingCost) }}</span>
                            </div>

                            @if(Session::has('couponApplied'))
                                @php $coupon = Session::get('couponApplied'); @endphp
                                <div class="summary_row d-flex justify-content-between text-success">
                                    <span>Discount ({{ $coupon['code'] }}):</span>
                                    <span>-{{ App\Helpers\Frontend\ProductView::priceSign($coupon['discount_amount'] ?? 0) }}</span>
                                </div>
                            @endif

                            <hr>

                            <div class="summary_row d-flex justify-content-between total_row">
                                <strong>Total:</strong>
                                <strong data-total>{{ App\Helpers\Frontend\ProductView::priceSign($totalCartPrice + $shippingCost - (Session::has('couponApplied') ? (Session::get('couponApplied')['discount_amount'] ?? 0) : 0)) }}</strong>
                            </div>
                        </div>

                        <div class="summary_actions">
                            <a href="{{ route('frontend_checkout_index') }}" 
                               class="btn btn-primary btn-lg btn-block mb-3">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>
                            
                            <a href="{{ route('frontend_home') }}" 
                               class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>

                        <!-- Coupon Code -->
                        <div class="coupon_section mt-4">
                            <h6>Have a coupon code?</h6>
                            <form action="{{ route('frontend_apply_coupon') }}" method="POST" class="coupon_form">
                                @csrf
                                <div class="input-group">
                                    <input type="text" 
                                           name="coupon_code" 
                                           class="form-control" 
                                           placeholder="Enter coupon code"
                                           value="{{ Session::has('couponApplied') ? Session::get('couponApplied')['code'] : '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-primary">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <!-- Empty Cart -->
            <div class="empty_cart text-center py-5">
                <div class="empty_cart_icon mb-4">
                    <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                </div>
                <h3 class="empty_cart_title">Your cart is empty</h3>
                <p class="empty_cart_message text-muted mb-4">
                    Looks like you haven't added any items to your cart yet.
                </p>
                <a href="{{ route('frontend_home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>     
</div>
<!--shopping cart area end -->
@endsection

@section('cusjs')
<style>
.cart_items_wrapper {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}

.cart_item {
    border-bottom: 1px solid #eee;
    padding: 20px 0;
}

.cart_item:last-child {
    border-bottom: none;
}

.cart_item_image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.quantity_input_group {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
}

.quantity_btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quantity_btn:hover {
    background: #e9ecef;
}

.quantity_input {
    border: none;
    text-align: center;
    width: 60px;
    padding: 8px;
    outline: none;
}

.cart_summary_wrapper {
    position: sticky;
    top: 20px;
}

.cart_summary {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}

.summary_row {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary_row:last-child {
    border-bottom: none;
}

.total_row {
    font-size: 1.2em;
    color: #333;
}

.empty_cart {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: 50px 0;
}

.attribute_item {
    display: block;
    margin: 2px 0;
    color: #666;
}

@media (max-width: 768px) {
    .cart_items_wrapper,
    .cart_summary {
        padding: 20px;
    }
    
    .cart_item .row > div {
        margin-bottom: 15px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Quantity buttons
    $('.quantity_btn').click(function() {
        const cartKey = $(this).data('cart-key');
        const quantity = $(this).data('quantity');
        
        if (quantity > 0) {
            // Update cart via AJAX
            $.ajax({
                url: '/api/cart/' + cartKey,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ quantity: quantity }),
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Failed to update cart');
                }
            });
        }
    });

    // Update button
    $('[data-cart-update]').click(function() {
        const cartKey = $(this).data('cart-key');
        const quantity = $(this).closest('.cart_item').find('.quantity_input').val();
        
        $.ajax({
            url: '/api/cart/' + cartKey,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({ quantity: parseInt(quantity) }),
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to update cart');
            }
        });
    });

    // Remove button
    $('[data-cart-remove]').click(function() {
        if (confirm('Are you sure you want to remove this item?')) {
            const cartKey = $(this).data('cart-key');
            
            $.ajax({
                url: '/api/cart/' + cartKey,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Failed to remove item');
                }
            });
        }
    });

    // Clear cart button
    $('[data-cart-clear]').click(function() {
        if (confirm('Are you sure you want to clear your cart?')) {
            $.ajax({
                url: '/api/cart',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Failed to clear cart');
                }
            });
        }
    });
});
</script>
@endsection

