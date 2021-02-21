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
                    <div class="form-group">
                        <label for="total stock">Total stock</label>
                        <input type="text" class="form-control form-control-sm" id="total stock" name="total stock" value="{{ (!empty($product)) ? $product->total_stock : '' }}" placeholder="Enter product total stock" autocomplete="off">
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
                    <h3 class="card-title panel-title">Product Attributes</h3>
                </div>
                <div class="card-body">
                    @php $attributes = App\Models\ProductAttribute::with('values')->get(); @endphp
                    @foreach($attributes as $key => $attr)
                        <label for="">{{$attr->name}}</label>
                        <div class="select2-dark">
                            <select class="form-control form-control-sm product_attribute" name="attribute[{{$attr->name}}][]" multiple="multiple"  data-dropdown-css-class="select2-dark" style="width: 100%;">

                                @foreach($attr->values as $val)
                               
                                <option
                                    <?php 
                                        if(isset($product->attribute[$attr->name])){ 
                                        foreach($product->attribute[$attr->name] as $va){
                                            echo $va == $val->value ? 'selected' : '';
                                    }};
                                    ?>
                                    >
                                    {{$val->value}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    @endforeach
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
                            @if((!empty($product)) && $product->product_image)
                                @foreach ($product->product_image as $key => $photo)
                                    <?php
                                        $pimg = \App\Models\Media::where('id', $photo)->first();
                                        //echo $pimg->filename;
                                    ?>
                                    @if(!empty($pimg->id))
                                        <div class="product-img product-images col-md-2 col-3">
                                            <input type="hidden" name="galleryimg_id[]" value="{{$pimg->id}}">
                                            <img class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$pimg->filename}}">
                                            <a href="javascript:void()" class="remove"><span class="fa fa-trash"></span></a>
                                        </div>
                                    @endif
                                    @endforeach
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
                                @if(!empty($fimg->id))
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


@endsection