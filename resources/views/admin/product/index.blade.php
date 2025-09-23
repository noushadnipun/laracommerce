@extends('admin.layouts.master')

@section('site-title')
All Product
@endsection

@section('style')
<style>
.product-image-thumb {
    transition: transform 0.2s;
}
.product-image-thumb:hover {
    transform: scale(1.1);
}
.badge {
    font-size: 0.75em;
}
.position-absolute {
    z-index: 10;
}
#imageModal .modal-dialog {
    max-width: 80%;
}
#imageModal .modal-body {
    padding: 20px;
}
</style>
@endsection

@section('page-content')
     <div class="card">
        <div class="card-header card-info">
            <h3 class="card-title panel-title float-left"> 
                All Product {{!empty($catName) ? 'of '. $catName : ''}}
            </h3>
            <div class="float-right">
                <a href="{{ route('admin_inventory_index') }}" class="btn btn-sm btn-success mr-2">
                    <i class="fa fa-boxes"></i> Inventory Management
                </a>
                <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false">
                    <i class="fa fa-filter"></i> Filter
                    @if(request()->hasAny(['search', 'category', 'brand', 'visibility', 'price_min', 'price_max', 'stock_status', 'image_status', 'per_page']))
                        <span class="badge bg-light text-dark ml-1">{{$product->total()}}</span>
                    @endif
                </button>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="collapse" id="filterCollapse">
            <div class="card-body bg-light">
                <form method="GET" action="{{route('admin_product_index')}}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search">Search Product</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{request('search')}}" placeholder="Search by title, code...">
                        </div>
                        <div class="col-md-2">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\ProductCategory::all() as $category)
                                    <option value="{{$category->id}}" {{request('category') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="brand">Brand</label>
                            <select name="brand" id="brand" class="form-control">
                                <option value="">All Brands</option>
                                @foreach(\App\Models\ProductBrand::all() as $brand)
                                    <option value="{{$brand->id}}" {{request('brand') == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="visibility">Visibility</label>
                            <select name="visibility" id="visibility" class="form-control">
                                <option value="">All</option>
                                <option value="1" {{request('visibility') == '1' ? 'selected' : ''}}>Public</option>
                                <option value="0" {{request('visibility') == '0' ? 'selected' : ''}}>Private</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="image_status">Image Status</label>
                            <select name="image_status" id="image_status" class="form-control">
                                <option value="">All</option>
                                <option value="has_images" {{request('image_status') == 'has_images' ? 'selected' : ''}}>Has Images</option>
                                <option value="no_images" {{request('image_status') == 'no_images' ? 'selected' : ''}}>No Images</option>
                                <option value="remote_only" {{request('image_status') == 'remote_only' ? 'selected' : ''}}>Remote Only</option>
                                <option value="local_only" {{request('image_status') == 'local_only' ? 'selected' : ''}}>Local Only</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="price_min">Min Price</label>
                            <input type="number" name="price_min" id="price_min" class="form-control" value="{{request('price_min')}}" placeholder="Min price">
                        </div>
                        <div class="col-md-3">
                            <label for="price_max">Max Price</label>
                            <input type="number" name="price_max" id="price_max" class="form-control" value="{{request('price_max')}}" placeholder="Max price">
                        </div>
                        <div class="col-md-3">
                            <label for="stock_status">Stock Status</label>
                            <select name="stock_status" id="stock_status" class="form-control">
                                <option value="">All</option>
                                <option value="in_stock" {{request('stock_status') == 'in_stock' ? 'selected' : ''}}>In Stock</option>
                                <option value="out_of_stock" {{request('stock_status') == 'out_of_stock' ? 'selected' : ''}}>Out of Stock</option>
                                <option value="low_stock" {{request('stock_status') == 'low_stock' ? 'selected' : ''}}>Low Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="per_page">Show Per Page</label>
                            <select name="per_page" id="per_page" class="form-control">
                                <option value="10" {{request('per_page') == '10' ? 'selected' : ''}}>10 Products</option>
                                <option value="15" {{request('per_page') == '15' ? 'selected' : ''}}>15 Products</option>
                                <option value="25" {{request('per_page') == '25' ? 'selected' : ''}}>25 Products</option>
                                <option value="50" {{request('per_page') == '50' ? 'selected' : ''}}>50 Products</option>
                                <option value="100" {{request('per_page') == '100' ? 'selected' : ''}}>100 Products</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 text-right">
                            <a href="{{route('admin_product_index')}}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-refresh"></i> Clear All Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if(request()->hasAny(['search', 'category', 'brand', 'visibility', 'price_min', 'price_max', 'stock_status', 'image_status', 'per_page']))
                <div class="alert alert-info mb-3">
                    <strong>Filter Results:</strong> {{$product->total()}} products found
                    @if(request('per_page'))
                        <span class="badge bg-primary ml-2">Showing {{request('per_page')}} per page</span>
                    @endif
                    <a href="{{route('admin_product_index')}}" class="btn btn-sm btn-secondary float-right">
                        <i class="fa fa-times"></i> Clear Filters
                    </a>
                </div>
            @endif
            <table class="table table-head-fixed table-hover">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Image</th>
                    <th>Title & Details</th>
                    <th>Price</th>
                    <th>Date & Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($product as $key => $data)
                <tr>
                    <td class="align-middle">{{$key + $product->firstItem()}}</td>
                    <td class="align-middle">
                        <div class="position-relative">
                            <img style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" class="img-fluid rounded product-image-thumb" 
                            src="{{$data->getFeaturedImageUrl()}}" alt="{{$data->title}}" data-bs-toggle="tooltip" title="Click to view large image" 
                            data-title="{{$data->title}}" onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                            @if($data->remote_images && is_array($data->remote_images) && count($data->remote_images) > 0)
                                <span class="badge bg-info position-absolute" style="top: -5px; right: -5px; font-size: 0.6em; padding: 2px 4px;" title="Remote Images ({{count($data->remote_images)}})">R</span>
                            @elseif($data->product_image && count($data->product_image) > 0)
                                <span class="badge bg-success position-absolute" style="top: -5px; right: -5px; font-size: 0.6em; padding: 2px 4px;" title="Local Images ({{count($data->product_image)}})">L</span>
                            @else
                                <span class="badge bg-warning text-dark position-absolute" style="top: -5px; right: -5px; font-size: 0.6em; padding: 2px 4px;" title="No Images Available">N</span>
                            @endif
                        </div>
                    </td>
                    <td class="align-middle">
                      <a target="_blank" class="text-primary" href="{{route('frontend_single_product', $data->slug)}}"> {{$data->title}} </a>
                      <br>
                      <small class="text-muted">
                          @if($data->category)
                              Category: {{$data->category->name}}
                          @endif
                          @if($data->brand)
                              | Brand: {{$data->brand->name}}
                          @endif
                          <br>
                          @php $allImages = $data->getAllImages(); @endphp
                          @if(count($allImages) > 0)
                              <span class="badge bg-light text-dark">{{count($allImages)}} image(s)</span>
                              @if($data->remote_images && is_array($data->remote_images) && count($data->remote_images) > 0)
                                  <span class="badge bg-info" style="font-size: 0.7em; padding: 1px 3px;">{{count($data->remote_images)}} remote</span>
                              @endif
                              @if($data->product_image && count($data->product_image) > 0)
                                  <span class="badge bg-success" style="font-size: 0.7em; padding: 1px 3px;">{{count($data->product_image)}} local</span>
                              @endif
                          @else
                              <span class="badge bg-warning text-dark">No images</span>
                              <span class="badge bg-secondary" style="font-size: 0.7em; padding: 1px 3px;">No Preview</span>
                          @endif
                      </small>
                    </td>
                    <td class="align-middle">
                    <small class="fw-bold">
                        PP : {{$data->purchase_price}} <br>
                        RP : {{$data->regular_price}} <br>
                        SP : {{$data->sale_price}} <br><br>
                    </small>  
                    </td>
                    <td class="align-middle">
                        <small>
                            @if($data->visibility == 1)
                            <span class="badge bg-success">Public</span>
                            @else 
                            <span class="badge bg-warning text-dark">Private</span>
                            @endif
                        </small> <br/>
                        {{$data->created_at}}
                    </td>
                    <td class="align-middle">
                        <a href="{{route('admin_product_edit', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-edit"></i></a>   
                        <a href="{{route('admin_product_delete', $data->id)}}" class="btn-sm alert-danger" onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <div class="float-left">
                <small class="text-muted">
                    Showing {{$product->firstItem()}} to {{$product->lastItem()}} of {{$product->total()}} products
                    @if(request('per_page'))
                        <span class="badge bg-info ml-2">{{request('per_page')}} per page</span>
                    @endif
                </small>
            </div>
            <div class="float-right">
                {{$product->appends(request()->query())->links('pagination::bootstrap-5')}}
            </div>
        </div>
     </div>

     <!-- Image Modal - Bootstrap 5 -->
     <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#imageModal').modal('hide')"></button>
                 </div>
                 <div class="modal-body text-center">
                     <div id="imageLoading" class="text-center" style="display: none; padding: 50px;">
                         <div class="spinner-border text-primary" role="status">
                             <span class="visually-hidden">Loading...</span>
                         </div>
                         <p class="mt-2 text-muted">Loading image...</p>
                     </div>
                     <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" 
                     onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                 </div>
             </div>
         </div>
     </div>

@endsection




@section('cusjs')
<script>
$(document).ready(function() {
    // Image modal functionality
    $('.product-image-thumb').on('click', function() {
        var imageSrc = $(this).attr('src');
        var imageTitle = $(this).attr('data-title');
        
        // Show loading
        $('#imageLoading').show();
        $('#modalImage').hide();
        
        // Set image source and title
        $('#modalImage').attr('src', imageSrc);
        $('#modalImage').attr('alt', imageTitle);
        $('#imageModalLabel').text(imageTitle || 'Product Image');
        
        // Hide loading and show image when loaded
        $('#modalImage').on('load', function() {
            $('#imageLoading').hide();
            $('#modalImage').show();
        });
        
        // Show modal
        $('#imageModal').modal('show');
    });
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection


