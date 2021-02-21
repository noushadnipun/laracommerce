<div class="invoice p-3 mb-3">
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
            Customer info
            <address>
            <strong>{{$order->user->name}}</strong><br>
            {{$order->user->adress}} , {{$order->user->dstrict}}, {{$order->user->postcode}}<br>
            Phone: {{$order->user->phone}}<br>
            Email: {{$order->user->email}}<br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
            Shipping Address
            <address>
            <strong>{{$order->customer_name}}</strong><br>
            {{$order->customer_address}}<br>
            {{$order->customer_thana}}<br>
            {{$order->customer_city}}, {{$order->customer_postal_code}}<br>
            Phone: {{$order->customer_phone}}<br>
            </address>
        </div>
    <!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <b>Order Code {{$order->order_code}}</b><br>
        <b>Order ID:</b> {{$order->id}}<br>
        <b>Order Date:</b> {{$order->created_at}}<br>
        <b>Transaction ID:</b> {{$order->tran_id}} <br/>
    </div>
    <div class="col-sm-3 invoice-col">
        <b>Payment Status</b> {{$order->payment_status}} <br/>
        <b>Delivery Status</b> {{$order->delivery_status}} <br/>
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
    <div class="col-12 table-responsive">
        <table class="table table-striped">
        <thead>
        <tr>
            <th>Qty</th>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Attribute</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @php $subTotal = 0; @endphp
        @foreach($order->orderDetails as $item)
        <tr>
            <td>{{$item->qty}}</td>
            <td> <a href="{{route('frontend_single_product', $item->products->slug)}}" target="_blank">{{$item->products->title}}</a>  </td>
            <td><img style="width: 50px;" src="{{App\Models\Media::fileLocation($item->products->featured_image)}}"> </td>
            <td>
                @if(isset($item->attribute))
                @foreach($item->attribute as $attr => $value)
                    <strong>{{$attr}} : </strong>
                    @foreach($value as $val)
                        {{$val}} 
                    @endforeach 
                    <br> 
                @endforeach
                @endif
            </td>
            <td>
                {{App\Helpers\Frontend\ProductView::PriceSign($item->price)}}
                <?php $subTotal += $item->price; ?>
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
    <!-- accepted payments column -->
    <div class="col-6">
        <p class="lead">Payment Methods: {{$order->payment_type}}</p>
        

    </div>
    <!-- /.col -->
    <div class="col-6">

        <div class="table-responsive">
        <table class="table">
            <tr>
                <th style="width:50%">Subtotal:</th>
                <td>
                    {{ $subTotal }}
                </td>
            </tr>
            @if(!empty($order->use_coupone && $order->coupone_discount))
            <tr>
                <th>Coupon <span class="badge badge-success">{{$order->use_coupone}}</span></th>
                <td>-{{$order->coupone_discount}}</td>
            </tr>
            @endif
            <tr>
                <th>Shipping:</th>
                <td>{{$order->shipping_cost}}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td>{{$order->total_amount}}</td>
            </tr>
        </table>
        </div>
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

</div>