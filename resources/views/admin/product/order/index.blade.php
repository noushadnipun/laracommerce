@extends('admin.layouts.master')

@section('site-title')
Manage Order
@endsection

@section('page-content')

<div id="reportBlock" class="d-inline-block"></div>

    <div class="btn btn-app bg-white">
        Payment Status 
        <?php $pSts =  request()->payment_status ?>
        <form method="get" action="{{route('admin_product_order_filter')}}">
            @csrf
            <select name="payment_status" id="">
                <option value="">Show All</option>
                <option value="Pending" {{$pSts == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{$pSts == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Paid" {{$pSts == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Unpaid" {{$pSts == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
            <button type="submit" class="btn badge badge-primary btn-primary">Filter</button>
        </form>
    </div>

    <div class="btn btn-app bg-white">
        Delivery Status
         <?php $dSts =  request()->delivery_status ?>
        <form method="get" action="{{route('admin_product_order_filter')}}" class="">
            @csrf
            <select name="delivery_status" id="">
                <option value=''>Show All</option>
                <option value="Pending" {{$dSts == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="on-delivery" {{$dSts == 'on-delivery' ? 'selected' : '' }}>On Delivery</option>
                <option value="placed" {{$dSts == 'placed' ? 'selected' : '' }}>Placed</option>
                <option value="canceled" {{$dSts == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
            <button type="submit" class="btn badge badge-primary btn-primary">Filter</button>
        </form>
    </div>
    <div class="btn btn-app bg-white">
         Search By Order Code    
        <form method="get" action="{{route('admin_product_order_filter')}}" class="">
            @csrf
            <input type="text" name="order_code" class="" value="{{request()->order_code}}" placeholder="#OD-">
            <button type="submit" class="btn badge badge-primary btn-primary">Search</button>
        </form>
    </div>


<?php 

$totalOrderAmount = 0; 
$totalPaidAmount = \App\Models\productOrder::where('payment_status', 'Paid')->select('total_amount')->sum('total_amount');
?>

<div class="card">
    <div class="card-header card-info">
        <h3 class="card-title panel-title float-left"> Manage Order </h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed table-hover">
            <thead>
            <tr>
                <th>SL</th>
                <th>Order Code</th>
                <th>Number Of Products</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Delivery Status</th>
                <th>Payment Status</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($getAllOrder as $key => $data)
            <tr>
                <td class="align-middle">
                    {{$key + $getAllOrder->firstItem()}}
                </td>
                <td class="align-middle">
                    {{$data->order_code}}
                </td>
                <td class="align-middle">
                    {{count($data->orderDetails)}}
                </td>
                <td class="align-middle">
                     {{$data->customer_name}}
                </td>
                <td class="align-middle">
                    {{$data->total_amount}}
                    <?php $totalOrderAmount += $data->total_amount;?>
                </td>
                <td class="align-middle">
                    {{$data->delivery_status}}
                </td>
                <td class="align-middle">
                    {{$data->payment_status}}
                </td>
                <td class="align-middle">
                    {{$data->payment_type}}
                </td>
                <td class="align-middle">
                    {{$data->created_at}}
                </td>
                <td class="align-middle">
                    <a href="{{route('admin_product_order_view', $data->id)}}" class="btn-sm alert-success"><i class="fa fa-eye"></i></a>   
                    <a href="{{route('admin_product_order_delete', $data->id)}}" class="btn-sm alert-danger" onclick="return confirm('Are you sure want to Delete?')"><i class="fa fa-trash"></i></a>  
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{$getAllOrder->links('pagination::bootstrap-4')}}
    </div>
</div>
      
<?php function bdtSign($arg){
    return \App\Helpers\Frontend\ProductView::priceSign($arg);
}
?>

@endsection


@section('cusjs')

<script>
    function Block(){
        let ele = '<div class="btn btn-app bg-success"><i class="fa">{{bdtSign($totalOrderAmount)}}</i> Total Ordered Amount</div>';
        //ele += '<div class="btn btn-app bg-success"><i class="fa">{{bdtSign($totalPaidAmount)}}</i> Total Paid Amount</div>';

        $('#reportBlock').html(ele);
    }
    Block()
</script>

@endsection