@extends('frontend.layouts.master')

@section('title', 'My Wishlist')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page_header">
                <div class="page_title">
                    <h1><i class="fa fa-heart"></i> My Wishlist</h1>
                    <p>Your saved favorite products</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="wishlist_content">
                @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                    <div class="wishlist_products">
                        <div class="row">
                            @foreach($wishlistItems as $item)
                                @if($item && $item->slug)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                                        <div class="wishlist_product_card">
                                            <div class="product_thumb">
                                                <a href="{{ route('frontend_single_product', $item->slug) }}">
                                                    <img src="{{ $item->getFeaturedImageUrl() }}" 
                                                         alt="{{ $item->title }}"
                                                         onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'">
                                                </a>
                                                <div class="product_actions">
                                                    <a href="javascript:void(0)" class="remove_wishlist" data-product-id="{{ $item->id }}" title="Remove from wishlist">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="product_content">
                                                <h3 class="product_name">
                                                    <a href="{{ route('frontend_single_product', $item->slug) }}">
                                                        {{ $item->title }}
                                                    </a>
                                                </h3>
                                                <div class="price_box">
                                                    @if($item->sale_price && $item->sale_price < $item->regular_price)
                                                        <span class="old_price">৳{{ number_format($item->regular_price / 100, 2) }}</span>
                                                        <span class="current_price">৳{{ number_format($item->sale_price / 100, 2) }}</span>
                                                    @else
                                                        <span class="current_price">৳{{ number_format($item->regular_price / 100, 2) }}</span>
                                                    @endif
                                                </div>
                                                <div class="product_actions_bottom">
                                                    <?php echo \App\Helpers\Frontend\ProductView::addToCartButton($item); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="empty_wishlist">
                        <div class="empty_icon">
                            <i class="fa fa-heart-o"></i>
                        </div>
                        <h3>Your wishlist is empty</h3>
                        <p>Start adding products you love to your wishlist</p>
                        <a href="{{ route('frontend_index') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('cusjs')
<style>
.page_header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    margin-bottom: 40px;
}

.page_title h1 {
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page_title h1 i {
    color: #e74c3c;
    margin-right: 10px;
}

.page_title p {
    font-size: 16px;
    color: #6c757d;
    margin: 0;
}

.wishlist_product_card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.wishlist_product_card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.product_thumb {
    position: relative;
    overflow: hidden;
}

.product_thumb img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist_product_card:hover .product_thumb img {
    transform: scale(1.05);
}

.product_actions {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wishlist_product_card:hover .product_actions {
    opacity: 1;
}

.remove_wishlist {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: rgba(231,76,60,0.9);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.remove_wishlist:hover {
    background: #c0392b;
    transform: scale(1.1);
}

.product_content {
    padding: 20px;
}

.product_name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
}

.product_name a {
    color: #2c3e50;
    text-decoration: none;
}

.product_name a:hover {
    color: #007bff;
}

.price_box {
    margin-bottom: 15px;
}

.old_price {
    font-size: 14px;
    color: #6c757d;
    text-decoration: line-through;
    margin-right: 8px;
}

.current_price {
    font-size: 18px;
    font-weight: 700;
    color: #28a745;
}

.product_actions_bottom .btn {
    width: 100%;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    z-index: 10;
    pointer-events: auto;
}

.empty_wishlist {
    text-align: center;
    padding: 80px 20px;
}

.empty_icon {
    font-size: 80px;
    color: #e9ecef;
    margin-bottom: 20px;
}

.empty_wishlist h3 {
    font-size: 24px;
    color: #6c757d;
    margin-bottom: 10px;
}

.empty_wishlist p {
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 30px;
}

@media (max-width: 767px) {
    .page_title h1 {
        font-size: 28px;
    }
    
    .wishlist_product_card {
        margin-bottom: 20px;
    }
    
    .product_thumb img {
        height: 180px;
    }
    
    .product_content {
        padding: 15px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Remove from wishlist
    $('.remove_wishlist').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var $card = $(this).closest('.wishlist_product_card');
        
        if(confirm('Are you sure you want to remove this product from your wishlist?')) {
            $.ajax({
                type: 'POST',
                url: '{{ route("frontend_wishlist_remove") }}',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        $card.fadeOut(300, function() {
                            $(this).remove();
                        });
                                ElegantNotification.success(response.message);
                    }
                },
                error: function(xhr) {
                    ElegantNotification.error('Error removing product from wishlist');
                }
            });
        }
    });
});
</script>
@endsection
