@extends('admin.layouts.master')

@section('site-title')
Add New Product
@endsection

@section('page-content')

    <form id="productForm" role="form" method="POST" action ="{{ (!empty($product)) ? route('admin_product_update') : route('admin_product_store') }}" senctype="multipart/form-data">
    @csrf
    @if(!empty($product))
        <input type="hidden" name="id" value="{{$product->id}}" />
    @endif
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">Product Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Product Title</label>
                        <input type="text" class="form-control form-control-sm" id="product_title" name="title" placeholder="Enter product title" autocomplete="off" value="{{ (!empty($product)) ? $product->title : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="slug">Product Slug</label>
                        @if(!empty($product)) 
                            <input class="slug_edit d-none" id="slug_edit" name="slug_edit" type="checkbox"> 
                            <label for="slug_edit" class=" font-weight-normal text-success slug_fa" role="button" style="font-size: 10px;"> 
                                <i class="fas fa-edit"></i>    
                            </label>
                        @endif
                        <input type="text" class="form-control form-control-sm {{ (!empty($product)) ? '' : 'product_slug_active' }}" id="product_slug" name="slug" placeholder="Enter product Slug" value="{{ (!empty($product)) ? $product->slug : '' }}" autocomplete="off" {{ (!empty($product)) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <select name="brand_id" class="form-control form-control-sm select2Brand">
                            @php $getBrand = \App\Models\ProductBrand::get() @endphp
                                <option value="">None</option>
                            @foreach($getBrand as $brand)
                                <option value="{{$brand->id}}" {{ (!empty($product)) && $product->brand_id == $brand->id ? 'selected' : '' }}>{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Product Category</label>
                        <div class="select2-dark">
                            <?php
                                global $avaiableCat;
                                $avaiableCat = (!empty($product)) ? $product->category_id : '';
                                function selectCat($parent_id = null, $sub_mark = "") {
                                    global $avaiableCat;
                                    $getCat = \App\Models\ProductCategory::where('parent_id', $parent_id)->orderBy('created_at', 'desc')->get();
                                    foreach($getCat as $row){ ?>
                                        <option value="{{$row->id}}" 
                                            <?php 
                                            if(!empty($avaiableCat)){
                                                foreach(\App\Helpers\WebsiteSettings::strToArr($avaiableCat) as $items){ echo $row->id == $items ? 'selected' : ''; } 
                                            };?>
                                            >{{$sub_mark.$row->name}} 
                                        </option>
                                        <?php selectCat($row->id, $sub_mark .'— ');
                                    }
                                }?>
                            <select name="category_id[]" class="product_category" multiple="multiple" data-placeholder="Select Category" data-dropdown-css-class="select2-dark" style="width: 100%;" autocomplete="off">
                                <?php selectCat();?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="code">Product Code</label>
                        <input type="text" class="form-control form-control-sm" id="code" name="code" value="{{ (!empty($product)) ? $product->code : '' }}" placeholder="Enter product code" autocomplete="off">
                    </div>
                    <!-- Stock management moved to Inventory Management -->
                    <div class="form-group">
                        <label>Stock Management</label>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Stock management is handled in <a href="{{ route('admin_inventory_index') }}" class="alert-link">Inventory Management</a> section.
                            <br><small>Current Stock: <strong>{{ (!empty($product) && $product->inventory) ? $product->inventory->current_stock : 'N/A' }}</strong></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="number" class="form-control form-control-sm" id="purchase_price" name="purchase_price" value="{{ (!empty($product)) ? $product->purchase_price : '' }}" placeholder="Enter product purrchase price" autocomplete="off">
                    </div>

                     <div class="form-group">
                        <label for="regular_price">Regular Price</label>
                        <input type="number" class="form-control form-control-sm" id="regular_price" name="regular_price" value="{{ (!empty($product)) ? $product->regular_price : '' }}" placeholder="Enter product Regular price" >
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" class="form-control form-control-sm" id="sale_price" name="sale_price" value="{{ (!empty($product)) ? $product->sale_price : '' }}" placeholder="Enter product sale price" >
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">Product Description</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="short_description">Short Description</label>
                        <div class="pad">
                        <textarea class="form-control" name="short_description">{{ (!empty($product)) ? $product->short_description : '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <div class="pad">
                        <textarea id="compose-textarea" class="form-control" name="description">{{ (!empty($product)) ? $product->description : '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specification">Specification</label>
                        <div class="pad">
                        <textarea id="specification-textarea" class="form-control" name="specification">{{ (!empty($product)) ? $product->specification : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">Product Shipping Cost</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="shipping_type">Shipping Type</label>
                        <select name="shipping_type" class="form-control form-control-sm" id="shipping_type">
                            <option value="0" {{ (!empty($product)) && $product->shipping_type == '0' ? 'selected'  : '' }}>Free Shipping</option>
                            <option value="1" {{ (!empty($product)) && $product->shipping_type =='1' ? 'selected'  : '' }}>Flat Rate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shipping_cost">Shipping Cost</label>
                        <input type="text" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="{{ (!empty($product)) && $product->shipping_type == '1' ?  $product->shipping_cost : '0' }}" {{ (!empty($product)) &&  $product->shipping_type == '1' ? '' : 'disabled' }}  placeholder="Enter shipping cost">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <?php 
            //dump($product->attribute)
            //{{!empty($product) && $product->attribute[$attr->name] == $val->value ?' selected' : '' }} 
           
        ?>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title">
                        <i class="fas fa-tags"></i> Product Attributes
                    </h3>
                </div>
                <div class="card-body">
                    @php $attributes = App\Models\ProductAttribute::with('activeValues')->where('is_active', true)->orderBy('sort_order')->get(); @endphp
                    
                    @if($attributes->count() > 0)
                        @foreach($attributes as $key => $attr)
                            <div class="form-group">
                                <label for="attribute_{{$attr->id}}">
                                    {{$attr->name}}
                                    @if($attr->is_required)
                                        <span class="text-danger">*</span>
                                    @endif
                                    @if($attr->description)
                                        <small class="text-muted">({{$attr->description}})</small>
                                    @endif
                                </label>
                                
                                @if($attr->type === 'color')
                                    <!-- Color Swatch Display -->
                                    <div class="color-swatch-container">
                                        @foreach($attr->activeValues as $val)
                                            <label class="color-swatch-option">
                                                <input type="radio" name="attribute[{{$attr->name}}]" value="{{$val->value}}" 
                                                       {{ (isset($product->attribute[$attr->name]) && $product->attribute[$attr->name] == $val->value) ? 'checked' : '' }}>
                                                <span class="color-swatch" style="background-color: {{$val->color_code ?? '#ddd'}};" 
                                                      title="{{$val->value}}"></span>
                                                <span class="color-label">{{$val->value}}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    
                                @elseif($attr->type === 'image')
                                    <!-- Image Grid Display -->
                                    <div class="image-grid-container">
                                        @foreach($attr->activeValues as $val)
                                            <label class="image-grid-option">
                                                <input type="radio" name="attribute[{{$attr->name}}]" value="{{$val->value}}" 
                                                       {{ (isset($product->attribute[$attr->name]) && $product->attribute[$attr->name] == $val->value) ? 'checked' : '' }}>
                                                @if($val->image)
                                                    <img src="{{$val->image}}" alt="{{$val->value}}" class="image-grid-img">
                                                @else
                                                    <div class="image-grid-placeholder">{{$val->value}}</div>
                                                @endif
                                                <span class="image-label">{{$val->value}}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    
                                @elseif($attr->display_type === 'radio')
                                    <!-- Radio Buttons -->
                                    <div class="radio-group">
                                        @foreach($attr->activeValues as $val)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attribute[{{$attr->name}}]" 
                                                       value="{{$val->value}}" id="attr_{{$attr->id}}_{{$val->id}}"
                                                       {{ (isset($product->attribute[$attr->name]) && $product->attribute[$attr->name] == $val->value) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attr_{{$attr->id}}_{{$val->id}}">
                                                    {{$val->value}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                @elseif($attr->display_type === 'checkbox')
                                    <!-- Checkboxes -->
                                    <div class="checkbox-group">
                                        @foreach($attr->activeValues as $val)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="attribute[{{$attr->name}}][]" 
                                                       value="{{$val->value}}" id="attr_{{$attr->id}}_{{$val->id}}"
                                                       {{ (isset($product->attribute[$attr->name]) && is_array($product->attribute[$attr->name]) && in_array($val->value, $product->attribute[$attr->name])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attr_{{$attr->id}}_{{$val->id}}">
                                                    {{$val->value}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                @else
                                    <!-- Default Dropdown -->
                                    <div class="select2-dark">
                                        <select class="form-control form-control-sm product_attribute" 
                                                name="attribute[{{$attr->name}}][]" 
                                                multiple="multiple" 
                                                data-dropdown-css-class="select2-dark" 
                                                style="width: 100%;"
                                                {{ $attr->is_required ? 'required' : '' }}>
                                            @foreach($attr->activeValues as $val)
                                                <option value="{{$val->value}}"
                                                        {{ (isset($product->attribute[$attr->name]) && is_array($product->attribute[$attr->name]) && in_array($val->value, $product->attribute[$attr->name])) ? 'selected' : '' }}>
                                                    {{$val->value}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No attributes available. 
                            <a href="{{route('admin_product_attribute_index')}}" class="alert-link">Create attributes first</a>.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card"><!-- Product gallery-->
                <div class="card-header card-info">
                    <h3 class="card-title panel-title">Product Gallery</h3>
                     <h3 class="card-title panel-title float-right">
                        <a type="button" data-toggle="modal" data-target="#gallery" class="text-primary">Insert Image</a> 
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="galleryimg row mx-auto">
                            <!-- product images and hidden fields -->
                            @if((!empty($product)) && !empty($product->product_image))
                                @php 
                                    // Handle both string (JSON) and array formats
                                    $productImages = is_string($product->product_image) ? json_decode($product->product_image, true) : $product->product_image;
                                @endphp
                                @if(is_array($productImages) && count($productImages) > 0)
                                    @foreach ($productImages as $key => $photo)
                                        <?php
                                            $pimg = \App\Models\Media::where('id', $photo)->first();
                                        ?>
                                        @if(!empty($pimg) && !empty($pimg->id))
                                            <div class="product-img product-images col-md-2 col-3">
                                                <input type="hidden" name="galleryimg_id[]" value="{{$pimg->id}}">
                                                <img class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$pimg->filename}}">
                                                <a href="javascript:void()" class="remove"><span class="fa fa-trash"></span></a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            <!-- dynamically added after  -->
                    </div>  
                </div> 
            </div><!-- End product Gallery -->


            <div class="card"><!-- Featured Image-->
                <div class="card-header card-info">
                    <h3 class="card-title panel-title">Featured Image</h3>
                     <h3 class="card-title panel-title float-right">
                        <a type="button" data-toggle="modal" data-target="#featured" class="text-primary">Insert Image</a> 
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="featuredimg row mx-auto">
                            <!-- product images and hidden fields -->
                            @if((!empty($product)) && $product->featured_image)
                                <?php
                                    $fimg = \App\Models\Media::where('id', $product->featured_image)->first();
                                ?>
                                @if(!empty($fimg) && !empty($fimg->id))
                                    <div class="product-img product-images col-md-2 col-3">
                                        <input type="hidden" name="featuredimg_id" value="{{$fimg->id}}">
                                        <img class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$fimg->filename}}">
                                        <a href="javascript:void()" class="remove"><span class="fa fa-trash"></span></a>
                                    </div>
                                @endif
                            @endif
                            <!-- dynamically added after  -->
                    </div>  
                </div> 
            </div><!-- End Featured Image -->

            <div class="card"><!-- Remote Images -->
                <div class="card-header card-info">
                    <h3 class="card-title panel-title">Remote Images (URLs)</h3>
                </div>
                
                <div class="card-body">
                    <div class="form-group">
                        <label for="remote_images">Remote Image URLs (one per line)</label>
                        <textarea name="remote_images" id="remote_images" class="form-control" rows="5" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('remote_images', $product && $product->remote_images ? (is_array($product->remote_images) ? implode("\n", $product->remote_images) : $product->remote_images) : '') }}</textarea>
                        <small class="form-text text-muted">Enter remote image URLs, one per line. These will be used as fallback when local images are not available.</small>
                    </div>
                </div> 
            </div><!-- End Remote Images -->
            
            <div class="card card-info">
                <div class="card-body">
                    <div class="form-group">
                        <label for="visibility">Visibility</label>
                        <select name="visibility"  class="form-control form-control-sm">
                            <?php $visibility = !empty($product) ? $product->visibility : '';?>
                            <option value="1" {{ $visibility == '1' ? 'selected' : ''}}>Public</option>
                            <option value="0" {{ $visibility == '0' ? 'selected' : ''}}>Private</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
            </div>  

        </div>   
    </div>        
</form> 

    <?php
     //$mediaManager = \App\CustomClass\MediaManager;
     //use \App\CustomClass\MediaManager;
     //$mediaManager  = new MediaManager();
    ?>
    <?php echo \App\CustomClass\MediaManager::mediaScript();?> 
    <?php echo \App\CustomClass\MediaManager::media('multiple', 'gallery', 'galleryimg');?> 
    <?php echo \App\CustomClass\MediaManager::media('single', 'featured', 'featuredimg');?> 
 

@endsection

@section('cusjs')



<script>
    /**
     * Slug Edit Script
     * Slug Auto Get when input Title
     */ 

        $(".slug_edit").change(function(){
            console.log(this.checked)
            $("#product_slug").attr('readonly',!this.checked)
            if(this.checked == true){
                $("#product_slug").addClass('product_slug_active')
                 $("label.slug_fa i").addClass('fa-check').removeClass('fa-edit')
            }
            if(this.checked == false){
                $("#product_slug").removeClass('product_slug_active')
                $("label.slug_fa i").addClass('fa-edit').removeClass('fa-check')
            }
        })
            $("#product_title").keyup(function(){
                var Text = $(this).val();
                Text = Text.toLowerCase();
                Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
                $(".product_slug_active").val(Text);
            });
        
    
 </script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<!-- Latest compiled and minified JavaScript -->  
<link href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

<script>
    /**
     *Brand Select 2 Script 
     * 
     */
    //Select 2
    function selectRefresh() {
        $('select.select2Cat, select.select2Brand').select2({
  
        });
        //alert('hi');
    } 
    selectRefresh();


</script>

<!-- SHipp8ing Type Script -->

<script>
    $('select#shipping_type').change(function(){
        let shippingType = $(this).find('option:selected').val();
           if(shippingType == '0'){
               $('#shipping_cost').val('0');
               $('#shipping_cost').attr('disabled', true);
           }
             if(shippingType == '1'){
               $('#shipping_cost').val('0');
               $('#shipping_cost').attr('disabled', false);
           }
    })
</script>

<!-- Teaxt Area Editor Summer Note -->

<script>
    $('#specification-textarea').summernote({
        height: 250,   //set editable area's height
        codemirror: { // codemirror options
          theme: 'monokai'
      }
    })
</script>


<!-- select2 Multiple Bootstrap CDN &  Script -->       
<script src="{{asset('public/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('public/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
        
<script>
    $('.product_category').select2({
        //theme: 'bootstrap4'
    })

    $('.product_attribute').select2({
        //theme: 'bootstrap4'
    })
</script>

<style>
/* Color Swatch Styles */
.color-swatch-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 5px;
}

.color-swatch-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 5px;
    border: 2px solid transparent;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.color-swatch-option:hover {
    border-color: #007bff;
}

.color-swatch-option input[type="radio"] {
    display: none;
}

.color-swatch-option input[type="radio"]:checked + .color-swatch {
    border: 3px solid #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.color-swatch {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: block;
    margin-bottom: 5px;
}

.color-label {
    font-size: 12px;
    text-align: center;
}

/* Image Grid Styles */
.image-grid-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 5px;
}

.image-grid-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 5px;
    border: 2px solid transparent;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.image-grid-option:hover {
    border-color: #007bff;
}

.image-grid-option input[type="radio"] {
    display: none;
}

.image-grid-option input[type="radio"]:checked + .image-grid-img,
.image-grid-option input[type="radio"]:checked + .image-grid-placeholder {
    border: 3px solid #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.image-grid-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    border: 2px solid #ddd;
    margin-bottom: 5px;
}

.image-grid-placeholder {
    width: 60px;
    height: 60px;
    border: 2px solid #ddd;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    text-align: center;
    margin-bottom: 5px;
    background-color: #f8f9fa;
}

.image-label {
    font-size: 12px;
    text-align: center;
}

/* Radio and Checkbox Groups */
.radio-group,
.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 5px;
}

.radio-group .form-check,
.checkbox-group .form-check {
    margin-bottom: 0;
}

.radio-group .form-check-input,
.checkbox-group .form-check-input {
    margin-right: 5px;
}
</style>

@endsection