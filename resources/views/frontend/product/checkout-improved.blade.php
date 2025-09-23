@extends('frontend.layouts.master')

@section('title', 'Checkout')

@section('page-content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="container">   
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{ route('frontend_home') }}">Home</a></li>
                        <li><a href="{{ route('frontend_cart_index') }}">Cart</a></li>
                        <li>Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>         
</div>
<!--breadcrumbs area end-->

<!--checkout area start -->
<div class="checkout_area mt-60">
    <div class="container">
        <!-- Display Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('cart'))
        <form id="checkout-form" method="POST" action="{{ route('frontend_checkout_checkout') }}">
            @csrf
            <div class="row">
                <!-- Checkout Form -->
                <div class="col-lg-8 col-md-12">
                    <div class="checkout_form_wrapper">
                        <!-- Customer Information -->
                        <div class="checkout_section">
                            <h4 class="section_title">
                                <i class="fas fa-user"></i> Customer Information
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="customer_name" 
                                               name="customer_name" 
                                               value="{{ Auth::user()->name ?? '' }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="customer_email" 
                                               name="customer_email" 
                                               value="{{ Auth::user()->email ?? '' }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_phone">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               value="{{ Auth::user()->phone ?? '' }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_city">City</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="customer_city" 
                                               name="customer_city" 
                                               value="Dhaka">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="checkout_section">
                            <h4 class="section_title">
                                <i class="fas fa-map-marker-alt"></i> Shipping Address
                            </h4>
                            
                            <!-- Saved Addresses -->
                            @if(Auth::check() && Auth::user()->addresses->count() > 0)
                            <div class="saved_addresses mb-4">
                                <label>Use Saved Address:</label>
                                <select name="saved_address" class="form-control">
                                    <option value="">Select a saved address</option>
                                    @foreach(Auth::user()->addresses as $address)
                                        <option value="{{ $address->id }}">
                                            {{ $address->name }} - {{ $address->address }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <div class="form-group">
                                <label for="customer_address">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                          id="customer_address" 
                                          name="customer_address" 
                                          rows="3" 
                                          required></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_state">State</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="customer_state" 
                                               name="customer_state" 
                                               value="Dhaka">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_postcode">Postal Code</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="customer_postcode" 
                                               name="customer_postcode" 
                                               value="1000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="checkout_section">
                            <h4 class="section_title">
                                <i class="fas fa-credit-card"></i> Payment Method
                            </h4>
                            
                            <div class="payment_methods">
                                <div class="payment_method">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="ssl" 
                                           id="payment_ssl" 
                                           checked>
                                    <label for="payment_ssl" class="payment_method_label">
                                        <div class="payment_icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="payment_info">
                                            <h6>SSL Commerz</h6>
                                            <p>Pay with bKash, Nagad, Rocket, or Card</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="payment_method">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="cod" 
                                           id="payment_cod">
                                    <label for="payment_cod" class="payment_method_label">
                                        <div class="payment_icon">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="payment_info">
                                            <h6>Cash on Delivery</h6>
                                            <p>Pay when you receive the order</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="checkout_section">
                            <h4 class="section_title">
                                <i class="fas fa-sticky-note"></i> Order Notes (Optional)
                            </h4>
                            
                            <div class="form-group">
                                <textarea class="form-control" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Any special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4 col-md-12">
                    <div class="order_summary_wrapper">
                        <div class="order_summary">
                            <h4 class="summary_title">
                                <i class="fas fa-shopping-bag"></i> Order Summary
                            </h4>

                            <!-- Order Items -->
                            <div class="order_items">
                                @php $totalCartPrice = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php
                                        $product = \App\Models\Product::find($details['id']);
                                        $totalCartPrice += $details['price'] * $details['qty'];
                                    @endphp
                                    <div class="order_item">
                                        <div class="item_image">
                                            <img src="{{ $details['image'] ?? '/images/no-image.jpg' }}" 
                                                 alt="{{ $details['name'] }}">
                                        </div>
                                        <div class="item_details">
                                            <h6 class="item_name">{{ $details['name'] }}</h6>
                                            <p class="item_quantity">Qty: {{ $details['qty'] }}</p>
                                        </div>
                                        <div class="item_price">
                                            {{ App\Helpers\Frontend\ProductView::priceSign($details['price'] * $details['qty']) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="order_totals">
                                @php 
                                    $shippingType = \App\Models\StoreSettings::where('meta_name', 'shipping_type')->first();
                                    $shippingRate = \App\Models\StoreSettings::where('meta_name', 'shipping_flat_rate')->first();
                                    $shippingCost = $shippingType && $shippingType->meta_value == 'flat_rate' ? $shippingRate->meta_value : 0;
                                    $subtotal = $totalCartPrice;
                                    $grandTotal = $subtotal + $shippingCost;
                                @endphp

                                <div class="total_row">
                                    <span>Subtotal:</span>
                                    <span data-subtotal>{{ App\Helpers\Frontend\ProductView::priceSign($subtotal) }}</span>
                                </div>
                                
                                <div class="total_row">
                                    <span>Shipping:</span>
                                    <span data-shipping>{{ App\Helpers\Frontend\ProductView::priceSign($shippingCost) }}</span>
                                </div>

                                @if(Session::has('couponApplied'))
                                    @php $coupon = Session::get('couponApplied'); @endphp
                                    <div class="total_row text-success">
                                        <span>Discount ({{ $coupon['code'] }}):</span>
                                        <span>-{{ App\Helpers\Frontend\ProductView::priceSign($coupon['discount_amount'] ?? 0) }}</span>
                                    </div>
                                    @php $grandTotal -= ($coupon['discount_amount'] ?? 0); @endphp
                                @endif

                                <hr>

                                <div class="total_row total_final">
                                    <strong>Total:</strong>
                                    <strong data-total>{{ App\Helpers\Frontend\ProductView::priceSign($grandTotal) }}</strong>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <div class="place_order_section">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-lock"></i> Place Order
                                </button>
                                
                                <p class="security_note text-center mt-3">
                                    <i class="fas fa-shield-alt"></i> Your payment information is secure and encrypted
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @else
            <!-- Empty Cart Redirect -->
            <div class="empty_cart_redirect text-center py-5">
                <div class="empty_cart_icon mb-4">
                    <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                </div>
                <h3 class="empty_cart_title">Your cart is empty</h3>
                <p class="empty_cart_message text-muted mb-4">
                    You need to add items to your cart before checkout.
                </p>
                <a href="{{ route('frontend_home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
<!--checkout area end -->
@endsection

@section('cusjs')
<style>
.checkout_form_wrapper {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}

.checkout_section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.checkout_section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section_title {
    color: #333;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
    display: inline-block;
}

.payment_methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.payment_method {
    position: relative;
}

.payment_method input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.payment_method_label {
    display: flex;
    align-items: center;
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment_method input[type="radio"]:checked + .payment_method_label {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.payment_icon {
    font-size: 2em;
    color: #007bff;
    margin-right: 15px;
}

.payment_info h6 {
    margin: 0 0 5px 0;
    color: #333;
}

.payment_info p {
    margin: 0;
    color: #666;
    font-size: 0.9em;
}

.order_summary_wrapper {
    position: sticky;
    top: 20px;
}

.order_summary {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}

.order_items {
    margin-bottom: 20px;
}

.order_item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order_item:last-child {
    border-bottom: none;
}

.item_image {
    width: 50px;
    height: 50px;
    margin-right: 15px;
}

.item_image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 5px;
}

.item_details {
    flex-grow: 1;
}

.item_name {
    margin: 0 0 5px 0;
    font-size: 0.9em;
    color: #333;
}

.item_quantity {
    margin: 0;
    font-size: 0.8em;
    color: #666;
}

.item_price {
    font-weight: bold;
    color: #333;
}

.order_totals {
    margin-bottom: 25px;
}

.total_row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.total_row:last-child {
    border-bottom: none;
}

.total_final {
    font-size: 1.2em;
    color: #333;
}

.place_order_section {
    text-align: center;
}

.security_note {
    font-size: 0.8em;
    color: #666;
}

.empty_cart_redirect {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: 50px 0;
}

@media (max-width: 768px) {
    .checkout_form_wrapper,
    .order_summary {
        padding: 20px;
    }
    
    .payment_methods {
        gap: 10px;
    }
    
    .payment_method_label {
        padding: 15px;
    }
    
    .payment_icon {
        font-size: 1.5em;
        margin-right: 10px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Use saved address
    $('select[name="saved_address"]').change(function() {
        const addressId = $(this).val();
        if (addressId) {
            // Load address details and fill form
            // This would typically be an AJAX call to get address details
            console.log('Selected address ID:', addressId);
        }
    });

    // Form validation
    $('#checkout-form').submit(function(e) {
        e.preventDefault();
        
        // Basic validation
        const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'customer_address'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = $(`[name="${field}"]`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill in all required fields');
            return;
        }
        
        // Submit form
        this.submit();
    });

    // Remove validation classes on input
    $('input, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endsection

