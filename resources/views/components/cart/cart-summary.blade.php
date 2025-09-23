<div class="cart-summary">
    @if($showItems && !empty($cart['items']))
        <div class="cart-items">
            @foreach($cart['items'] as $key => $item)
                <div class="cart-item d-flex align-items-center mb-3">
                    <div class="item-image me-3">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" 
                             class="img-fluid" style="width: 60px; height: 60px; object-fit: cover;">
                    </div>
                    <div class="item-details flex-grow-1">
                        <h6 class="mb-1">{{ $item['name'] }}</h6>
                        <p class="text-muted mb-1">Qty: {{ $item['qty'] }}</p>
                        <p class="mb-0 fw-bold">{{ \App\Helpers\Cart\CartHelper::formatAmount($item['price'] * $item['qty']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($showTotal)
        <div class="cart-totals">
            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span>{{ $cart['formatted_total'] }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Items:</span>
                <span>{{ $cart['count'] }}</span>
            </div>
        </div>
    @endif

    @if($showActions)
        <div class="cart-actions mt-3">
            <a href="{{ route('frontend_cart_index') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                <i class="fas fa-shopping-cart"></i> View Cart
            </a>
            <a href="{{ route('frontend_checkout_index') }}" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-credit-card"></i> Checkout
            </a>
        </div>
    @endif
</div>