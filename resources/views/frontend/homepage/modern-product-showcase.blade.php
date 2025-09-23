@php
    $productShowCase = \App\Helpers\WebsiteSettings::homeProductShowCase();
    $showcaseCategories = $productShowCase ? $productShowCase : [];
@endphp

@if(!empty($showcaseCategories))
    @foreach($showcaseCategories as $categoryIndex => $categoryID)
    @php
        $categoryName = \App\Models\ProductCategory::categoryName($categoryID);
        $products = \App\Models\Product::productByCatId($categoryID)->where('visibility', '1')->limit(8)->get();
        $categoryColors = ['#0063d1', '#354b65', '#242424', '#0063d1', '#354b65', '#242424'];
        $categoryColor = $categoryColors[$categoryIndex % count($categoryColors)];
    @endphp
    
    @if($products->count())
    <section class="modern-product-showcase" style="--category-color: {{ $categoryColor }}">
        <div class="showcase-container">
            <!-- Section Header -->
            <div class="showcase-header" data-aos="fade-up">
                <div class="section-badge">
                    <span class="badge-icon">🏷️</span>
                    <span class="badge-text">Featured Collection</span>
                </div>
                <h2 class="section-title">{{ $categoryName }}</h2>
                <p class="section-subtitle">Discover our carefully curated selection of premium products</p>
                <div class="section-decoration">
                    <div class="decoration-line"></div>
                    <div class="decoration-dot"></div>
                    <div class="decoration-line"></div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="products-grid" data-aos="fade-up" data-aos-delay="200">
                @foreach($products as $productIndex => $product)
                <div class="product-card" data-aos="zoom-in" data-aos-delay="{{ $productIndex * 100 }}">
                    <div class="product-image-container">
                        <a href="{{ route('frontend_single_product', $product->slug) }}" class="product-link">
                            <img src="{{ $product->getFeaturedImageUrl() }}" 
                                 alt="{{ $product->title }}"
                                 onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'"
                                 class="product-image">
                        </a>
                        
                        <!-- Product Labels -->
                        <div class="product-labels">
                            @if($product->sale_price && $product->sale_price < $product->regular_price)
                                <span class="label-sale">Sale</span>
                            @endif
                            @if($product->isOutOfStock())
                                <span class="label-out">Out of Stock</span>
                            @endif
                        </div>
                        
                        <!-- Product Actions -->
                        <div class="product-actions">
                            <button class="action-btn wishlist-btn" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                <i class="fa fa-heart"></i>
                            </button>
                            <button class="action-btn compare-btn" data-product-id="{{ $product->id }}" title="Add to Compare">
                                <i class="fa fa-balance-scale"></i>
                            </button>
                            <a href="{{ route('frontend_single_product', $product->slug) }}" class="action-btn quick-view-btn" title="Quick View">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <div class="add-to-cart-container">
                            <form method="POST" action="{{ route('frontend_cart_store') }}" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="add-to-cart-btn" {{ $product->isOutOfStock() ? 'disabled' : '' }}>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>{{ $product->isOutOfStock() ? 'Out of Stock' : 'Add to Cart' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <div class="product-category">{{ $categoryName }}</div>
                        <h3 class="product-title">
                            <a href="{{ route('frontend_single_product', $product->slug) }}">{{ $product->title }}</a>
                        </h3>
                        <div class="product-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star {{ $i <= 4 ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-count">({{ rand(10, 100) }})</span>
                        </div>
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->regular_price)
                                <span class="price-old">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                                <span class="price-new">৳{{ number_format($product->sale_price / 100, 2) }}</span>
                                <span class="discount-badge">-{{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}%</span>
                            @else
                                <span class="price-current">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="showcase-footer" data-aos="fade-up" data-aos-delay="400">
                @php
                    $category = \App\Models\ProductCategory::find($categoryID);
                    $categorySlug = $category ? $category->slug : 'all';
                @endphp
                <a href="{{ route('frontend_products') }}?category={{ $categorySlug }}" class="view-all-btn">
                    <span>View All Products</span>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
    @endforeach
@endif

<style>
/* Modern Product Showcase Styles */
.modern-product-showcase {
    padding: 80px 0;
    background: linear-gradient(135deg, #ffffff 0%, #f6fafb 100%);
    position: relative;
    overflow: hidden;
}

.modern-product-showcase::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 80%, var(--category-color, #667eea) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, var(--category-color, #667eea) 0%, transparent 50%);
    opacity: 0.03;
}

.showcase-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.showcase-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--category-color, #667eea), color-mix(in srgb, var(--category-color, #667eea) 80%, black));
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 3rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 15px;
    background: linear-gradient(135deg, #2c3e50, var(--category-color, #667eea));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.section-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.decoration-line {
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--category-color, #667eea), transparent);
}

.decoration-dot {
    width: 8px;
    height: 8px;
    background: var(--category-color, #667eea);
    border-radius: 50%;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.product-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    border: 1px solid rgba(255,255,255,0.2);
    position: relative;
}


.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.product-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-link {
    display: block;
    height: 100%;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-labels {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.label-sale, .label-out {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.label-sale {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
}

.label-out {
    background: linear-gradient(135deg, #6c757d, #5a6268);
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    color: #6c757d;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.action-btn:hover {
    background: var(--category-color, #667eea);
    color: white;
    transform: scale(1.1);
}

.add-to-cart-container {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 3;
}

.product-card:hover .add-to-cart-container {
    opacity: 1;
    transform: translateY(0);
}

.add-to-cart-form {
    width: 100%;
}

.add-to-cart-btn {
    width: 100%;
    padding: 12px 20px;
    background: linear-gradient(135deg, var(--category-color, #667eea), color-mix(in srgb, var(--category-color, #667eea) 80%, black));
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.add-to-cart-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.add-to-cart-btn:disabled {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    cursor: not-allowed;
    opacity: 0.7;
}

.product-info {
    padding: 25px;
    position: relative;
    z-index: 2;
}

.product-category {
    font-size: 12px;
    color: var(--category-color, #667eea);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: var(--category-color, #667eea);
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    font-size: 12px;
    color: #ddd;
    transition: color 0.3s ease;
}

.stars i.active {
    color: #ffc107;
}

.rating-count {
    font-size: 12px;
    color: #6c757d;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.price-old {
    font-size: 14px;
    color: #6c757d;
    text-decoration: line-through;
}

.price-new, .price-current {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--category-color, #667eea);
}

.discount-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.showcase-footer {
    text-align: center;
}

.view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--category-color, #667eea), color-mix(in srgb, var(--category-color, #667eea) 80%, black));
    color: white;
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.view-all-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.2);
    color: white;
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modern-product-showcase {
        padding: 60px 0;
    }
    
    .section-title {
        font-size: 2.2rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .product-image-container {
        height: 200px;
    }
    
    .product-info {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .modern-product-showcase {
        padding: 40px 0;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .product-image-container {
        height: 180px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Add to cart functionality
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var button = form.find('.add-to-cart-btn');
        var originalText = button.html();
        
        // Show loading state
        button.html('<i class="fa fa-spinner fa-spin"></i> Adding...').prop('disabled', true);
        
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    ElegantNotification.success(response.message);
                    button.html('<i class="fa fa-check"></i> Added!');
                    setTimeout(() => {
                        button.html(originalText).prop('disabled', false);
                    }, 2000);
                } else {
                    ElegantNotification.error(response.message);
                    button.html(originalText).prop('disabled', false);
                }
            },
            error: function(xhr) {
                ElegantNotification.error('Error adding product to cart');
                button.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Wishlist and compare functionality (using existing handlers from ProductView)
    $('.wishlist-btn').on('click', function(e) {
        e.preventDefault();
        // This will be handled by the existing ProductView handlers
    });
    
    $('.compare-btn').on('click', function(e) {
        e.preventDefault();
        // This will be handled by the existing ProductView handlers
    });
});
</script>
