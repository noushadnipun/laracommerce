@extends('admin.layouts.master')

@section('site-title')
Product Statistics
@endsection

@section('page-title')
Product Statistics - {{$product->title}}
@endsection

@section('page-content')

<!-- Product Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{$product->getFeaturedImageUrl()}}" alt="{{$product->title}}" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;" 
                             onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                    </div>
                    <div class="col-md-10">
                        <h4>{{$product->title}}</h4>
                        <p class="text-muted mb-2">
                            <strong>SKU:</strong> {{$product->sku}} | 
                            <strong>Price:</strong> ${{number_format($product->price, 2)}} |
                            <strong>Stock:</strong> {{$product->stock_quantity}}
                        </p>
                        <p class="mb-0">
                            @if($product->category)
                                <span class="badge badge-info">{{$product->category->name}}</span>
                            @endif
                            @if($product->brand)
                                <span class="badge badge-secondary">{{$product->brand->name}}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$statistics->views ?? 0}}</h4>
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
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$statistics->clicks ?? 0}}</h4>
                        <p class="mb-0">Clicks</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-mouse-pointer fa-2x"></i>
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
                        <h4 class="mb-0">{{$statistics->cart_adds ?? 0}}</h4>
                        <p class="mb-0">Cart Adds</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
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
                        <h4 class="mb-0">{{$statistics->total_sales ?? 0}}</h4>
                        <p class="mb-0">Total Sales</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$statistics->wishlist_adds ?? 0}}</h4>
                        <p class="mb-0">Wishlist Adds</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-heart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$statistics->compare_adds ?? 0}}</h4>
                        <p class="mb-0">Compare Adds</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-balance-scale fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{$statistics->shares ?? 0}}</h4>
                        <p class="mb-0">Shares</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-share-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-light text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{number_format($statistics->total_revenue ?? 0, 2)}}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Detailed Statistics
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Views</strong></td>
                                <td><span class="badge badge-primary">{{$statistics->views ?? 0}}</span></td>
                                <td>Total number of times this product was viewed</td>
                            </tr>
                            <tr>
                                <td><strong>Clicks</strong></td>
                                <td><span class="badge badge-info">{{$statistics->clicks ?? 0}}</span></td>
                                <td>Total number of clicks on this product</td>
                            </tr>
                            <tr>
                                <td><strong>Cart Adds</strong></td>
                                <td><span class="badge badge-warning">{{$statistics->cart_adds ?? 0}}</span></td>
                                <td>Total number of times added to cart</td>
                            </tr>
                            <tr>
                                <td><strong>Wishlist Adds</strong></td>
                                <td><span class="badge badge-danger">{{$statistics->wishlist_adds ?? 0}}</span></td>
                                <td>Total number of times added to wishlist</td>
                            </tr>
                            <tr>
                                <td><strong>Compare Adds</strong></td>
                                <td><span class="badge badge-secondary">{{$statistics->compare_adds ?? 0}}</span></td>
                                <td>Total number of times added to compare</td>
                            </tr>
                            <tr>
                                <td><strong>Shares</strong></td>
                                <td><span class="badge badge-dark">{{$statistics->shares ?? 0}}</span></td>
                                <td>Total number of social shares</td>
                            </tr>
                            <tr>
                                <td><strong>Total Sales</strong></td>
                                <td><span class="badge badge-success">{{$statistics->total_sales ?? 0}}</span></td>
                                <td>Total number of units sold</td>
                            </tr>
                            <tr>
                                <td><strong>Total Revenue</strong></td>
                                <td><strong>${{number_format($statistics->total_revenue ?? 0, 2)}}</strong></td>
                                <td>Total revenue generated from this product</td>
                            </tr>
                            <tr>
                                <td><strong>Average Rating</strong></td>
                                <td>
                                    @if($statistics && $statistics->average_rating > 0)
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{$i <= $statistics->average_rating ? '' : '-o'}}"></i>
                                                @endfor
                                            </span>
                                            <span class="ml-2">{{number_format($statistics->average_rating, 1)}}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">No ratings</span>
                                    @endif
                                </td>
                                <td>Average customer rating</td>
                            </tr>
                            <tr>
                                <td><strong>Last Viewed</strong></td>
                                <td>
                                    @if($statistics && $statistics->last_viewed_at)
                                        {{$statistics->last_viewed_at->format('M d, Y H:i')}}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>Last time this product was viewed</td>
                            </tr>
                            <tr>
                                <td><strong>Last Sold</strong></td>
                                <td>
                                    @if($statistics && $statistics->last_sold_at)
                                        {{$statistics->last_sold_at->format('M d, Y H:i')}}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>Last time this product was sold</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="row">
    <div class="col-12">
        <a href="{{route('admin_statistics_index')}}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Statistics
        </a>
    </div>
</div>

@endsection





