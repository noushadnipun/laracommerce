@extends('admin.layouts.master')

@section('site-title')
Frontend Settings
@endsection

@section('page-content')
<div class="row">
    <?php function frontSetting($arg){
        $get = \App\Models\FrontendSettings::where('meta_name', $arg)->first();
        return $get->meta_value;
    }?>
    <div class="col-md-6">
        <form action="{{route('admin_frontend_settings_update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">
                        Frontend Settings
                    </h3>
                </div><!-- end card-header-->
               {{-- <label dname="site_name">Label</label>
               <input name="meta_name[]" value="site_name">
               <input type="text" name="site_name">
               <br/> --}}
                <div class="div card-body">

                    <div class="form-group"> <!-- Site Logo -->
                        <label for="">Site Logo</label>
                        <h3 class="card-title panel-title float-right">
                         <a type="button" data-toggle="modal" data-target="#site_logo" class="text-primary">Insert Image</a> 
                        </h3>
                        <input name="meta_name[]" type="hidden" value="site_logoimg_id">

                        <div class="site_logoimg row mx-auto">
                            <!-- product images and hidden fields -->
                                <?php
                                    $fimg = \App\Models\Media::where('id', frontSetting('site_logoimg_id'))->first();
                                ?>
                                @if(!empty($fimg->id))
                                    <div class="product-img product-images col-md-2 col-3">
                                        <input type="hidden" name="site_logoimg_id" value="{{$fimg->id}}">
                                        <img class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$fimg->filename}}">
                                        <a href="javascript:void()" class="remove"><span class="fa fa-trash"></span></a>
                                    </div>
                                @endif
                            <!-- dynamically added after  -->
                        </div>  
                    </div> <!-- Site Logo -->

                    <div class="form-group"><!-- Company Phone -->
                        <label for="">Contact Phone</label>
                        <input name="meta_name[]" type="hidden" value="company_phone">
                        <input name="company_phone" type="text" class="form-control form-control-sm" value="{{frontSetting('company_phone') }}">
                    </div><!-- End Company Phone -->

                    <div class="form-group"> <!-- Homepage Slider -->
                        <label for="">Homepage Slider</label>
                        <input name="meta_name[]" type="hidden" value="home_slider">
                        <select class="form-control form-control-sm" name="home_slider">
                            <option valu="">Select Category</option>
                            @php $getSliderCat = \App\Models\Category::where('taxonomy_type', 'slider')->get() @endphp
                            @foreach($getSliderCat as $row)
                                <option value="{{$row->id}}" {{$row->id == frontSetting('home_slider')? 'selected' : ''}}>
                                    {{$row->name}}
                                </option>
                            @endforeach
                        </select>
                    </div><!-- End Homepage Slider -->
                        
                    <div class="form-group"><!-- Product category -->
                        <label>Homepage Product Category</label>
                        <input type="hidden" name="meta_name[]" value="home_product_category">
                        <div class="select2-dark">
                            <?php
                                function selectCat($parent_id = null, $sub_mark = "") {
                                    $getCat = \App\Models\ProductCategory::where('parent_id', $parent_id)->orderBy('created_at', 'desc')->get();
                                    foreach($getCat as $row){ ?>
                                        <option value="{{$row->id}}" 
                                            <?php 
                                                foreach(\App\Helpers\WebsiteSettings::strToArr(frontSetting('home_product_category')) as $items){ echo $row->id == $items ? 'selected' : ''; } 
                                            ;?>
                                            >{{$sub_mark.$row->name}} 
                                        </option>
                                        <?php selectCat($row->id, $sub_mark .'— ');
                                    }
                                }?>
                            <select name="home_product_category[]" class="product_category" multiple="multiple" data-placeholder="Select Category" data-dropdown-css-class="select2-dark" style="width: 100%;" autocomplete="off">
                                <?php selectCat();?>
                            </select>
                        </div>
                    </div><!-- End product categeory -->
                    

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>

<?php echo \App\CustomClass\MediaManager::mediaScript();?> 
<?php echo \App\CustomClass\MediaManager::media('single', 'site_logo', 'site_logoimg');?> 

@endsection


@section('cusjs')
    <script src="{{asset('public/admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>

   
   
    <script>
        $('.product_category').select2({
            //theme: 'bootstrap4'
        })
    </script>
@endsection