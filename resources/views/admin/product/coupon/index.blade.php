@extends('admin.layouts.master')
@section('site-title')
Coupons
@endsection

@section('page-content')

<div class="row">
    <div class="col-md-5">
        <form id="productForm" role="form" method="POST" action ="{{ (!empty($coupon)) ? route('admin_product_coupon_update') : route('admin_product_coupon_store') }}" senctype="multipart/form-data">
            @if(!empty($coupon))
                <input type="hidden" name="id" value="{{$coupon->id}}" />
            @endif
            @csrf
            <div class="card">
                <div class="card-header card-info  {{ (!empty($coupon)) ? 'alert-primary' : '' }}">
                    <h3 class="card-title panel-title float-left">
                        {{ (!empty($coupon)) ? 'Edit Coupon' : 'Add Coupon' }}
                    </h3>
                    <h3 class="card-title panel-title float-right">
                        <a title="Add New" href="{{route('admin_product_coupon_index')}}" class="text-primary"> <i class="fa fa-plus"></i></a> 
                    </h3>
                </div><!-- end card-header-->
                <div class="card-body">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Enter Coupon code" autocomplete="off" value="{{ (!empty($coupon)) ? $coupon->code : '' }}">
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control form-control-sm">
                             <?php $type = !empty($coupon) ? $coupon->type : '';?>
                            <option value="fixed" {{ $type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                            <option value="percentage_off" {{ $type == 'percentage_off' ? 'selected' : ''}}>Percentage Of</option>
                            {{-- <option value="product_base" {{ $type == 'product_base' ? 'selected' : ''}}>Product Base</option> --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" name="amount" class="form-control form-control-sm" placeholder="Enter Coupon Amount" autocomplete="off" value="{{ (!empty($coupon)) ? $coupon->amount : '' }}">
                    </div>

                    <div class="form-group">
                        <label for="visibility">Visibility</label>
                        <select name="status"  class="form-control form-control-sm">
                            <?php $visibility = !empty($coupon) ? $coupon->status : '';?>
                            <option value="1" {{ $visibility == '1' ? 'selected' : ''}}>Public</option>
                            <option value="0" {{ $visibility == '0' ? 'selected' : ''}}>Private</option>
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
                <h3 class="card-title panel-title float-left"> All Coupons </h3>
            </div><!-- end card header-->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Amount</th>
                        <th>Visibility</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                            @foreach($getCoupon as $data)
                                <tr class="">
                                    <td class="align-middle"></td>
                                    <td class="align-middle">{{$data->code}}</td>
                                    <td class="align-middle">{{$data->amount}}</td>
                                    <td class="align-middle">
                                        <small>
                                                @if($data->status == 1)
                                                <span class="badge badge-success">Public</span>
                                                @else 
                                                <span class="badge badge-warning">Private</span>
                                                @endif
                                        </small>
                                    </td>
                                    <td class="align-middle">{{$data->type}}</td>
                                    <td class="align-middle">{{$data->created_at}}</td>
                                    <td class="align-middle">
                                        <a href="{{route('admin_product_coupon_edit', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-edit"></i></a>   
                                        <a href="{{route('admin_product_coupon_delete', $data->id)}}" class="btn-sm alert-danger"  onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
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