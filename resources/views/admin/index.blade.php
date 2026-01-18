@extends('admin.layouts.master')

@section('page-content')
@php
$totalProductCat = count(\App\Models\ProductCategory::get());
$totalProductBrand = count(\App\Models\ProductBrand::get());
$totalOrder = count(\App\Models\ProductOrder::get());
$totalPaymentPendingOrder = count(\App\Models\ProductOrder::where('payment_status', 'Pending')->get());
$totalRevenue = \App\Models\ProductOrder::where('payment_status', 'Paid')->sum('final_amount');
@endphp

<!-- Critical Alerts -->
@if($lowStockProducts->count() > 0 || $outOfStockProducts->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-dismissible" style="background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%); border: none; color: #2d3436;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color: #2d3436;">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle" style="color: #e17055;"></i> Stock Alert!</h5>
            @if($lowStockProducts->count() > 0)
                <p><strong>{{ $lowStockProducts->count() }}</strong> products are running low on stock.</p>
            @endif
            @if($outOfStockProducts->count() > 0)
                <p><strong>{{ $outOfStockProducts->count() }}</strong> products are out of stock.</p>
            @endif
            <a href="{{ route('admin_inventory_index') }}" class="btn btn-sm" style="background: #e17055; color: white; border: none;">
                <i class="fas fa-boxes"></i> Manage Inventory
            </a>
        </div>
    </div>
</div>
@endif

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="inner">
                <h3>{{ $inventoryStats['total_products'] }}</h3>
                <p>Total Products</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('admin_product_index') }}" class="small-box-footer" style="background: rgba(255,255,255,0.1); color: white;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="inner">
                <h3>{{ $totalOrder }}</h3>
                <p>Total Orders</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <a href="{{ route('admin_product_order_index') }}" class="small-box-footer" style="background: rgba(255,255,255,0.1); color: white;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #8b4513;">
            <div class="inner">
                <h3>{{ $totalPaymentPendingOrder }}</h3>
                <p>Pending Orders</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('admin_product_order_index') }}?payment_status=Pending" class="small-box-footer" style="background: rgba(139,69,19,0.1); color: #8b4513;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2c3e50;">
            <div class="inner">
                <h3>৳{{ number_format($totalRevenue / 100, 0) }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="{{ route('admin_product_order_index') }}?payment_status=Paid" class="small-box-footer" style="background: rgba(44,62,80,0.1); color: #2c3e50;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Inventory Status Overview -->
<div class="row mb-4">
    <div class="col-lg-4 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="inner">
                <h3>{{ $inventoryStats['in_stock_count'] }}</h3>
                <p>In Stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin_inventory_index') }}?stock_status=in_stock" class="small-box-footer" style="background: rgba(255,255,255,0.1); color: white;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #8b4513;">
            <div class="inner">
                <h3>{{ $inventoryStats['low_stock_count'] }}</h3>
                <p>Low Stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('admin_inventory_index') }}?stock_status=low_stock" class="small-box-footer" style="background: rgba(139,69,19,0.1); color: #8b4513;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-4 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%); color: white;">
            <div class="inner">
                <h3>{{ $inventoryStats['out_of_stock_count'] }}</h3>
                <p>Out of Stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('admin_inventory_index') }}?stock_status=out_of_stock" class="small-box-footer" style="background: rgba(255,255,255,0.1); color: white;">
                View <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Low Stock Products Table -->
@if($lowStockProducts->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Low Stock Products
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Low Stock Threshold</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->getFeaturedImageUrl())
                                            <img src="{{ $product->getFeaturedImageUrl() }}" alt="{{ $product->title }}" 
                                                 class="img-thumbnail mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $product->title }}</strong>
                                            <br><small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-warning">{{ $product->inventory->current_stock }}</span>
                                </td>
                                <td>{{ $product->inventory->low_stock_threshold }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin_inventory_show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Out of Stock Products Table -->
@if($outOfStockProducts->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-times-circle text-danger"></i> Out of Stock Products
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outOfStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->getFeaturedImageUrl())
                                            <img src="{{ $product->getFeaturedImageUrl() }}" alt="{{ $product->title }}" 
                                                 class="img-thumbnail mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $product->title }}</strong>
                                            <br><small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-danger">{{ $product->inventory->current_stock }}</span>
                                </td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin_inventory_show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions & Additional Info -->
<div class="row">
    <div class="col-lg-6">
        <div class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="card-header" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); color: white; border: none;">
                <h3 class="card-title" style="color: white;">
                    <i class="fas fa-tags"></i> Categories & Brands
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%); color: white; border-radius: 8px; padding: 15px;">
                            <span class="info-box-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="color: white;">Categories</span>
                                <span class="info-box-number" style="color: white;">{{ $totalProductCat }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); color: white; border-radius: 8px; padding: 15px;">
                            <span class="info-box-icon" style="background: rgba(255,255,255,0.2); color: white;"><i class="fas fa-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" style="color: white;">Brands</span>
                                <span class="info-box-number" style="color: white;">{{ $totalProductBrand }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin_product_category_index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%); color: white; border: none; margin-right: 10px;">
                        <i class="fas fa-list"></i> Manage Categories
                    </a>
                    <a href="{{ route('admin_product_brand_index') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%); color: white; border: none;">
                        <i class="fas fa-tag"></i> Manage Brands
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="card-header" style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%); color: white; border: none;">
                <h3 class="card-title" style="color: white;">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-2">
                        <a href="{{ route('admin_product_create') }}" class="btn btn-block" style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%); color: white; border: none;">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="{{ route('admin_inventory_index') }}" class="btn btn-block" style="background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); color: white; border: none;">
                            <i class="fas fa-boxes"></i> Manage Inventory
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="{{ route('admin_product_order_index') }}" class="btn btn-block" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); color: white; border: none;">
                            <i class="fas fa-shopping-bag"></i> View Orders
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="{{ route('admin_statistics_index') }}" class="btn btn-block" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%); color: white; border: none;">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection