@extends('admin.layouts.master')

@section('site-title')
Product Brand
@endsection

@section('page-content')
<div class="row">
    <div class="col-md-5">
        <form id="productForm" role="form" method="POST" action ="{{ (!empty($brand)) ? route('admin_product_brand_update') : route('admin_product_brand_store') }}" senctype="multipart/form-data">
            @if(!empty($brand))
                <input type="hidden" name="id" value="{{$brand->id}}" />
            @endif
            @csrf
            <div class="card">
                <div class="card-header card-info  {{ (!empty($brand)) ? 'alert-primary' : '' }}">
                    <h3 class="card-title panel-title float-left">
                        {{ (!empty($brand)) ? 'Edit brand' : 'Add brand' }}
                    </h3>
                    <h3 class="card-title panel-title float-right">
                        <a title="Add New" href="{{route('admin_product_brand_index')}}" class="text-primary"> <i class="fa fa-plus"></i></a> 
                    </h3>
                </div><!-- end card-header-->
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Brand Name</label>
                        <input type="text" class="form-control form-control-sm" id="brand_title" name="name" placeholder="Enter brand name" autocomplete="off" value="{{ (!empty($brand)) ? $brand->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="slug">Brand Slug</label>
                        @if(!empty($brand)) 
                            <input class="slug_edit d-none" id="slug_edit" name="slug_edit" type="checkbox"> 
                            <label for="slug_edit" class=" font-weight-normal text-success slug_fa" role="button" style="font-size: 10px;"> 
                                <i class="fas fa-edit"></i>    
                            </label>
                        @endif
                        <input type="text" class="form-control form-control-sm {{ (!empty($brand)) ?  : 'brand_slug_active' }}" id="brand_slug" name="slug" placeholder="Enter brand Slug" value="{{ (!empty($brand)) ? $brand->slug : '' }}" autocomplete="off" {{ (!empty($brand)) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="catimg">Brand Logo</label>
                        <a type="button" data-toggle="modal" data-target="#brand" class="text-primary float-right">Insert Image</a> 
                        <div class="brandimg row mx-auto">
                                <!-- product images and hidden fields -->
                                @if((!empty($brand)) && $brand->image)
                                    <?php
                                        $bimg = \App\Models\Media::where('id', $brand->image)->first();
                                    ?>
                                    @if(!empty($bimg->id))
                                        <div class="product-img product-images col-md-2 col-3">
                                            <input type="hidden" name="brandimg_id" value="{{$bimg->id}}">
                                            <img class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$bimg->filename}}">
                                            <a href="javascript:void()" class="remove"><span class="fa fa-trash"></span></a>
                                        </div>
                                    @endif
                                @endif
                                <!-- dynamically added after  -->
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="visibility">Visibility</label>
                        <select name="visibility"  class="form-control form-control-sm">
                            <?php $visibility = !empty($brand) ? $brand->visibility : '';?>
                            <option value="1" {{ $visibility == '1' ? 'selected' : ''}}>Public</option>
                            <option value="0" {{ $visibility == '0' ? 'selected' : ''}}>Private</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- end card-body-->
            </div><!-- end card-->
        </form>
    </div><!-- end col-->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-info">
                <h3 class="card-title panel-title float-left"> All Brand </h3>
            </div><!-- end card header-->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name & Details</th>
                        <th>Visibility</th>
                        <th>Count</th>
                        <th>Image</th>
                        <th>Date & Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                            @foreach($getBrand as $data)
                                <tr class="">
                                    <td class="align-middle"></td>
                                    <td class="align-middle">{{$data->name}}</td>
                                    <td class="align-middle">
                                        <small>
                                                @if($data->visibility == 1)
                                                <span class="badge badge-success">Public</span>
                                                @else 
                                                <span class="badge badge-warning">Private</span>
                                                @endif
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <a target="_blank" class="text-primary" href="{{route('admin_brand_by_product', $data->id)}}">
                                            {{count(App\Models\Product::where('brand_id', $data->id)->get())}}
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <?php
                                            $bimg = \App\Models\Media::where('id', $data->image)->first();
                                        ?>
                                        @if(!empty($bimg->id))
                                            <img style="width: 50px;" class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$bimg->filename}}">
                                        @endif
                                    </td>
                                    <td class="align-middle">{{$data->created_at}}</td>
                                    <td class="align-middle">
                                        <a href="{{route('admin_product_brand_edit', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-edit"></i></a>   
                                        <a href="{{route('admin_product_brand_delete', $data->id)}}" class="btn-sm alert-danger"  onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div><!-- end card body-->
        </div><!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->
<?php 
    echo \App\CustomClass\MediaManager::mediaScript(); 
    echo \App\CustomClass\MediaManager::media('single', 'brand', 'brandimg');
?> 


@endsection



@section('cusjs')

<script>

        $(".slug_edit").change(function(){
            console.log(this.checked)
            $("#brand_slug").attr('readonly',!this.checked)
            if(this.checked == true){
                $("#brand_slug").addClass('brand_slug_active')
                $("label.slug_fa i").addClass('fa-check').removeClass('fa-edit')
            }
            if(this.checked == false){
                $("#brand_slug").removeClass('brand_slug_active')
                $("label.slug_fa i").addClass('fa-edit').removeClass('fa-check')
            }
        })
            $("#brand_title").keyup(function(){
                var Text = $(this).val();
                Text = Text.toLowerCase();
                Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
                $(".brand_slug_active").val(Text);
            });
        
    
 </script>




@endsection