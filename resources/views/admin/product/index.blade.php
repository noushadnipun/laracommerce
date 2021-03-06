@extends('admin.layouts.master')

@section('site-title')
All Product
@endsection

@section('page-content')
     <div class="card">
        <div class="card-header card-info">
            <h3 class="card-title panel-title float-left"> 
                All Product {{!empty($catName) ? 'of '. $catName : ''}}
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
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
                        <?php
                            $fimg = \App\Models\Media::where('id', $data->featured_image)->first();
                        ?>
                        @if(!empty($fimg->id))
                            <img style="width: 50px;" class="img-fluid" src="{{asset('/public/uploads/images/').'/'.$fimg->filename}}">
                        @endif
                    </td>
                    <td class="align-middle">
                      <a target="_blank" class="text-primary" href="{{route('frontend_single_product', $data->slug)}}"> {{$data->title}} </a>  
                    </td>
                    <td class="align-middle">
                    <small class="font-weight-bold">
                        PP : {{$data->purchase_price}} <br>
                        RP : {{$data->regular_price}} <br>
                        SP : {{$data->sale_price}} <br><br>
                    </small>  
                    </td>
                    <td class="align-middle">
                        <small>
                            @if($data->visibility == 1)
                            <span class="badge badge-success">Public</span>
                            @else 
                            <span class="badge-warning">Private</span>
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
            {{$product->links('pagination::bootstrap-4')}}
        </div>
     </div>

@endsection