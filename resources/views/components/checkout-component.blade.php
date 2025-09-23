<div class="checkout-component">
    @if(!$isValid)
        <div class="alert alert-warning text-center">
            <h4>Invalid Cart</h4>
            <p>Your cart is empty or contains invalid items.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Start Shopping</a>
        </div>
    @else
        <form method="POST" action="{{ route('frontend_checkout_done') }}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name *</label>
                                        <input type="text" name="name" class="form-control" 
                                               value="{{ $user->name ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ $user->email ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone *</label>
                                        <input type="tel" name="phone" class="form-control" 
                                               value="{{ $user->phone ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="Dhaka">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address *</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Thana</label>
                                        <input type="text" name="thana" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" value="1000">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" name="country" class="form-control" value="Bangladesh">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shipping</label>
                                        <select name="shippingCost" class="form-control">
                                            <option value="50">Standard - 50</option>
                                            <option value="100">Express - 100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="ssl" id="ssl" checked>
                                <label class="form-check-label" for="ssl">
                                    SSL Commerz (bKash, Nagad, Card)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cod">
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery
                                </label>
                            </div>
                            <div class="form-group mt-3">
                                <label>Order Notes (Optional)</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            @foreach($cartData['items'] as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <span>{{ App\Helpers\Frontend\ProductView::priceSign($item['total']) }}</span>
                                </div>
                            @endforeach
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>{{ App\Helpers\Frontend\ProductView::priceSign($cartData['grand_total']) }}</strong>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-3">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>