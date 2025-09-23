@extends('frontend.layouts.master')

@section('title', 'Compare Products')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page_header">
                <div class="page_title">
                    <h1><i class="fa fa-balance-scale"></i> Compare Products</h1>
                    <p>Compare your selected products side by side</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="compare_content">
                @if(isset($products) && $products->count() > 0)
                    <div class="compare_products">
                        <div class="compare_table_wrapper">
                            <table class="compare_table">
                                <thead>
                                    <tr>
                                        <th class="feature_column">Features</th>
                                        @foreach($products as $product)
                                            @if($product && $product->slug)
                                                <th class="product_column">
                                                    <div class="product_header">
                                                        <div class="product_image">
                                                            <img src="{{ $product->getFeaturedImageUrl() }}" 
                                                                 alt="{{ $product->title }}"
                                                                 onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'">
                                                        </div>
                                                        <h3 class="product_name">{{ $product->title }}</h3>
                                                        <div class="product_price">
                                                            @if($product->sale_price && $product->sale_price < $product->regular_price)
                                                                <span class="old_price">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                                                                <span class="current_price">৳{{ number_format($product->sale_price / 100, 2) }}</span>
                                                            @else
                                                                <span class="current_price">৳{{ number_format($product->regular_price / 100, 2) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="product_actions">
                                                            <div class="add-to-cart-wrapper">
                                                                <?php echo \App\Helpers\Frontend\ProductView::addToCartButton($product); ?>
                                                            </div>
                                                            <a href="javascript:void(0)" class="remove_compare" data-product-id="{{ $product->id }}" title="Remove from compare">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="feature_name">Brand</td>
                                        @foreach($products as $product)
                                            @if($product && $product->slug)
                                                <td>{{ $product->brand ? $product->brand->name : 'N/A' }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="feature_name">Category</td>
                                        @foreach($products as $product)
                                            @if($product && $product->slug)
                                                <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="feature_name">Stock Status</td>
                                        @foreach($products as $product)
                                            @if($product && $product->slug)
                                                <td>
                                                    @if($product->current_stock > 0)
                                                        <span class="in_stock">In Stock ({{ $product->current_stock }})</span>
                                                    @else
                                                        <span class="out_of_stock">Out of Stock</span>
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="feature_name">Description</td>
                                        @foreach($products as $product)
                                            @if($product && $product->slug)
                                                <td>{{ Str::limit(strip_tags($product->description), 100) }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @if($products->where('specification')->count() > 0)
                                        <tr>
                                            <td class="feature_name">Specifications</td>
                                            @foreach($products as $product)
                                                @if($product && $product->slug)
                                                    <td>{{ Str::limit(strip_tags($product->specification), 100) }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="compare_actions">
                            <a href="javascript:void(0)" class="btn btn-danger" id="clear_compare">
                                <i class="fa fa-trash"></i> Clear All
                            </a>
                            <a href="{{ route('frontend_index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                @else
                    <div class="empty_compare">
                        <div class="empty_icon">
                            <i class="fa fa-balance-scale"></i>
                        </div>
                        <h3>No products to compare</h3>
                        <p>Add products to compare them side by side</p>
                        <a href="{{ route('frontend_index') }}" class="btn btn-primary">Start Comparing</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.page_header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    margin-bottom: 40px;
}

.page_title h1 {
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.page_title h1 i {
    color: #28a745;
    margin-right: 10px;
}

.page_title p {
    font-size: 16px;
    color: #6c757d;
    margin: 0;
}

.compare_table_wrapper {
    overflow-x: auto;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    background: white;
}

.compare_table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.compare_table th,
.compare_table td {
    padding: 20px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
    vertical-align: top;
}

.feature_column {
    background: #f8f9fa;
    font-weight: 700;
    color: #2c3e50;
    width: 200px;
    min-width: 200px;
}

.product_column {
    width: 250px;
    min-width: 250px;
    text-align: center;
}

.product_header {
    padding: 0;
}

.product_image {
    margin-bottom: 15px;
}

.product_image img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product_name {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
    line-height: 1.4;
}

.product_price {
    margin-bottom: 15px;
}

.old_price {
    font-size: 14px;
    color: #6c757d;
    text-decoration: line-through;
    margin-right: 8px;
}

.current_price {
    font-size: 18px;
    font-weight: 700;
    color: #28a745;
}

.product_actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}

.product_actions .btn {
    width: 100%;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.remove_compare {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.remove_compare:hover {
    background: #c0392b;
    transform: scale(1.1);
}

.feature_name {
    font-weight: 600;
    color: #2c3e50;
}

.in_stock {
    color: #28a745;
    font-weight: 600;
}

.out_of_stock {
    color: #e74c3c;
    font-weight: 600;
}

.compare_actions {
    text-align: center;
    padding: 30px 0;
    background: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

.compare_actions .btn {
    margin: 0 10px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.empty_compare {
    text-align: center;
    padding: 80px 20px;
}

.empty_icon {
    font-size: 80px;
    color: #e9ecef;
    margin-bottom: 20px;
}

.empty_compare h3 {
    font-size: 24px;
    color: #6c757d;
    margin-bottom: 10px;
}

.empty_compare p {
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 30px;
}

@media (max-width: 767px) {
    .page_title h1 {
        font-size: 28px;
    }
    
    .compare_table th,
    .compare_table td {
        padding: 15px 10px;
    }
    
    .feature_column {
        width: 150px;
        min-width: 150px;
    }
    
    .product_column {
        width: 200px;
        min-width: 200px;
    }
    
    .product_image img {
        width: 80px;
        height: 80px;
    }
    
    .product_name {
        font-size: 14px;
    }
    
    .compare_actions .btn {
        margin: 5px;
        display: block;
        width: 100%;
    }
}
</style>

<script>
$(document).ready(function() {
    // Remove from compare
    $('.remove_compare').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var $column = $(this).closest('th');
        
        if(confirm('Are you sure you want to remove this product from comparison?')) {
            $.ajax({
                type: 'POST',
                url: '{{ route("frontend_compare_remove") }}',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        $column.fadeOut(300, function() {
                            $(this).remove();
                        });
                                ElegantNotification.success(response.message);
                    }
                },
                error: function(xhr) {
                    ElegantNotification.error('Error removing product from comparison');
                }
            });
        }
    });
    
    // Clear all compare
    $('#clear_compare').click(function(e) {
        e.preventDefault();
        if(confirm('Are you sure you want to clear all products from comparison?')) {
            $.ajax({
                type: 'POST',
                url: '{{ route("frontend_compare_clear") }}',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                            ElegantNotification.error('Error clearing comparison list');
                }
            });
        }
    });

});
</script>
@endsection
