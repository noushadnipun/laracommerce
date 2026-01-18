@extends('admin.layouts.master')

@section('site-title')
Product Statistics
@endsection

@section('page-title')
Product Statistics Dashboard
@endsection

@section('page-content')

<!-- Real-time Status Bar -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-sync-alt fa-spin"></i>
                <strong>Live Statistics</strong> - Data updates every 30 seconds
            </div>
            <div>
                <span class="badge badge-success" id="lastUpdate">Last updated: {{now()->format('H:i:s')}}</span>
                <button class="btn btn-sm btn-outline-primary ml-2" onclick="refreshStats()">
                    <i class="fas fa-sync-alt"></i> Refresh Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_views'] ?? 0}}</h4>
                        <p class="mb-0">Total Views</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_orders'] ?? 0}}</h4>
                        <p class="mb-0">Total Orders</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">৳{{number_format($overallStats['total_revenue'] ?? 0, 2)}}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_units_sold'] ?? 0}}</h4>
                        <p class="mb-0">Units Sold</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">৳{{number_format($overallStats['average_order_value'] ?? 0, 2)}}</h4>
                        <p class="mb-0">Avg Order Value</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_wishlist_adds'] ?? 0}}</h4>
                        <p class="mb-0">Wishlist Adds</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-heart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter"></i> Filter Statistics
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{route('admin_statistics_index')}}" id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_from">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{request('date_from')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="{{request('date_to')}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{request('category_id') == $category->id ? 'selected' : ''}}>
                                            {{$category->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}" {{request('brand_id') == $brand->id ? 'selected' : ''}}>
                                            {{$brand->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary mr-1">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{route('admin_statistics_index')}}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-line"></i> Performance Over Time
                </h6>
            </div>
            <div class="card-body py-2">
                <canvas id="viewsChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Sales by Category
                </h6>
            </div>
            <div class="card-body py-2">
                <canvas id="salesChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-pie"></i> Revenue Distribution
                </h6>
            </div>
            <div class="card-body py-2">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-2">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-radar"></i> Top Products Performance
                </h6>
            </div>
            <div class="card-body py-2">
                <canvas id="topProductsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Sell Report Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Comprehensive Sell Report
                    </h3>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-light btn-sm" onclick="exportSellReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                        <button type="button" class="btn btn-light btn-sm" onclick="printSellReport()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Sales Analytics Tabs -->
                <ul class="nav nav-tabs" id="sellReportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="recent-sales-tab" data-toggle="tab" href="#recent-sales" role="tab">
                            <i class="fas fa-clock"></i> Recent Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-methods-tab" data-toggle="tab" href="#payment-methods" role="tab">
                            <i class="fas fa-credit-card"></i> Payment Methods
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order-status-tab" data-toggle="tab" href="#order-status" role="tab">
                            <i class="fas fa-tasks"></i> Order Status
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="top-products-tab" data-toggle="tab" href="#top-products" role="tab">
                            <i class="fas fa-trophy"></i> Top Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customer-analytics-tab" data-toggle="tab" href="#customer-analytics" role="tab">
                            <i class="fas fa-users"></i> Customer Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-interactions-tab" data-toggle="tab" href="#product-interactions" role="tab">
                            <i class="fas fa-mouse-pointer"></i> Product Interactions
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content mt-3" id="sellReportTabContent">
                    <!-- Recent Sales Tab -->
                    <div class="tab-pane fade show active" id="recent-sales" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin_product_order_view', $sale->id)}}" class="text-primary">
                                                {{$sale->order_code}}
                                            </a>
                                        </td>
                                        <td>{{$sale->customer_name}}</td>
                                        <td>{{ is_string($sale->created_at) ? \Carbon\Carbon::parse($sale->created_at)->format('M d, Y H:i') : $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td><strong>৳{{number_format($sale->final_amount, 2)}}</strong></td>
                                        <td>
                                            <span class="badge badge-{{$sale->payment_status == 'Paid' ? 'success' : ($sale->payment_status == 'Pending' ? 'warning' : 'danger')}}">
                                                {{$sale->payment_status}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{$sale->order_status == 'delivered' ? 'success' : ($sale->order_status == 'processing' ? 'primary' : 'secondary')}}">
                                                {{$sale->order_status}}
                                            </span>
                                        </td>
                                        <td>{{$sale->payment_type ?? 'N/A'}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #ddd;"></i>
                                            <h5 class="mt-2">No sales data available</h5>
                                            <p class="text-muted">Sales data will appear here as orders are placed.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Payment Methods Tab -->
                    <div class="tab-pane fade" id="payment-methods" role="tabpanel">
                        <div class="row">
                            @foreach($salesAnalytics['payment_methods'] as $method)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>{{$method->payment_type ?? 'Unknown'}}</h5>
                                        <h3 class="text-primary">{{$method->count}}</h3>
                                        <p class="text-muted">Orders</p>
                                        <h4 class="text-success">৳{{number_format($method->revenue, 2)}}</h4>
                                        <small class="text-muted">Revenue</small>
                </div>
            </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Order Status Tab -->
                    <div class="tab-pane fade" id="order-status" role="tabpanel">
                        <div class="row">
                            @foreach($salesAnalytics['order_statuses'] as $status)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="text-capitalize">{{$status->order_status}}</h5>
                                        <h3 class="text-primary">{{$status->count}}</h3>
                                        <p class="text-muted">Orders</p>
                                        <h4 class="text-success">৳{{number_format($status->revenue, 2)}}</h4>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Top Products Tab -->
                    <div class="tab-pane fade" id="top-products" role="tabpanel">
                <div class="table-responsive">
                            <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Rank</th>
                                <th>Product</th>
                                        <th>Units Sold</th>
                                <th>Revenue</th>
                                        <th>Avg Price</th>
                            </tr>
                        </thead>
                        <tbody>
                                    @foreach($salesAnalytics['top_selling_products'] as $index => $product)
                                <tr>
                                    <td>
                                            <span class="badge badge-{{$index < 3 ? 'warning' : 'light'}}">
                                                #{{$index + 1}}
                                            </span>
                                    </td>
                                        <td>{{$product->title}}</td>
                                        <td><span class="badge badge-primary">{{$product->units_sold}}</span></td>
                                        <td><strong>৳{{number_format($product->revenue, 2)}}</strong></td>
                                        <td>৳{{number_format($product->revenue / $product->units_sold, 2)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Customer Analytics Tab -->
                    <div class="tab-pane fade" id="customer-analytics" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$salesAnalytics['customer_stats']->unique_customers ?? 0}}</h3>
                                        <p class="mb-0">Unique Customers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3>৳{{number_format($salesAnalytics['customer_stats']->avg_order_value ?? 0, 2)}}</h3>
                                        <p class="mb-0">Avg Order Value</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$salesAnalytics['customer_stats']->total_orders ?? 0}}</h3>
                                        <p class="mb-0">Total Orders</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Interactions Tab -->
                    <div class="tab-pane fade" id="product-interactions" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$overallStats['total_views'] ?? 0}}</h3>
                                        <p class="mb-0">Total Views</p>
                                        <small>Product page visits</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$overallStats['total_clicks'] ?? 0}}</h3>
                                        <p class="mb-0">Total Clicks</p>
                                        <small>Product interactions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$overallStats['total_cart_adds'] ?? 0}}</h3>
                                        <p class="mb-0">Cart Adds</p>
                                        <small>Add to cart actions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <h3>{{$overallStats['total_wishlist_adds'] ?? 0}}</h3>
                                        <p class="mb-0">Wishlist Adds</p>
                                        <small>Wishlist actions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Top Interacted Products -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-eye"></i> Most Viewed Products
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Views</th>
                                                        <th>Clicks</th>
                                                        <th>Conversion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topProducts as $index => $product)
                                                    <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                                                <img src="{{$product->product->getFeaturedImageUrl()}}" 
                                                                     alt="{{$product->product->title}}" 
                                                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" 
                                                 onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                                            <div class="ml-2">
                                                <strong>{{$product->product->title}}</strong>
                                                <br>
                                                <small class="text-muted">{{$product->product->sku}}</small>
                                            </div>
                                        </div>
                                    </td>
                                                        <td><span class="badge badge-primary">{{$product->views ?? 0}}</span></td>
                                                        <td><span class="badge badge-info">{{$product->clicks ?? 0}}</span></td>
                                                        <td>
                                                            @php
                                                                $conversionRate = $product->views > 0 ? (($product->total_sales ?? 0) / $product->views) * 100 : 0;
                                                            @endphp
                                                            <span class="badge badge-{{$conversionRate > 5 ? 'success' : ($conversionRate > 2 ? 'warning' : 'secondary')}}">
                                                                {{number_format($conversionRate, 1)}}%
                                                            </span>
                                    </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-shopping-cart"></i> Most Added to Cart
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Cart Adds</th>
                                                        <th>Wishlist</th>
                                                        <th>Sales</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topProducts as $index => $product)
                                                    <tr>
                                                        <td>
                                            <div class="d-flex align-items-center">
                                                                <img src="{{$product->product->getFeaturedImageUrl()}}" 
                                                                     alt="{{$product->product->title}}" 
                                                                     style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" 
                                                                     onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                                                                <div class="ml-2">
                                                                    <strong>{{$product->product->title}}</strong>
                                                                    <br>
                                                                    <small class="text-muted">{{$product->product->sku}}</small>
                                            </div>
                                                            </div>
                                    </td>
                                                        <td><span class="badge badge-warning">{{$product->cart_adds ?? 0}}</span></td>
                                                        <td><span class="badge badge-secondary">{{$product->wishlist_adds ?? 0}}</span></td>
                                                        <td><span class="badge badge-success">{{$product->total_sales ?? 0}}</span></td>
                                </tr>
                                                    @endforeach
                        </tbody>
                    </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interaction Analytics -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-chart-line"></i> Interaction Analytics
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{$overallStats['total_views'] > 0 ? number_format(($overallStats['total_clicks'] / $overallStats['total_views']) * 100, 1) : 0}}%</h4>
                                                    <p class="text-muted">Click-through Rate</p>
                                                    <small>Views to Clicks conversion</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-warning">{{$overallStats['total_views'] > 0 ? number_format(($overallStats['total_cart_adds'] / $overallStats['total_views']) * 100, 1) : 0}}%</h4>
                                                    <p class="text-muted">Cart Add Rate</p>
                                                    <small>Views to Cart conversion</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-success">{{$overallStats['total_views'] > 0 ? number_format(($overallStats['total_units_sold'] / $overallStats['total_views']) * 100, 1) : 0}}%</h4>
                                                    <p class="text-muted">Purchase Rate</p>
                                                    <small>Views to Sales conversion</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('cusjs')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Compact chart styling */
.card-header.py-2 {
    padding: 0.5rem 1rem !important;
}

.card-body.py-2 {
    padding: 0.5rem 1rem !important;
}

.card-title {
    font-size: 0.9rem !important;
    font-weight: 600 !important;
}

/* Chart container optimization */
canvas {
    max-height: 200px !important;
}

/* Responsive chart adjustments */
@media (max-width: 768px) {
    .card-body.py-2 {
        padding: 0.25rem 0.5rem !important;
    }
    
    canvas {
        max-height: 150px !important;
    }
}
</style>

<script>
// Auto-dismiss alerts
setTimeout(function() {
    $('.alert').fadeOut();
}, 5000);

// Set default date range (last 30 days)
$(document).ready(function() {
    if (!$('#date_from').val()) {
        var date = new Date();
        date.setDate(date.getDate() - 30);
        $('#date_from').val(date.toISOString().split('T')[0]);
    }
    
    if (!$('#date_to').val()) {
        var today = new Date();
        $('#date_to').val(today.toISOString().split('T')[0]);
    }
    
    // Initialize charts
    initializeCharts();
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Auto-submit form on filter change
    $('#category_id, #brand_id').change(function() {
        $('#filterForm').submit();
    });
});

// Initialize all charts
function initializeCharts() {
    // Views over time chart
    initViewsChart();
    
    // Sales comparison chart
    initSalesChart();
    
    // Revenue distribution chart
    initRevenueChart();
    
    // Top products performance chart
    initTopProductsChart();
}

// Views over time chart
function initViewsChart() {
    const ctx = document.getElementById('viewsChart').getContext('2d');
    window.viewsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['dates'] ?? []) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode($chartData['views'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }, {
                label: 'Clicks',
                data: {!! json_encode($chartData['clicks'] ?? []) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: false
            }, {
                label: 'Sales',
                data: {!! json_encode($chartData['sales'] ?? []) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 8
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    radius: 3
                }
            }
        }
    });
}

// Sales comparison chart
function initSalesChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    window.salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['categories'] ?? []) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($chartData['category_sales'] ?? []) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 8
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Revenue distribution chart
function initRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    window.revenueChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Online Sales', 'COD Sales', 'Pending Sales', 'Other Sales'],
            datasets: [{
                data: {!! json_encode($chartData['revenue_distribution'] ?? [0, 0, 0, 0]) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 8
                    }
                }
            }
        }
    });
}

// Top products performance chart
function initTopProductsChart() {
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    window.topProductsChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: {!! json_encode($chartData['top_products_labels'] ?? []) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode($chartData['top_products_views'] ?? []) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)'
            }, {
                label: 'Sales',
                data: {!! json_encode($chartData['top_products_sales'] ?? []) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 8
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    grid: {
                        display: true
                    }
                }
            }
        }
    });
}

// Real-time updates functionality
function initializeRealTimeUpdates() {
    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshStats();
    }, 30000);
    
    // Update last update time every second
    setInterval(function() {
        updateLastUpdateTime();
    }, 1000);
}

// Refresh statistics data
function refreshStats() {
    $.ajax({
        url: '{{route("admin_statistics_api")}}',
        method: 'GET',
        data: {
            date_from: $('#date_from').val(),
            date_to: $('#date_to').val(),
            category_id: $('#category_id').val(),
            brand_id: $('#brand_id').val()
        },
        success: function(response) {
            if (response.success) {
                // Update overview cards
                updateOverviewCards(response.data.overallStats);
                
                // Update charts
                updateCharts(response.data.chartData);
                
                // Update last update time
                updateLastUpdateTime();
                
                // Show success notification
                showNotification('Statistics updated successfully!', 'success');
            }
        },
        error: function() {
            showNotification('Failed to update statistics', 'error');
        }
    });
}

// Update overview cards
function updateOverviewCards(stats) {
    $('.card.bg-primary .card-body h4').text(stats.total_views || 0);
    $('.card.bg-success .card-body h4').text(stats.total_sales || 0);
    $('.card.bg-info .card-body h4').text('$' + (stats.total_revenue || 0).toFixed(2));
    $('.card.bg-warning .card-body h4').text((stats.average_rating || 0).toFixed(1) + '/5');
}

// Update charts with new data
function updateCharts(chartData) {
    // Destroy existing charts
    if (window.viewsChart) {
        window.viewsChart.destroy();
    }
    if (window.salesChart) {
        window.salesChart.destroy();
    }
    if (window.revenueChart) {
        window.revenueChart.destroy();
    }
    if (window.topProductsChart) {
        window.topProductsChart.destroy();
    }
    
    // Reinitialize charts with new data
    initializeCharts();
}

// Update last update time
function updateLastUpdateTime() {
    var now = new Date();
    var timeString = now.toLocaleTimeString();
    $('#lastUpdate').text('Last updated: ' + timeString);
}

// Show notification
function showNotification(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var notification = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
        '<strong>' + (type === 'success' ? 'Success!' : 'Error!') + '</strong> ' + message +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';
    
    $('body').append(notification);
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
}

// Export sell report
function exportSellReport() {
    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var categoryId = $('#category_id').val();
    var brandId = $('#brand_id').val();
    
    var url = '{{route("admin_statistics_export")}}?' + 
              'date_from=' + dateFrom + 
              '&date_to=' + dateTo + 
              '&category_id=' + categoryId + 
              '&brand_id=' + brandId + 
              '&type=sell_report';
    
    window.open(url, '_blank');
    showNotification('Sell report export started!', 'success');
}

// Print sell report
function printSellReport() {
    var printContent = document.getElementById('sellReportTabContent').innerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = '<h2>Sell Report - {{$dateFrom}} to {{$dateTo}}</h2>' + printContent;
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reload the page to restore functionality
    location.reload();
}

// Smart insights and recommendations
function generateSmartInsights() {
    var insights = [];
    
    // Check for low performing products
    var lowPerformers = $('.badge.badge-light').length;
    if (lowPerformers > 5) {
        insights.push({
            type: 'warning',
            title: 'Low Performance Alert',
            message: lowPerformers + ' products have low performance metrics. Consider reviewing their pricing or marketing strategy.'
        });
    }
    
    // Check for high conversion rates
    var highConversion = $('.conversion-rate').filter(function() {
        return parseFloat($(this).text()) > 5;
    }).length;
    
    if (highConversion > 0) {
        insights.push({
            type: 'success',
            title: 'High Conversion Products',
            message: highConversion + ' products have conversion rates above 5%. Consider promoting these products more.'
        });
    }
    
    // Check for payment method trends
    var sslOrders = $('.payment-method').filter(function() {
        return $(this).text().includes('SSL');
    }).length;
    
    var totalOrders = $('.payment-method').length;
    var sslPercentage = (sslOrders / totalOrders) * 100;
    
    if (sslPercentage > 70) {
        insights.push({
            type: 'info',
            title: 'Payment Method Trend',
            message: sslPercentage.toFixed(1) + '% of orders use SSL Commerz. Consider optimizing the online payment experience.'
        });
    }
    
    return insights;
}

// Display smart insights
function displaySmartInsights() {
    var insights = generateSmartInsights();
    
    if (insights.length > 0) {
        var insightsHtml = '<div class="alert alert-info mt-3"><h5><i class="fas fa-lightbulb"></i> Smart Insights</h5><ul>';
        
        insights.forEach(function(insight) {
            insightsHtml += '<li class="text-' + insight.type + '"><strong>' + insight.title + ':</strong> ' + insight.message + '</li>';
        });
        
        insightsHtml += '</ul></div>';
        
        $('#sellReportTabContent').prepend(insightsHtml);
    }
}

// Initialize smart features
$(document).ready(function() {
    // Display smart insights after a delay
    setTimeout(displaySmartInsights, 2000);
    
    // Add real-time updates for sell report
    setInterval(function() {
        updateSellReportData();
    }, 60000); // Update every minute
});

// Update sell report data
function updateSellReportData() {
    $.ajax({
        url: '{{route("admin_statistics_api")}}',
        method: 'GET',
        data: {
            date_from: $('#date_from').val(),
            date_to: $('#date_to').val(),
            category_id: $('#category_id').val(),
            brand_id: $('#brand_id').val()
        },
        success: function(response) {
            if (response.success) {
                // Update recent sales count
                var recentSalesCount = response.data.recentSales ? response.data.recentSales.length : 0;
                $('#recent-sales-tab').html('<i class="fas fa-clock"></i> Recent Sales (' + recentSalesCount + ')');
                
                showNotification('Sell report data updated!', 'success');
            }
        },
        error: function() {
            console.log('Failed to update sell report data');
        }
    });
}
</script>
@endsection
