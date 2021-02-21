@extends('frontend.layouts.master')

@section('page-content')
    <!-- my account start  -->
    <section class="main_content_area">
        <div class="container">   
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <!-- Nav tabs -->
                        <div class="dashboard_tab_button">
                            <ul role="tablist" class="nav flex-column dashboard-list" id="myTab">
                                <li><a href="#account-details" data-toggle="tab" class="nav-link active">Account details</a></li>
                                <li> <a href="#orders" data-toggle="tab" class="nav-link">Orders</a></li>
                                <li><a href="#address" data-toggle="tab" class="nav-link" aria-controls="address" role="tab" aria-selected="false">Addresses</a></li>
                            </ul>
                        </div>    
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <!-- Tab panes -->
                        <div class="tab-content dashboard_content">
                            <div class="tab-pane fade show active" id="account-details">
                                <h3>Account details </h3>
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
                                <h3>Orders</h3>
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
                                            @foreach($getAllOrder as $order)
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
                                            @php
                                                $address = \App\Models\UserAddressBook::where('user_id', Auth::user()->id)->first();
                                            @endphp
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
</script>



<script>

    $('.modalQuickView').click(function(e){
        e.preventDefault();
        let orderId = $(this).attr('id');
        $.ajax({
            type : 'GET',
            url :  '<?php echo route('frontend_order_quick_view', '');?>/'+orderId,
            success : function(data){
                $('.product_modal_body').html(data);
                $('#modal_box').modal('show');  
            }
        })
    })
    
</script>

@endsection