@extends('admin.layouts.master')

@section('site-title')
Manage Order
@endsection

@section('page-content')

<div id="reportBlock"></div>

<?php $totalOrderAmount = 0; ?>

<div class="card">
    <div class="card-header card-info">
        <h3 class="card-title panel-title float-left"> Manage Order </h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed table-hover">
            <thead>
            <tr>
                <th>Order ID</th>
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
            @foreach($getAllOrder as $data)
            <tr>
                <td class="align-middle">
                    {{$data->id}}
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

        $('#reportBlock').html(ele);
    }
    Block()
</script>

@endsection