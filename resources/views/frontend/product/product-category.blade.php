@extends('frontend.layouts.master')

@section('page-content')
<!-- Elegant Product Category Page -->
<div class="elegant-category-page">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs-section">
    <div class="container-fluid">   
        <div class="row">
            <div class="col-12">
                    <nav class="breadcrumb-nav">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('/')}}">
                                    <i class="fa fa-home"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{$categoryName}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>         

    <!-- Main Content -->
    <div class="category-main-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 col-md-4">
                    <div class="elegant-sidebar">
                        <!-- Mobile Filter Toggle -->
                        <div class="mobile-filter-toggle d-lg-none">
                            <button class="filter-btn" id="filter-toggle">
                                <i class="fa fa-filter"></i>
                                <span>Filters</span>
                                <i class="fa fa-chevron-down"></i>
                            </button>
                        </div>

                        <!-- Filter Panel -->
                        <div class="filter-panel" id="filter-panel">
                            <!-- Categories Filter -->
                            <div class="filter-section">
                                <h3 class="filter-title">
                                    <i class="fa fa-list"></i>
                                    Categories
                                </h3>
                                <div class="filter-content">
                                    @php $getProductCats = \App\Models\ProductCategory::where('visibility', '1')->where('parent_id', null)->orderBy('name')->get(); @endphp
                                    <ul class="category-list">
                                        @foreach($getProductCats as $pCat)
                                            <li class="category-item">
                                                <a href="{{ route('frontend_single_product_category', $pCat->slug) }}" 
                                                   class="category-link {{ request()->is('product/category/'.$pCat->slug) ? 'active' : '' }}">
                                                    <i class="fa fa-{{ $pCat->icon ?? 'tag' }}"></i>
                                                    {{ $pCat->name }}
                                                    <span class="category-count">({{ $pCat->products()->count() }})</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="filter-section">
                                <h3 class="filter-title">
                                    <i class="fa fa-dollar-sign"></i>
                                    Price Range
                                </h3>
                                <div class="filter-content">
                                    <form id="price-filter-form" action="{{ route('frontend_filter_price') }}" method="get">
                                        <div class="price-range-container">
                                            <div class="price-inputs">
                                                <div class="price-input-group">
                                                    <label>Min Price</label>
                                                    <input type="number" name="min_price" id="min-price" 
                                                           value="{{ request('min_price') }}" 
                                                           placeholder="0" min="0">
                                                </div>
                                                <div class="price-input-group">
                                                    <label>Max Price</label>
                                                    <input type="number" name="max_price" id="max-price" 
                                                           value="{{ request('max_price') }}" 
                                                           placeholder="10000" min="0">
                                                </div>
                                            </div>
                                            <div class="price-slider-container">
                                                <div id="price-slider"></div>
                                            </div>
                                            <div class="price-display">
                                                <span id="price-range-display">৳0 - ৳10,000</span>
                                            </div>
                                            <button type="submit" class="apply-filter-btn">
                                                <i class="fa fa-check"></i>
                                                Apply Filter
                                            </button>
                                        </div>
                            </form>
                        </div>
                            </div>

                            <!-- Brand Filter -->
                            <div class="filter-section">
                                <h3 class="filter-title">
                                    <i class="fa fa-tags"></i>
                                    Brands
                                </h3>
                                <div class="filter-content">
                                    @php $brands = \App\Models\ProductBrand::where('visibility', '1')->orderBy('name')->get(); @endphp
                                    <div class="brand-list">
                                        @foreach($brands as $brand)
                                            <label class="brand-checkbox">
                                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                                                       class="brand-filter" 
                                                       {{ in_array($brand->id, (array)request('brands', [])) ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span class="brand-name">{{ $brand->name }}</span>
                                                <span class="brand-count">({{ $brand->products()->count() }})</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Clear Filters -->
                            <div class="filter-actions">
                                <button type="button" class="clear-filters-btn" id="clear-filters">
                                    <i class="fa fa-times"></i>
                                    Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                                        </div>

                <!-- Products Section -->
                <div class="col-lg-9 col-md-8">
                    <div class="products-section">
                        <!-- Products Header -->
                        <div class="products-header">
                            <div class="products-title">
                                <h1>{{ $categoryName }}</h1>
                                <p class="products-count">{{ $getProduct->total() }} products found</p>
                                        </div>
                            
                            <!-- Sort and View Options -->
                            <div class="products-controls">
                                <!-- Sort Options -->
                                <div class="sort-options">
                                    <form id="sort-form" method="get">
                                        <select name="sort" id="sort-select" class="sort-select">
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                        </select>
                                            </form>
                                        </div>

                                <!-- View Options -->
                                <div class="view-options">
                                    <button class="view-btn active" data-view="grid" title="Grid View">
                                        <i class="fa fa-th"></i>
                                    </button>
                                    <button class="view-btn" data-view="list" title="List View">
                                        <i class="fa fa-list"></i>
                                    </button>
                                            </div>
                                        </div>
                                    </div>

                        <!-- Products Grid -->
                        <div class="products-grid" id="products-container">
                            <?php echo \App\Helpers\Frontend\ProductView::view($getProduct); ?>
                    </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $getProduct->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- ProductView Modal (Elegant Design) -->
        <?php echo \App\Helpers\Frontend\ProductView::productModal(); ?>
        @endsection

@section('cusjs')
<style>
/* Elegant Category Page Styles */
.elegant-category-page {
    background: #f8f9fa;
    min-height: 100vh;
}

/* Breadcrumbs */
.breadcrumbs-section {
    background: white;
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-nav .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-nav .breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-nav .breadcrumb-item a {
    color: #0063d1;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
}

.breadcrumb-nav .breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

/* Main Content */
.category-main-content {
    padding: 30px 0;
}

/* Elegant Sidebar */
.elegant-sidebar {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    position: sticky;
    top: 20px;
}

.mobile-filter-toggle {
    display: none;
    padding: 15px 20px;
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: none;
    border: none;
    color: white;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    justify-content: space-between;
}

.filter-panel {
    padding: 0;
}

/* Filter Sections */
.filter-section {
    border-bottom: 1px solid #e9ecef;
}

.filter-section:last-child {
    border-bottom: none;
}

.filter-title {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 20px 15px 20px;
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.filter-title i {
    color: #0063d1;
    font-size: 14px;
}

.filter-content {
    padding: 20px;
}

/* Category List */
.category-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.category-item {
    margin-bottom: 8px;
}

.category-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    color: #666;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
}

.category-link:hover {
    background: #f8f9fa;
    color: #0063d1;
    transform: translateX(5px);
}

.category-link.active {
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
}

.category-link i {
    font-size: 14px;
    width: 16px;
    text-align: center;
}

.category-count {
    margin-left: auto;
    font-size: 12px;
    opacity: 0.7;
}

/* Price Filter */
.price-range-container {
    space-y: 15px;
}

.price-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.price-input-group {
    display: flex;
    flex-direction: column;
}

.price-input-group label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    margin-bottom: 5px;
}

.price-input-group input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.price-input-group input:focus {
    outline: none;
    border-color: #0063d1;
    box-shadow: 0 0 0 2px rgba(0, 99, 209, 0.1);
}

.price-slider-container {
    margin: 20px 0;
}

#price-slider {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    position: relative;
}

.price-display {
    text-align: center;
    margin: 15px 0;
    font-weight: 500;
    color: #0063d1;
}

.apply-filter-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.apply-filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.3);
}

/* Brand Filter */
.brand-list {
    max-height: 300px;
    overflow-y: auto;
}

.brand-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    cursor: pointer;
    position: relative;
}

.brand-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #ddd;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
}

.brand-checkbox input[type="checkbox"]:checked + .checkmark {
    background: #0063d1;
    border-color: #0063d1;
}

.brand-checkbox input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.brand-name {
    flex: 1;
    font-size: 14px;
    color: #333;
}

.brand-count {
    font-size: 12px;
    color: #666;
}

/* Filter Actions */
.filter-actions {
    padding: 20px;
    background: #f8f9fa;
}

.clear-filters-btn {
    width: 100%;
    padding: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.clear-filters-btn:hover {
    background: #c82333;
    transform: translateY(-1px);
}

/* Products Section */
.products-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 30px;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-bottom: 1px solid #e9ecef;
}

.products-title h1 {
    margin: 0 0 5px 0;
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.products-count {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.products-controls {
    display: flex;
    align-items: center;
    gap: 20px;
}

.sort-options {
    position: relative;
}

.sort-select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    cursor: pointer;
    min-width: 180px;
}

.view-options {
    display: flex;
    gap: 5px;
}

.view-btn {
    padding: 10px 12px;
    border: 1px solid #ddd;
    background: white;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 6px;
}

.view-btn:first-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.view-btn:last-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: none;
}

.view-btn.active {
    background: #0063d1;
    color: white;
    border-color: #0063d1;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    padding: 30px;
}

/* Enhanced ProductView Styles */
.products-grid .single_product {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    margin-bottom: 0;
}

.products-grid .single_product:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.products-grid .product_thumb {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
}

.products-grid .product_thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.products-grid .single_product:hover .product_thumb img {
    transform: scale(1.05);
}

.products-grid .label_product {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.products-grid .label_sale {
    background: #ff4757;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.products-grid .label_stockout {
    background: #6c757d;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.products-grid .action_links {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 3;
}

.products-grid .single_product:hover .action_links {
    opacity: 1;
}

.products-grid .action_links ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.products-grid .action_links li {
    margin: 0;
}

.products-grid .action_links a {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    color: #666;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    text-decoration: none;
}

.products-grid .action_links a:hover {
    background: #0063d1;
    color: white;
    transform: scale(1.1);
}

.products-grid .product_content {
    padding: 20px;
}

.products-grid .product_name {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
}

.products-grid .product_name a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.products-grid .product_name a:hover {
    color: #0063d1;
}

.products-grid .price_box {
    margin-bottom: 10px;
}

.products-grid .old_price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    margin-right: 8px;
}

.products-grid .current_price {
    color: #0063d1;
    font-size: 18px;
    font-weight: 700;
}

.products-grid .add_to_cart {
    margin-top: 15px;
}

.products-grid .add_to_cart_btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.products-grid .add_to_cart_btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.products-grid .add_to_cart_btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Pagination */
.pagination-wrapper {
    padding: 30px;
    text-align: center;
    background: #f8f9fa;
}

.pagination-wrapper .pagination {
    justify-content: center;
}

.pagination-wrapper .page-link {
    color: #0063d1;
    border-color: #ddd;
    padding: 10px 15px;
    margin: 0 2px;
    border-radius: 8px;
}

.pagination-wrapper .page-item.active .page-link {
    background: #0063d1;
    border-color: #0063d1;
}

/* Responsive Design */
@media (max-width: 991px) {
    .elegant-sidebar {
        position: static;
        margin-bottom: 30px;
    }
    
    .mobile-filter-toggle {
        display: block;
    }
    
    .filter-panel {
        display: none;
    }
    
    .filter-panel.show {
        display: block;
    }
    
    .products-header {
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }
    
    .products-controls {
        width: 100%;
        justify-content: space-between;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    
    .products-title h1 {
        font-size: 24px;
    }
    
    .price-inputs {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        padding: 15px;
    }
    
    .products-header {
        padding: 20px;
    }
    
    .products-controls {
        flex-direction: column;
        gap: 15px;
        width: 100%;
    }
    
    .sort-options {
        width: 100%;
    }
    
    .sort-select {
        width: 100%;
    }
}
</style>

<script>
$(document).ready(function() {
    // Mobile filter toggle
    $('#filter-toggle').click(function() {
        $('#filter-panel').toggleClass('show');
        $(this).find('.fa-chevron-down').toggleClass('fa-chevron-up');
    });
    
    // Sort form submission
    $('#sort-select').change(function() {
        $('#sort-form').submit();
    });
    
    // View toggle
    $('.view-btn').click(function() {
        $('.view-btn').removeClass('active');
        $(this).addClass('active');
        
        var view = $(this).data('view');
        if (view === 'list') {
            $('.products-grid').addClass('list-view');
            $('.product-card').addClass('list-card');
        } else {
            $('.products-grid').removeClass('list-view');
            $('.product-card').removeClass('list-card');
        }
    });
    
    // Brand filter
    $('.brand-filter').change(function() {
        var selectedBrands = [];
        $('.brand-filter:checked').each(function() {
            selectedBrands.push($(this).val());
        });
        
        // Update URL with brand filters
        var url = new URL(window.location);
        if (selectedBrands.length > 0) {
            url.searchParams.set('brands', selectedBrands.join(','));
        } else {
            url.searchParams.delete('brands');
        }
        
        window.location.href = url.toString();
    });
    
    // Clear filters
    $('#clear-filters').click(function() {
        window.location.href = window.location.pathname;
    });
    
    // Price slider (if you have jQuery UI)
    if ($.fn.slider) {
        $('#price-slider').slider({
            range: true,
            min: 0,
            max: 10000,
            values: [0, 10000],
            slide: function(event, ui) {
                $('#min-price').val(ui.values[0]);
                $('#max-price').val(ui.values[1]);
                $('#price-range-display').text('৳' + ui.values[0] + ' - ৳' + ui.values[1]);
            }
        });
    }
    
            // ProductView helper already handles all product interactions
            // No additional JavaScript needed as ProductView::view() includes all necessary handlers
});
</script>
@endsection