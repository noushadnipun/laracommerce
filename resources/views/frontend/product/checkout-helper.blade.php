@extends('frontend.layouts.master')

@section('title', 'Checkout')

@section('style')
<style>
.checkout-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 30px;
}
.payment-method {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.payment-method:hover {
    border-color: #007bff;
    background: #f8f9ff;
}
.payment-method.selected {
    border-color: #007bff;
    background: #e3f2fd;
}
.payment-method input[type="radio"] {
    margin-right: 10px;
}
.order-summary {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}
.summary-item:last-child {
    border-bottom: none;
    font-weight: bold;
    font-size: 1.1rem;
}
.form-group label {
    font-weight: 600;
    color: #495057;
}
.required {
    color: #dc3545;
}
</style>
@endsection

@section('page-content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-credit-card"></i> Checkout
            </h2>
        </div>
    </div>

    <form action="{{ route('frontend_checkout_done') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Billing Information -->
            <div class="col-lg-8">
                <div class="checkout-section">
                    <h4 class="mb-4">
                        <i class="fas fa-user"></i> Billing Information
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Full Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ Auth::user()->name ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ Auth::user()->phone ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="address">Address <span class="required">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="thana">Thana <span class="required">*</span></label>
                                <input type="text" class="form-control" id="thana" name="thana" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="city">City <span class="required">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="note">Order Notes (Optional)</label>
                        <textarea class="form-control" id="note" name="note" rows="3" 
                                  placeholder="Any special instructions for your order..."></textarea>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="checkout-section">
                    <h4 class="mb-4">
                        <i class="fas fa-credit-card"></i> Payment Method
                    </h4>
                    
                    @foreach($paymentMethods as $key => $method)
                    <div class="payment-method" onclick="selectPaymentMethod('{{ $key }}')">
                        <input type="radio" name="payment_method" value="{{ $key }}" id="payment_{{ $key }}" 
                               {{ $key === 'cash' ? 'checked' : '' }}>
                        <label for="payment_{{ $key }}" class="mb-0">
                            <i class="{{ $method['icon'] }}"></i> {{ $method['name'] }}
                        </label>
                        <p class="text-muted mb-0 mt-1">{{ $method['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="mb-4">
                        <i class="fas fa-shopping-bag"></i> Order Summary
                    </h4>
                    
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span>{{ $checkoutSummary['formatted_subtotal'] }}</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Shipping:</span>
                        <span>{{ $checkoutSummary['formatted_shipping'] }}</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Tax:</span>
                        <span>{{ $checkoutSummary['formatted_tax'] }}</span>
                    </div>
                    
                    @if($checkoutSummary['coupon_discount'] > 0)
                    <div class="summary-item text-success">
                        <span>Discount:</span>
                        <span>-{{ $checkoutSummary['formatted_discount'] }}</span>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="summary-item">
                        <span>Total:</span>
                        <span class="text-primary">{{ $checkoutSummary['formatted_final'] }}</span>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i> Your payment information is secure and encrypted
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
function selectPaymentMethod(method) {
    // Remove selected class from all payment methods
    $('.payment-method').removeClass('selected');
    
    // Add selected class to clicked method
    $(`input[value="${method}"]`).closest('.payment-method').addClass('selected');
    
    // Check the radio button
    $(`input[value="${method}"]`).prop('checked', true);
}

// Form submission
$('#checkoutForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state
    $('#placeOrderBtn').html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                // Redirect to success page
                window.location.href = '{{ route("frontend_checkout_success") }}?order=' + response.order_code;
            } else {
                alert(response.message);
                $('#placeOrderBtn').html('<i class="fas fa-lock"></i> Place Order').prop('disabled', false);
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred while processing your order.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                errorMessage = errors.join('\n');
            }
            
            alert(errorMessage);
            $('#placeOrderBtn').html('<i class="fas fa-lock"></i> Place Order').prop('disabled', false);
        }
    });
});

// Initialize payment method selection
$(document).ready(function() {
    $('input[name="payment_method"]:checked').closest('.payment-method').addClass('selected');
});
</script>
@endsection





