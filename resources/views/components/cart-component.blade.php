<div class="cart-component">
    @if($isEmpty)
        <!-- Empty Cart -->
        <div class="empty-cart text-center py-5">
            <div class="empty-cart-icon mb-4">
                <i class="fa fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h3 class="empty-cart-title">Your cart is empty</h3>
            <p class="empty-cart-message text-muted mb-4">
                Looks like you haven't added any items to your cart yet.
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @else
        <!-- Cart Items -->
        <div class="cart-items">
            <div class="cart-header d-flex justify-content-between align-items-center mb-4">
                <h3 class="cart-title">
                    <i class="fa fa-shopping-cart"></i> Shopping Cart
                    <span class="cart-count badge badge-primary ml-2">{{ count($cartData) }} items</span>
                </h3>
                <form action="{{ route('frontend_apply_coupon_remove') }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-ticket"></i> Remove Coupon
                    </button>
                </form>
            </div>

            @foreach($cartData as $item)
                <div class="cart-item" data-cart-key="{{ $item['id'] }}">
                    <div class="row align-items-center">
                        <!-- Product Image -->
                        <div class="col-md-2 col-sm-3">
                            <div class="cart-item-image">
                                @php
                                    $fallbackImg = asset('public/frontend/images/no-images.svg');
                                    $imgSrc = '';

                                    // 1) Direct image fields from session
                                    if (!empty($item['image'])) {
                                        $imgSrc = $item['image'];
                                    } elseif (!empty($item['image_url'])) {
                                        $imgSrc = $item['image_url'];
                                    }

                                    // 2) Featured image via Media helper or uploads path
                                    if (empty($imgSrc)) {
                                        $featured = $item['featured_image'] ?? (isset($item['product']) ? ($item['product']->featured_image ?? null) : null);
                                        if (!empty($featured)) {
                                            $imgFromMedia = class_exists('App\\Models\\Media') ? (App\\Models\\Media::fileLocation($featured) ?? null) : null;
                                            $imgSrc = $imgFromMedia ?: asset('uploads/products/'.$featured);
                                        }
                                    }

                                    // 3) Product model helpers/arrays
                                    if (empty($imgSrc) && !empty($item['product'])) {
                                        $productModel = $item['product'];
                                        if (method_exists($productModel, 'getFeaturedImageUrl')) {
                                            $imgSrc = $productModel->getFeaturedImageUrl();
                                        }
                                        if (empty($imgSrc) && is_array($productModel->product_image ?? null) && count($productModel->product_image) > 0) {
                                            $imgSrc = asset('uploads/products/' . $productModel->product_image[0]);
                                        }
                                        if (empty($imgSrc) && is_array($productModel->remote_images ?? null) && count($productModel->remote_images) > 0) {
                                            $imgSrc = $productModel->remote_images[0];
                                        }
                                    }

                                    // 4) Final fallback
                                    if (empty($imgSrc)) {
                                        $imgSrc = $fallbackImg;
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}" class="img-fluid rounded" onerror="this.src='{{ $fallbackImg }}'">
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-4 col-sm-9">
                            <div class="cart-item-details">
                                <h5 class="product-name">
                                    <a href="{{ route('frontend_single_product', $item['product']->slug ?? '#') }}">
                                        {{ $item['name'] }}
                                    </a>
                                </h5>
                                
                                <!-- Stock Status -->
                                @if($item['stock_status']['status'] == 'out_of_stock')
                                    <small class="text-danger">
                                        <i class="fa fa-times-circle"></i> {{ $item['stock_status']['message'] }}
                                    </small>
                                @elseif($item['stock_status']['status'] == 'low_stock')
                                    <small class="text-warning">
                                        <i class="fa fa-exclamation-triangle"></i> {{ $item['stock_status']['message'] }}
                                    </small>
                                @else
                                    <small class="text-success">
                                        <i class="fa fa-check-circle"></i> {{ $item['stock_status']['message'] }}
                                    </small>
                                @endif

                                <!-- Product Attributes -->
                                @if(!empty($item['attributes']))
                                    <div class="product-attributes mt-2">
                                        @foreach($item['attributes'] as $key => $value)
                                            <small class="attribute-item">
                                                <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                            </small>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-md-2 col-sm-6">
                            <div class="cart-item-price">
                                <span class="price">{{ App\Helpers\Frontend\ProductView::priceSign($item['price']) }}</span>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-2 col-sm-6">
                            <div class="cart-item-quantity">
                                <form action="{{ route('frontend_cart_update') }}" method="POST" class="quantity-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <div class="quantity-input-group">
                                        <button type="button" class="quantity-btn quantity-decrease" 
                                                data-quantity="{{ $item['quantity'] - 1 }}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               class="quantity-input" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" 
                                               max="{{ $item['stock_status']['available'] }}">
                                        <button type="button" class="quantity-btn quantity-increase" 
                                                data-quantity="{{ $item['quantity'] + 1 }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Total & Actions -->
                        <div class="col-md-2 col-sm-12">
                            <div class="cart-item-total">
                                <div class="item-total-price">
                                    <strong>{{ App\Helpers\Frontend\ProductView::priceSign($item['total']) }}</strong>
                                </div>
                                <div class="item-actions mt-2">
                                    <button type="submit" 
                                            class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-refresh"></i> Update
                                    </button>
                                    <form action="{{ route('frontend_cart_remove') }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-sm ml-1" 
                                                onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary mt-4">
            <div class="row">
                <div class="col-md-8">
                    <!-- Coupon Code -->
                    <div class="coupon-section">
                        <h6>Have a coupon code?</h6>
                        <form action="{{ route('frontend_apply_coupon') }}" method="POST" class="coupon-form">
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
                <div class="col-md-4">
                    <div class="cart-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>{{ App\Helpers\Frontend\ProductView::priceSign($totalPrice) }}</span>
                        </div>
                        
                        @php 
                            $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
                            $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
                            $shippingCost = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;
                        @endphp
                        
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>{{ App\Helpers\Frontend\ProductView::priceSign($shippingCost) }}</span>
                        </div>

                        @if(Session::has('couponApplied'))
                            @php $coupon = Session::get('couponApplied'); @endphp
                            <div class="total-row text-success">
                                <span>Discount ({{ $coupon['code'] }}):</span>
                                <span>-{{ App\Helpers\Frontend\ProductView::priceSign($coupon['discount_amount'] ?? 0) }}</span>
                            </div>
                        @endif

                        <hr>

                        <div class="total-row total-final">
                            <strong>Total:</strong>
                            <strong>{{ App\Helpers\Frontend\ProductView::priceSign($totalPrice) }}</strong>
                        </div>

                        <div class="checkout-actions mt-3">
                            <a href="{{ route('frontend_checkout_index') }}" 
                               class="btn btn-primary btn-lg btn-block mb-2">
                                <i class="fa fa-credit-card"></i> Proceed to Checkout
                            </a>
                            
                            <a href="{{ url('/') }}" 
                               class="btn btn-outline-secondary btn-block">
                                <i class="fa fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.cart-component {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    padding: 24px;
}

.cart-item {
    border-bottom: 1px solid #f0f2f5;
    padding: 18px 0;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image img {
    width: 88px;
    height: 88px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eef0f3;
}

.quantity-input-group {
    display: flex;
    align-items: center;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.quantity-btn {
    background: #f9fafb;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quantity-btn:hover {
    background: #f3f4f6;
}

.quantity-input {
    border: none;
    text-align: center;
    width: 64px;
    padding: 8px;
    outline: none;
    font-weight: 600;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}

.total-row:last-child {
    border-bottom: none;
}

.total-final {
    font-size: 1.15em;
    color: #111827;
}

.empty-cart {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    margin: 50px 0;
}

.attribute-item {
    display: block;
    margin: 2px 0;
    color: #6b7280;
}

.cart-title { font-weight: 700; color: #111827; }
.price { font-weight: 600; color: #111827; }
.item-total-price strong { font-size: 1.05em; color: #111827; }
.coupon-section h6 { font-weight: 700; color: #111827; }
.checkout-actions .btn { border-radius: 10px; padding: 10px 14px; }
.btn-outline-primary { border-color: #3b82f6; color: #2563eb; }
.btn-outline-primary:hover { background: #eff6ff; }
.btn-outline-danger { border-color: #ef4444; color: #b91c1c; }
.btn-outline-danger:hover { background: #fef2f2; }

@media (max-width: 768px) {
    .cart-component {
        padding: 18px;
    }
    
    .cart-item .row > div {
        margin-bottom: 15px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Quantity buttons
    $('.quantity-btn').click(function() {
        const quantity = $(this).data('quantity');
        const form = $(this).closest('.quantity-form');
        
        if (quantity > 0) {
            form.find('input[name="quantity"]').val(quantity);
            form.submit();
        }
    });

    // Update button
    $('.update-cart-btn').click(function() {
        const form = $(this).closest('.cart-item').find('.quantity-form');
        form.submit();
    });
});
</script>