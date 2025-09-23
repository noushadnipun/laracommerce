@extends('frontend.layouts.master')

@section('title', 'Shopping Cart')

@section('style')
<style>
.cart-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}
.cart-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}
.quantity-input {
    width: 80px;
    text-align: center;
}
.cart-summary {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    position: sticky;
    top: 20px;
}
.empty-cart {
    text-align: center;
    padding: 60px 20px;
}
.empty-cart i {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 20px;
}
</style>
@endsection

@section('page-content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-shopping-cart"></i> Shopping Cart
                @if($cart['count'] > 0)
                    <span class="badge badge-primary">{{ $cart['count'] }} items</span>
                @endif
            </h2>
        </div>
    </div>

    @if($cart['count'] > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            @foreach($cart['items'] as $key => $item)
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        @php
                            $productModel = \App\Models\Product::find($item['id'] ?? null);
                            $fallbackImg = asset('public/frontend/images/no-images.jpg');
                            $imgSrc = $item['image'] ?? ($item['image_url'] ?? ($productModel && method_exists($productModel, 'getFeaturedImageUrl') ? $productModel->getFeaturedImageUrl() : $fallbackImg));
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}" class="product-image" onerror="this.src='{{ $fallbackImg }}'">
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-1">{{ $item['name'] }}</h5>
                        <p class="text-muted mb-1">SKU: {{ $item['id'] }}</p>
                        @if(!empty($item['attribute']))
                            <div class="attributes">
                                @foreach($item['attribute'] as $attrKey => $attrValue)
                                    <span class="badge badge-light me-1">{{ $attrKey }}: {{ $attrValue }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <div class="quantity-controls">
                            <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                    data-key="{{ $key }}" data-action="decrease">-</button>
                            <input type="number" class="form-control quantity-input d-inline-block" 
                                   value="{{ $item['qty'] }}" min="1" data-key="{{ $key }}">
                            <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                    data-key="{{ $key }}" data-action="increase">+</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="price">
                            <h6 class="mb-0">{{ \App\Helpers\Cart\CartHelper::formatAmount($item['price']) }}</h6>
                            <small class="text-muted">per unit</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="total-price">
                            <h5 class="mb-0 text-primary">{{ \App\Helpers\Cart\CartHelper::formatAmount($item['price'] * $item['qty']) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <button class="btn btn-sm btn-outline-danger remove-item" data-key="{{ $key }}">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <h4 class="mb-3">Order Summary</h4>
                
                <div class="summary-item d-flex justify-content-between mb-2">
                    <span>Subtotal ({{ $cart['count'] }} items):</span>
                    <span>{{ $cart['formatted_total'] }}</span>
                </div>
                
                <div class="summary-item d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>৳0.00</span>
                </div>
                
                <div class="summary-item d-flex justify-content-between mb-2">
                    <span>Tax:</span>
                    <span>৳0.00</span>
                </div>
                
                <hr>
                
                <div class="summary-item d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="text-primary">{{ $cart['formatted_total'] }}</strong>
                </div>
                
                <div class="cart-actions">
                    <a href="{{ route('frontend_checkout_index') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-credit-card"></i> Proceed to Checkout
                    </a>
                    <a href="{{ route('frontend_index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="row">
        <div class="col-12">
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('frontend_index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Quantity controls
    $('.quantity-btn').on('click', function() {
        const key = $(this).data('key');
        const action = $(this).data('action');
        const input = $(`input[data-key="${key}"]`);
        let quantity = parseInt(input.val());
        
        if (action === 'increase') {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        }
        
        updateCartItem(key, quantity);
    });
    
    // Quantity input change
    $('.quantity-input').on('change', function() {
        const key = $(this).data('key');
        const quantity = parseInt($(this).val());
        
        if (quantity < 1) {
            $(this).val(1);
            return;
        }
        
        updateCartItem(key, quantity);
    });
    
    // Remove item
    $('.remove-item').on('click', function() {
        const key = $(this).data('key');
        
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            removeCartItem(key);
        }
    });
    
    function updateCartItem(key, quantity) {
        $.ajax({
            url: '{{ route("frontend_cart_update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                cart_key: key,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating the cart.');
            }
        });
    }
    
    function removeCartItem(key) {
        $.ajax({
            url: '{{ route("frontend_cart_remove") }}',
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                cart_key: key
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred while removing the item.');
            }
        });
    }
});
</script>
@endsection


