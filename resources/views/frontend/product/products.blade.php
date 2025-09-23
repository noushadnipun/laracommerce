@extends('frontend.layouts.master')

@section('page-content')
<div class="products-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">All Products</h1>
                <p class="page-subtitle">Discover our amazing collection of products</p>
            </div>
        </div>
        
        <!-- Filters and Products -->
        <div class="products-content">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 col-md-4">
                    <div class="filters-sidebar">
                        <h3>Filters</h3>
                        
                        <!-- Search -->
                        <div class="filter-group">
                            <h4>Search</h4>
                            <form method="GET" action="{{ route('frontend_products') }}">
                                <div class="search-box">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Categories -->
                        <div class="filter-group">
                            <h4>Categories</h4>
                            <div class="filter-options">
                                <a href="{{ route('frontend_products') }}" class="filter-option {{ !request('category') ? 'active' : '' }}">
                                    All Categories
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('frontend_products') }}?category={{ $category->slug }}" 
                                   class="filter-option {{ request('category') == $category->slug ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Brands -->
                        <div class="filter-group">
                            <h4>Brands</h4>
                            <div class="filter-options">
                                <a href="{{ route('frontend_products') }}" class="filter-option {{ !request('brand') ? 'active' : '' }}">
                                    All Brands
                                </a>
                                @foreach($brands as $brand)
                                <a href="{{ route('frontend_products') }}?brand={{ $brand->slug }}" 
                                   class="filter-option {{ request('brand') == $brand->slug ? 'active' : '' }}">
                                    {{ $brand->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="filter-group">
                            <h4>Price Range</h4>
                            <form method="GET" action="{{ route('frontend_products') }}">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                
                                <div class="price-range">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Price" min="0">
                                    <span>to</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Price" min="0">
                                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="col-lg-9 col-md-8">
                    <!-- Sort and Results -->
                    <div class="products-header">
                        <div class="results-info">
                            <p>Showing {{ $products->count() }} of {{ $products->total() }} products</p>
                        </div>
                        <div class="sort-options">
                            <form method="GET" action="{{ route('frontend_products') }}">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('brand'))
                                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('min_price'))
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                @endif
                                @if(request('max_price'))
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                @endif
                                
                                <select name="sort" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="products-grid">
                        @if($products->count() > 0)
                            {!! \App\Helpers\Frontend\ProductView::view($products) !!}
                        @else
                            <div class="no-products">
                                <div class="no-products-content">
                                    <i class="fa fa-search"></i>
                                    <h3>No Products Found</h3>
                                    <p>Try adjusting your search or filter criteria</p>
                                    <a href="{{ route('frontend_products') }}" class="btn btn-primary">View All Products</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Products Page Styles */
.products-page {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: 80vh;
}

.page-header {
    background: linear-gradient(135deg, #0063d1 0%, #354b65 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 40px;
    text-align: center;
}

.page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
}

.page-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

.products-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.filters-sidebar {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    position: sticky;
    top: 20px;
}

.filters-sidebar h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.filter-group {
    margin-bottom: 30px;
}

.filter-group h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
}

.search-box {
    display: flex;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color 0.3s ease;
}

.search-box:focus-within {
    border-color: #667eea;
}

.search-box input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    outline: none;
    font-size: 14px;
}

.search-box button {
    padding: 12px 15px;
    background: #0063d1;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.search-box button:hover {
    background: #0052b3;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-option {
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    color: #6c757d;
    transition: all 0.3s ease;
    font-size: 14px;
}

.filter-option:hover {
    background: #f8f9fa;
    color: #495057;
    text-decoration: none;
}

.filter-option.active {
    background: #0063d1;
    color: white;
}

.price-range {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.price-range input {
    padding: 10px 12px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.price-range input:focus {
    outline: none;
    border-color: #0063d1;
}

.price-range span {
    text-align: center;
    color: #6c757d;
    font-size: 14px;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 20px 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.results-info p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.sort-options select {
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.sort-options select:focus {
    outline: none;
    border-color: #0063d1;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.no-products-content i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.no-products-content h3 {
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.no-products-content p {
    color: #adb5bd;
    margin-bottom: 25px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .products-content {
        padding: 0 15px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
    }
    
    .filters-sidebar {
        position: static;
        margin-bottom: 20px;
    }
    
    .products-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-sidebar {
        padding: 20px;
    }
}
</style>
@endsection
