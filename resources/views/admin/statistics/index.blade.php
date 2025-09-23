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
    <div class="col-lg-3 col-md-6">
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
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_sales'] ?? 0}}</h4>
                        <p class="mb-0">Total Sales</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{number_format($overallStats['total_revenue'] ?? 0, 2)}}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$overallStats['total_wishlists'] ?? 0}}</h4>
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
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line"></i> Performance Over Time
                </h5>
            </div>
            <div class="card-body">
                <canvas id="viewsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Sales by Category
                </h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie"></i> Revenue Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-radar"></i> Top Products Performance
                </h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-trophy"></i> Top Performing Products
                    </h3>
                    <div class="btn-group btn-group-sm">
                        <a href="{{route('admin_statistics_export')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-download"></i> Export
                        </a>
                        <button type="button" class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('admin_statistics_export', ['type' => 'monthly'])}}">
                                <i class="fas fa-calendar-alt"></i> Monthly Report
                            </a>
                            <a class="dropdown-item" href="{{route('admin_statistics_export', ['type' => 'yearly'])}}">
                                <i class="fas fa-calendar"></i> Yearly Report
                            </a>
                            <a class="dropdown-item" href="{{route('admin_statistics_export', ['type' => 'trends'])}}">
                                <i class="fas fa-chart-line"></i> Trends Analysis
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('admin_statistics_export', ['type' => 'detailed'])}}">
                                <i class="fas fa-file-alt"></i> Detailed Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Rank</th>
                                <th>Product</th>
                                <th>Views</th>
                                <th>Clicks</th>
                                <th>Cart Adds</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $product)
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge badge-{{$index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'success')}}">
                                                #{{$index + 1}}
                                            </span>
                                        @else
                                            <span class="badge badge-light">#{{$index + 1}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{$product->product->getFeaturedImageUrl()}}" alt="{{$product->product->title}}" 
                                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" 
                                                 onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                                            <div class="ml-2">
                                                <strong>{{$product->product->title}}</strong>
                                                <br>
                                                <small class="text-muted">{{$product->product->sku}}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{$product->views ?? 0}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{$product->clicks ?? 0}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{$product->cart_adds ?? 0}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{$product->total_sales ?? 0}}</span>
                                    </td>
                                    <td>
                                        <strong>${{number_format($product->total_revenue ?? 0, 2)}}</strong>
                                    </td>
                                    <td>
                                        @if($product->average_rating && $product->average_rating > 0)
                                            <div class="d-flex align-items-center">
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{$i <= $product->average_rating ? '' : '-o'}}"></i>
                                                    @endfor
                                                </span>
                                                <small class="ml-1">({{number_format($product->average_rating, 1)}})</small>
                                            </div>
                                        @else
                                            <span class="text-muted">No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin_statistics_product', $product->id)}}" 
                                           class="btn btn-outline-primary btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-chart-line" style="font-size: 48px; color: #ddd;"></i>
                                        <h5 class="mt-2">No statistics available</h5>
                                        <p class="text-muted">Product statistics will appear here as customers interact with your products.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('cusjs')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            plugins: {
                title: {
                    display: true,
                    text: 'Performance Over Time'
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Count'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
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
            plugins: {
                title: {
                    display: true,
                    text: 'Sales by Category'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
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
            labels: ['Direct Sales', 'Online Sales', 'Wholesale', 'Other'],
            datasets: [{
                data: {!! json_encode($chartData['revenue_distribution'] ?? [100, 0, 0, 0]) !!},
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
            plugins: {
                title: {
                    display: true,
                    text: 'Revenue Distribution'
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
            plugins: {
                title: {
                    display: true,
                    text: 'Top Products Performance'
                }
            },
            scales: {
                r: {
                    beginAtZero: true
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
</script>
@endsection
