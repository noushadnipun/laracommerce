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

@push('styles')
<link rel="stylesheet" href="{{ asset('public/css/category.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('public/js/category.js') }}"></script>
@endpush