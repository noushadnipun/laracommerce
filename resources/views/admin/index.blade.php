@extends('admin.layouts.master')

@section('page-content')
@php
    
@endphp
<?php
$totalProduct = count(\App\Models\Product::get());
$totalProductCat = count(\App\Models\ProductCategory::get());
$totalProductBrand = count(\App\Models\ProductBrand::get());
$totalOrder = count(\App\Models\ProductOrder::get());
$totalPaymentPendingOrder = count(\App\Models\ProductOrder::where('payment_status', 'Pending')->get());


?> 
    <div class="row">

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$totalProduct}}</h3>

                    <p>Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{route('admin_product_index')}}" class="small-box-footer">
                    View <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{$totalProductCat}}</h3>

                    <p>Categories Stored</p>
                </div>
                <div class="icon">
                    <i class="fas fa-info"></i>
                </div>
                <a href="{{route('admin_product_category_index')}}" class="small-box-footer">
                    View <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{$totalProductBrand}}</h3>

                    <p>Brand Stored</p>
                </div>
                <div class="icon">
                    <i class="fas fa-info"></i>
                </div>
                <a href="{{route('admin_product_brand_index')}}" class="small-box-footer">
                    View <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

         <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{$totalOrder}}</h3>

                    <p>Total Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bag"></i>
                </div>
                <a href="{{route('admin_product_order_index')}}" class="small-box-footer">
                    View <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->


         <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$totalPaymentPendingOrder}}</h3>

                    <p>Total Pending Payment Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bag"></i>
                </div>
                <form method="get" action="{{route('admin_product_order_filter')}}" class="small-box-footer">
                    @csrf
                    <input type="hidden" name="payment_status" value="Pending" />
                        <button type="submit" class="btn btn-sm">
                        View <i class="fas fa-arrow-circle-right"></i>
                        </button>
                </form>
            </div>
        </div>
        <!-- ./col -->
    </div>
    
@endsection