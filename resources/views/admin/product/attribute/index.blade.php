@extends('admin.layouts.master')
@section('site-title')
attributes
@endsection

@section('page-content')

<div class="row">
    <div class="col-md-5">
        <form id="productForm" role="form" method="POST" action ="{{ (!empty($attribute)) ? route('admin_product_attribute_update') : route('admin_product_attribute_store') }}" senctype="multipart/form-data">
            @if(!empty($attribute))
                <input type="hidden" name="id" value="{{$attribute->id}}" />
            @endif
            @csrf
            <div class="card">
                <div class="card-header card-info  {{ (!empty($attribute)) ? 'alert-primary' : '' }}">
                    <h3 class="card-title panel-title float-left">
                        {{ (!empty($attribute)) ? 'Edit attribute' : 'Add attribute' }}
                    </h3>
                    <h3 class="card-title panel-title float-right">
                        <a title="Add New" href="{{route('admin_product_attribute_index')}}" class="text-primary"> <i class="fa fa-plus"></i></a> 
                    </h3>
                </div><!-- end card-header-->
                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter attribute Name" autocomplete="off" value="{{ (!empty($attribute)) ? $attribute->name : '' }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- end col-->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-info">
                <h3 class="card-title panel-title float-left"> All Attribute </h3>
            </div><!-- end card header-->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                            @foreach($getAttribute as $data)
                                <tr class="">
                                    <td class="align-middle">{{$data->name}}</td>
                                    <td class="align-middle">
                                        <a href="{{route('admin_product_attribute_edit', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-edit"></i></a>   
                                        <a href="{{route('admin_product_attribute_delete', $data->id)}}" class="btn-sm alert-danger"  onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div><!-- end card body-->
        </div><!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->



<!-- Atribute Value -->

<div class="row">
    <div class="col-md-5">
        <form id="productForm" role="form" method="POST" action ="{{ (!empty($attributevalue)) ? route('admin_product_attribute_value_update') : route('admin_product_attribute_value_store') }}" senctype="multipart/form-data">
            @if(!empty($attributevalue))
                <input type="hidden" name="id" value="{{$attributevalue->id}}" />
            @endif
            @csrf
            <div class="card">
                <div class="card-header card-info  {{ (!empty($attributevalue)) ? 'alert-primary' : '' }}">
                    <h3 class="card-title panel-title float-left">
                        {{ (!empty($attributevalue)) ? 'Edit attribute Value' : 'Add attribute value' }}
                    </h3>
                    <h3 class="card-title panel-title float-right">
                        <a title="Add New" href="{{route('admin_product_attribute_value_index')}}" class="text-primary"> <i class="fa fa-plus"></i></a> 
                    </h3>
                </div><!-- end card-header-->
                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Value</label>
                        <input type="text" name="value" class="form-control form-control-sm" placeholder="Enter attribute Name" autocomplete="off" value="{{ (!empty($attributevalue)) ? $attributevalue->value : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="code">Attribute</label>
                        <select class="form-control form-control-sm" name="attributes_id">
                            @foreach($getAttribute as $item)
                            <option value="{{$item->id}}" {{ (!empty($attributevalue)) && $attributevalue->attributes_id == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- end col-->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-info">
                <h3 class="card-title panel-title float-left"> All Attribute Value</h3>
            </div><!-- end card header-->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed table-hover">
                    <thead>
                    <tr>
                        <th>Attribute Value</th>
                        <th>Attribute</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                            @foreach($getAttributeValue as $data)
                                <tr class="">
                                    <td class="align-middle">{{$data->value}}</td>
                                    <td class="align-middle">
                                        {{\App\Models\ProductAttribute::where('id', $data->attributes_id)->first()->name }}
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{route('admin_product_attribute_value_edit', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-edit"></i></a>   
                                        <a href="{{route('admin_product_attribute_value_delete', $data->id)}}" class="btn-sm alert-danger"  onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div><!-- end card body-->
        </div><!-- end card -->
    </div><!-- end col-->
</div><!-- end row-->


@endsection