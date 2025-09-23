<!-- Simple Products Section -->
<section class="simple-products-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title text-center mb-5">Latest Products</h2>
            </div>
        </div>
        <div class="row">
            @php
                $latestProducts = \App\Models\Product::where('visibility', '1')
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            @endphp
            
            @if($latestProducts->count())
                @foreach($latestProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('frontend_single_product', $product->slug) }}">
                                <img src="{{ $product->getFeaturedImageUrl() }}" 
                                     alt="{{ $product->title }}"
                                     class="img-fluid"
                                     onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'">
                            </a>
                        </div>
                        <div class="product-content">
                            <h5 class="product-title">
                                <a href="{{ route('frontend_single_product', $product->slug) }}">
                                    {{ $product->title }}
                                </a>
                            </h5>
                            <div class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="old-price">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                                    <span class="current-price">৳{{ number_format($product->sale_price / 100, 2) }}</span>
                                @else
                                    <span class="current-price">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                                @endif
                            </div>
                            <div class="product-actions">
                                <?php echo \App\Helpers\Frontend\ProductView::addToCartButton($product); ?>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-center">No products available.</p>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12 text-center mt-4">
                <a href="{{ route('frontend_products') }}" class="btn btn-primary btn-lg">View All Products</a>
            </div>
        </div>
    </div>
</section>

<style>
.simple-products-section {
    padding: 60px 0;
    background: white;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #242424;
    margin-bottom: 50px;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-content {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.product-title a {
    color: #242424;
    text-decoration: none;
}

.product-title a:hover {
    color: #0063d1;
}

.product-price {
    margin-bottom: 15px;
}

.old-price {
    text-decoration: line-through;
    color: #6c757d;
    margin-right: 10px;
}

.current-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #0063d1;
}

.product-actions {
    margin-top: auto;
}

.product-actions .btn {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .product-image {
        height: 150px;
    }
}
</style>
