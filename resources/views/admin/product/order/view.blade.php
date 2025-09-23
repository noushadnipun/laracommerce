@extends('admin.layouts.master')

@section('site-title')
Order Details - {{ $order->order_code }}
@endsection



@section('page-content')
<div class="container-fluid">
    <!-- Order Header -->
    <div class="order-header">
        <div class="row">
            <div class="col-md-8">
                <h2><i class="fas fa-shopping-cart"></i> Order #{{ $order->order_code }}</h2>
                <p class="mb-0">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="col-md-4 text-right">
                <h3>{{ $order->formatted_final_amount }}</h3>
                <span class="badge badge-lg {{ $order->status_badge_class }}">{{ $order->status_label }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Billing Address</h6>
                            <address>
                                <strong>{{ $order->customer_name }}</strong><br>
                                {{ $order->customer_address }}<br>
                                {{ $order->customer_thana }}, {{ $order->customer_city }}<br>
                                {{ $order->customer_postal_code }}, {{ $order->customer_country }}<br>
                                <i class="fas fa-phone"></i> {{ $order->customer_phone }}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Order Details</h6>
                            <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_type }}</p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge badge-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </p>
                            @if($order->tracking_number)
                            <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                            @endif
                            @if($order->shipping_carrier)
                            <p><strong>Shipping Carrier:</strong> {{ $order->shipping_carrier }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box"></i> Order Items ({{ $order->orderDetails->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Attributes</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->getFeaturedImageUrl())
                                                <img src="{{ $item->product->getFeaturedImageUrl() }}" 
                                                     class="product-image mr-3" alt="Product">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->product->title ?? 'Product Not Found' }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->attribute && is_array($item->attribute))
                                            @foreach($item->attribute as $key => $value)
                                                <span class="badge badge-light">{{ $key }}: {{ $value }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No attributes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $item->qty }}</span>
                                    </td>
                                    <td>৳{{ number_format($item->price / $item->qty, 2) }}</td>
                                    <td><strong>৳{{ number_format($item->price, 2) }}</strong></td>
                                    <td>
                                        @if(in_array($order->order_status, ['pending','processing']))
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="openPartialCancel({{ $order->id }}, {{ $item->id }}, {{ (int)$item->qty }})">
                                            <i class="fas fa-cut"></i> Partial Cancel
                                        </button>
                                        @endif
                                        @if($item->product_id)
                                        <a href="{{ route('admin_inventory_show', $item->product_id) }}" target="_blank"
                                           class="btn btn-sm btn-outline-secondary mt-1">
                                            <i class="fas fa-warehouse"></i> Stock
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->note || $order->admin_notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note"></i> Order Notes
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->note)
                    <div class="mb-3">
                        <h6>Customer Notes:</h6>
                        <p class="text-muted">{{ $order->note }}</p>
                    </div>
                    @endif
                    @if($order->admin_notes)
                    <div>
                        <h6>Admin Notes:</h6>
                        <p class="text-muted admin-notes-content">{{ $order->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Order Actions & Status -->
        <div class="col-md-4">
            <!-- Order Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-timeline"></i> Order Status Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="status-timeline">
                        @php
                            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                            $currentStatus = $order->order_status;
                        @endphp
                        
                        @foreach($statuses as $status)
                        <div class="status-item {{ $status === $currentStatus ? 'active' : (array_search($status, $statuses) < array_search($currentStatus, $statuses) ? 'completed' : '') }}">
                            <h6 class="mb-1">{{ ucfirst($status) }}</h6>
                            @if($status === $currentStatus)
                                <small class="text-success">Current Status</small>
                            @elseif(array_search($status, $statuses) < array_search($currentStatus, $statuses))
                                <small class="text-primary">Completed</small>
                            @else
                                <small class="text-muted">Pending</small>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs"></i> Order Actions
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Quick Status Actions -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Quick Status Update</h6>
                        <div class="btn-group-vertical w-100" role="group">
                            @if($order->is_cancellable)
                            <button class="btn btn-outline-danger mb-2" 
                                    onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                <i class="fas fa-times-circle"></i> Cancel Order
                            </button>
                            @endif
                            
                            @if($order->is_shippable)
                            <button class="btn btn-outline-primary mb-2" 
                                    onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                                <i class="fas fa-shipping-fast"></i> Mark as Shipped
                            </button>
                            @endif
                            
                            @if($order->is_deliverable)
                            <button class="btn btn-outline-success mb-2" 
                                    onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
                                <i class="fas fa-check-circle"></i> Mark as Delivered
                            </button>
                            @endif
                            
                            <button class="btn btn-outline-info mb-2" 
                                    onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                <i class="fas fa-cog"></i> Mark as Processing
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Actions -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Advanced Actions</h6>
                        <div class="btn-group-vertical w-100" role="group">
                            <button class="btn btn-outline-secondary mb-2" 
                                    onclick="addAdminNotes({{ $order->id }})">
                                <i class="fas fa-sticky-note"></i> Add Admin Notes
                            </button>
                            
                            <button class="btn btn-outline-warning mb-2" 
                                    onclick="updatePaymentStatus({{ $order->id }})">
                                <i class="fas fa-credit-card"></i> Update Payment Status
                            </button>
                            
                            <button class="btn btn-outline-info mb-2" 
                                    onclick="sendNotification({{ $order->id }})">
                                <i class="fas fa-envelope"></i> Send Notification
                            </button>
                            
                            <button class="btn btn-outline-dark mb-2" 
                                    onclick="printOrder({{ $order->id }})">
                                <i class="fas fa-print"></i> Print Order
                            </button>
                        </div>
                    </div>

                    <!-- Order Statistics -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="text-muted mb-2">Order Statistics</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h5 class="mb-0 text-primary">{{ $order->orderDetails->count() }}</h5>
                                    <small class="text-muted">Items</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="mb-0 text-success">{{ $order->orderDetails->sum('qty') }}</h5>
                                <small class="text-muted">Total Qty</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span>৳{{ number_format($order->total_amount - $order->shipping_cost, 2) }}</span>
                    </div>
                    @if($order->shipping_cost > 0)
                    <div class="d-flex justify-content-between">
                        <span>Shipping:</span>
                        <span>৳{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    @endif
                    @if($order->coupone_discount > 0)
                    <div class="d-flex justify-content-between text-success">
                        <span>Discount:</span>
                        <span>-৳{{ number_format($order->coupone_discount, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>{{ $order->formatted_final_amount }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusUpdateForm" method="POST" action="{{ route('admin_order_update_status') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="statusOrderId">
                    <input type="hidden" name="status" id="statusValue">
                    
                    <div class="form-group">
                        <label>New Status</label>
                        <input type="text" id="statusDisplay" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Add notes about this status change..."></textarea>
                    </div>
                    
                    <div class="form-group" id="trackingGroup" style="display: none;">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" 
                               placeholder="Enter tracking number">
                    </div>
                    
                    <div class="form-group" id="carrierGroup" style="display: none;">
                        <label>Shipping Carrier</label>
                        <input type="text" name="shipping_carrier" class="form-control" 
                               placeholder="e.g., Sundarban, RedX, eCourier">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Partial Cancel Modal -->
<div class="modal fade" id="partialCancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-cut"></i> Partial Cancel Line</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="partialCancelForm" method="POST" action="{{ route('admin_order_partial_cancel') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="pcOrderId">
                    <input type="hidden" name="order_detail_id" id="pcOrderDetailId">
                    <div class="form-group">
                        <label>Quantity to cancel</label>
                        <input type="number" name="quantity" id="pcQty" class="form-control" min="1" value="1" required>
                        <small id="pcQtyHelp" class="form-text text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label>Reason (optional)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Reason for partial cancellation..."></textarea>
                    </div>
                    <div class="alert alert-warning mb-0">
                        This will restock the cancelled quantity and adjust order totals.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-cut"></i> Confirm Cancel</button>
                </div>
            </form>
        </div>
    </div>
    </div>

<!-- Admin Notes Modal -->
<div class="modal fade" id="adminNotesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sticky-note"></i> Add Admin Notes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="adminNotesForm" method="POST" action="{{ route('admin_order_add_notes') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="notesOrderId">
                    
                    <div class="form-group">
                        <label>Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="4" 
                                  placeholder="Add internal notes about this order..."></textarea>
                        <small class="form-text text-muted">These notes are only visible to admin users.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card"></i> Update Payment Status
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="paymentStatusForm" method="POST" action="{{ route('admin_order_change_payment_status') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="paymentOrderId">
                    
                    <div class="form-group">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-control" required>
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Refunded">Refunded</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Add notes about payment status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-credit-card"></i> Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i> Send Notification
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="notificationForm" method="POST" action="{{ route('admin_order_send_notification') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="notificationOrderId">
                    
                    <div class="form-group">
                        <label>Notification Type</label>
                        <select name="type" class="form-control" required>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" 
                               placeholder="Notification subject...">
                    </div>
                    
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="4" 
                                  placeholder="Notification message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('cusjs')
<style>
.order-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}
.status-timeline {
    position: relative;
    padding-left: 30px;
}
.status-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.status-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 20px;
}
.status-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #6c757d;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}
.status-item.active::before {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}
.status-item.completed::before {
    background: #007bff;
    box-shadow: 0 0 0 2px #007bff;
}
.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
.action-buttons .btn {
    margin: 2px;
}
</style>
<script>
// Status Update Functions
function updateOrderStatus(orderId, status) {
    document.getElementById('statusOrderId').value = orderId;
    document.getElementById('statusValue').value = status;
    document.getElementById('statusDisplay').value = status.charAt(0).toUpperCase() + status.slice(1);
    
    // Show/hide tracking fields for shipped status
    if (status === 'shipped') {
        document.getElementById('trackingGroup').style.display = 'block';
        document.getElementById('carrierGroup').style.display = 'block';
    } else {
        document.getElementById('trackingGroup').style.display = 'none';
        document.getElementById('carrierGroup').style.display = 'none';
    }
    
    $('#statusUpdateModal').modal('show');
}

function openPartialCancel(orderId, orderDetailId, maxQty) {
    document.getElementById('pcOrderId').value = orderId;
    document.getElementById('pcOrderDetailId').value = orderDetailId;
    const qtyInput = document.getElementById('pcQty');
    qtyInput.max = maxQty;
    qtyInput.value = 1;
    const help = document.getElementById('pcQtyHelp');
    if (help) help.textContent = `Max cancellable quantity: ${maxQty}`;
    $('#partialCancelModal').modal('show');
}

function addAdminNotes(orderId) {
    document.getElementById('notesOrderId').value = orderId;
    $('#adminNotesModal').modal('show');
}

function updatePaymentStatus(orderId) {
    document.getElementById('paymentOrderId').value = orderId;
    $('#paymentStatusModal').modal('show');
}

function sendNotification(orderId) {
    document.getElementById('notificationOrderId').value = orderId;
    $('#notificationModal').modal('show');
}

function printOrder(orderId) {
    // Create print-friendly window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>Order #{{ $order->order_code }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .section { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Order #{{ $order->order_code }}</h1>
                <p>Date: {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            
            <div class="section">
                <h3>Customer Information</h3>
                <p><strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_address }}<br>
                {{ $order->customer_thana }}, {{ $order->customer_city }}<br>
                {{ $order->customer_postal_code }}, {{ $order->customer_country }}<br>
                Phone: {{ $order->customer_phone }}</p>
            </div>
            
            <div class="section">
                <h3>Order Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderDetails as $item)
                        <tr>
                            <td>{{ $item->product->title ?? 'Product Not Found' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>৳{{ number_format($item->price / $item->qty, 2) }}</td>
                            <td>৳{{ number_format($item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="section">
                <h3>Order Summary</h3>
                <p><strong>Total: {{ $order->formatted_final_amount }}</strong></p>
                <p>Payment Status: {{ $order->payment_status }}</p>
                <p>Order Status: {{ $order->status_label }}</p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Form Submission Handlers
$('#statusUpdateForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                
                // Update order status badge dynamically
                if (response.order) {
                    $('.badge.badge-lg').removeClass().addClass('badge badge-lg ' + response.order.status_badge_class).text(response.order.status_label);
                    
                    // Update tracking info if available
                    if (response.order.tracking_number) {
                        $('p:contains("Tracking Number:")').next().text(response.order.tracking_number);
                    }
                    if (response.order.shipping_carrier) {
                        $('p:contains("Shipping Carrier:")').next().text(response.order.shipping_carrier);
                    }
                }
                
                // Close modal
                $('#statusUpdateModal').modal('hide');
            } else {
                showNotification(response.message || 'Error updating order status', 'error');
            }
            submitBtn.html(originalText).prop('disabled', false);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating order status';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }
            showNotification(errorMessage, 'error');
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

$('#adminNotesForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Adding...').prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                
                // Update admin notes section dynamically
                if (response.order && response.order.admin_notes) {
                    $('.admin-notes-content').text(response.order.admin_notes);
                }
                
                // Close modal
                $('#adminNotesModal').modal('hide');
            } else {
                showNotification(response.message || 'Error adding admin notes', 'error');
            }
            submitBtn.html(originalText).prop('disabled', false);
        },
        error: function(xhr) {
            let errorMessage = 'Error adding admin notes';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }
            showNotification(errorMessage, 'error');
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

$('#paymentStatusForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                
                // Update payment status badge dynamically
                if (response.order) {
                    const paymentStatusBadge = $('p:contains("Payment Status:")').find('.badge');
                    paymentStatusBadge.removeClass().addClass('badge badge-' + (response.order.payment_status === 'Paid' ? 'success' : 'warning')).text(response.order.payment_status);
                }
                
                // Close modal
                $('#paymentStatusModal').modal('hide');
            } else {
                showNotification(response.message || 'Error updating payment status', 'error');
            }
            submitBtn.html(originalText).prop('disabled', false);
        },
        error: function(xhr) {
            let errorMessage = 'Error updating payment status';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }
            showNotification(errorMessage, 'error');
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

$('#notificationForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                
                // Close modal
                $('#notificationModal').modal('hide');
            } else {
                showNotification(response.message || 'Error sending notification', 'error');
            }
            submitBtn.html(originalText).prop('disabled', false);
        },
        error: function(xhr) {
            let errorMessage = 'Error sending notification';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }
            showNotification(errorMessage, 'error');
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Notification System
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    
    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

// Dynamic order status updates - no auto-refresh needed
// All updates are now handled via AJAX without page reloads

// Partial cancel submit
$('#partialCancelForm').on('submit', function(e) {
    e.preventDefault();
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(response){
            showNotification(response.message || 'Line cancelled', 'success');
            $('#partialCancelModal').modal('hide');
            location.reload();
        },
        error: function(xhr){
            let msg = 'Failed to cancel line';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            else if (xhr.responseText) msg = xhr.responseText;
            showNotification(msg, 'error');
        },
        complete: function(){
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});
</script>
@endsection