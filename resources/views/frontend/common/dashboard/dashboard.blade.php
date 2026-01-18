@extends('frontend.layouts.master')

@section('page-content')
    <!-- my account start  -->
    <section class="main_content_area row justify-content-center">
        <div class="col-lg-10">
            @if(isset($stats))
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Total Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Processing</h6>
                            <h3 class="mb-0 text-primary">{{ $stats['processing'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-1">Delivered</h6>
                            <h3 class="mb-0 text-success">{{ $stats['delivered'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <!-- Nav tabs -->
                        <div class="dashboard_tab_button card shadow-sm">
                            <div class="card-body p-0">
                            <ul role="tablist" class="nav flex-column dashboard-list" id="myTab">
                                <li><a href="#account-details" data-toggle="tab" class="nav-link active">Account details</a></li>
                                <li><a href="#orders" data-toggle="tab" class="nav-link">Orders</a></li>
                                @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                                <li><a href="#wishlist" data-toggle="tab" class="nav-link">Wishlist ({{ $stats['wishlist_count'] ?? $wishlistItems->count() }})</a></li>
                                @endif
                                <li><a href="#address" data-toggle="tab" class="nav-link" aria-controls="address" role="tab" aria-selected="false">Addresses</a></li>
                            </ul>
                            </div>
                        </div>    
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <!-- Tab panes -->
                        <div class="tab-content dashboard_content">
                            <div class="tab-pane fade show active" id="account-details">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="mb-0">Account details</h3>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#trackOrderCollapse">Track order</button>
                                    </div>
                                </div>
                                <div class="collapse mb-3" id="trackOrderCollapse">
                                    <div class="card card-body">
                                        <form id="trackOrderForm" onsubmit="return trackOrderByCode(event)">
                                            <div class="form-row align-items-center">
                                                <div class="col-sm-8 my-1">
                                                    <input type="text" class="form-control" placeholder="Enter order code (e.g., ORD-XXXX)" id="trackOrderCode" required>
                                                </div>
                                                <div class="col-auto my-1">
                                                    <button type="submit" class="btn btn-primary">Track</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="login">
                                    <div class="login_form_container">
                                        <div class="account_login_form">
                                            <form action="{{route('frontend_customer_account_update')}}" method="post">
                                                @csrf
                                                <label>Name</label>
                                                <input type="text" name="name" value="{{auth()->user()->name}}">
                                                <label>Email</label>
                                                <input type="text" name="email" value="{{auth()->user()->email}}" disabled>
                                                <label>Password</label>
                                                <input type="password" name="password" required>
                                                <label>Confirm Password</label>
                                                <input type="password" name="confirm_passowrd" required>
                                                <br>
                                                <div class="save_button primary_btn default_button">
                                                    <button type="submit">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="orders">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="mb-0">Orders</h3>
                                    @if(isset($orders) && $orders->count() > 0)
                                    <div>
                                        @php $last = $orders->first(); @endphp
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="reorder({{ $last->id }})">Reorder last order</a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary ml-1" onclick="downloadInvoice({{ $last->id }})">Download invoice</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order Code</th>
                                                <th>Date</th>
                                                <th>Payment Status:</th>
                                                <th>Order Status</th>
                                                <th>Total Order Amount</th>
                                                <th>Actions</th>	 	 	 	
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(($orders ?? collect()) as $order)
                                            <tr>
                                                <td>{{$order->order_code}}</td>
                                                <td>{{$order->created_at}}</td>
                                                <td>
                                                     {{$order->payment_status}}
                                                </td>
                                                <td><span class="success">{{$order->delivery_status}}</span></td>
                                                <td>৳{{$order->total_amount}} </td>
                                                <td>
                                                    <a href="javascript:void()" id="<?php echo $order->id;?>" class="modalQuickView view"  xdata-toggle="modal" xdata-target="#modal_box" title="quick view">view</a>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                @if(isset($orders))
                                <div class="d-flex justify-content-end">
                                    {{ $orders->links() }}
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane" id="address" aria-labelledby="address-tab">
                                <p>The following addresses will be used on the checkout page by default.</p>
                                <h4 class="billing-address">
                                    Billing address 
                                 </h4>
                                <div class="login_form_container">
                                    <div class="account_login_form">
                                        <form action="{{route('frontend_customer_address_update')}}" method="post">
                                            @csrf
                                            @php /* Address injected by controller as $address */ @endphp
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{!empty($address) ? $address->name : ''}}" required>

                                            <label>Address <span class="text-danger">*</span></label>
                                            <input type="text" name="address" value="{{!empty($address) ? $address->address : ''}}" required>

                                            <label>Thana <span class="text-danger">*</span></label>
                                            <input type="text" name="thana" value="{{!empty($address) ? $address->thana : ''}}" required>

                                            <label>Postal Code <span class="text-danger">*</span></label>
                                            <input type="text" name="postal_code" value="{{!empty($address) ? $address->postal_code : ''}}" required>

                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" name="city" value="{{!empty($address) ? $address->city : ''}}" required>

                                            <label>Country <span class="text-danger">*</span></label>
                                            <input type="text" name="country" value="{{!empty($address) ? $address->country : 'Bangladesh'}}" required>

                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" value="{{!empty($address) ? $address->phone : ''}}" required>

                                            <br>
                                            <div class="save_button primary_btn default_button">
                                                <button type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div> <!-- Endd -->
                            </div>
                            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                            <div class="tab-pane fade" id="wishlist">
                                <h3>Wishlist</h3>
                                <div class="row">
                                    @foreach($wishlistItems as $w)
                                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                                        @php $p = $w->product; @endphp
                                        {!! $p ? \App\Helpers\Frontend\ProductView::view([$p]) : '' !!}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            {{-- ProductView quick view modal (same as homepage) --}}
                            {!! \App\Helpers\Frontend\ProductView::productModal() !!}
                        </div>
                    </div>
                </div>
                </div>
            </div>  
        </div>        	
    </section>			
    <!-- my account end   -->

     <!-- order modal area start-->
        <div class="modal fade" id="modal_box" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal_body product_modal_body">
                            
                    </div>    
                </div>
            </div>
        </div>

        <!-- modal area end-->
@endsection

@section('cusjs')

<script>
    $(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    $('#myTab a[href="#'+hash[1]+'"]').tab("show");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);
  } 
   
  $('a[data-toggle="tab"]').on("click", function() {
    let newUrl;
    const hash = $(this).attr("href");
    if(hash == "#home") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    newUrl += "/";
    history.replaceState(null, null, newUrl);
  });
});

function trackOrderByCode(e){
    e.preventDefault();
    const code = document.getElementById('trackOrderCode').value.trim();
    if(!code) return false;
    try {
        const row = Array.from(document.querySelectorAll('#orders table tbody tr')).find(tr => tr.innerText.includes(code));
        if(row){
            $('#myTab a[href="#orders"]').tab('show');
            row.scrollIntoView({behavior:'smooth', block:'center'});
            row.classList.add('table-warning');
            setTimeout(() => row.classList.remove('table-warning'), 2000);
        } else {
            alert('Order not found in recent list. Please check your email for the order link.');
        }
    } catch(err) {
        console.warn(err);
    }
    return false;
}
</script>



<script>

    $('.modalQuickView').click(function(e){
        e.preventDefault();
        let orderId = $(this).attr('id');
        $.ajax({
            type : 'GET',
            url :  '<?php echo url('order/quick-view');?>/'+orderId,
            success : function(data){
                $('.product_modal_body').html(data);
                $('#modal_box').modal('show');  
            }
        })
    })
    
    // Quick actions placeholders
    function reorder(orderId){
        alert('Reorder requested for order #' + orderId + '. This can add the items to your cart.');
    }
    
    function downloadInvoice(orderId){
        alert('Invoice download for order #' + orderId + ' will be implemented.');
    }

</script>

@endsection