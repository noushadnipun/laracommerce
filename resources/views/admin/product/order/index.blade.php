@extends('admin.layouts.master')

@section('site-title')
Order Management
@endsection


@section('page-content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3>{{ $stats['total_orders'] }}</h3>
                <p>Total Orders</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>{{ $stats['pending_orders'] }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h3>{{ $stats['processing_orders'] }}</h3>
                <p>Processing</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <h3>{{ $stats['shipped_orders'] }}</h3>
                <p>Shipped</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <h3>{{ $stats['delivered_orders'] }}</h3>
                <p>Delivered</p>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <h3>৳{{ number_format($stats['total_revenue'], 2) }}</h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin_product_order_index') }}">
            <div class="row">
                <div class="col-md-2">
                    <label>Order Status</label>
                    <select name="order_status" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('order_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Payment Status</label>
                    <select name="payment_status" class="form-control form-control-sm">
                        <option value="">All Payment</option>
                        <option value="Pending" {{ request('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ request('payment_status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Unpaid" {{ request('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Order Code</label>
                    <input type="text" name="order_code" class="form-control form-control-sm" 
                           value="{{ request('order_code') }}" placeholder="#OD-">
                </div>
                <div class="col-md-2">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" class="form-control form-control-sm" 
                           value="{{ request('customer_name') }}" placeholder="Customer name">
                </div>
                <div class="col-md-2">
                    <label>Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label>Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" 
                           value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Filter Orders
                    </button>
                    <a href="{{ route('admin_product_order_index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shopping-cart"></i> Order Management
                <span class="badge badge-info ml-2">{{ $getAllOrder->total() }} Orders</span>
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Order Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($getAllOrder as $order)
                    <tr class="order-row">
                        <td>
                            <strong>{{ $order->order_code }}</strong>
                            @if($order->tracking_number)
                                <br><small class="text-muted">Tracking: {{ $order->tracking_number }}</small>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->customer_name }}</strong>
                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $order->orderDetails->count() }} items</span>
                            <br><small class="text-muted">Qty: {{ $order->total_items }}</small>
                        </td>
                        <td>
                            <strong>{{ $order->formatted_final_amount }}</strong>
                            @if($order->shipping_cost > 0)
                                <br><small class="text-muted">+ ৳{{ $order->shipping_cost }} shipping</small>
                            @endif
                        </td>
                        <td>
                            <x-order.order-status-badge :status="$order->order_status" />
                        </td>
                        <td>
                            <x-order.order-status-badge :status="$order->payment_status" type="payment" />
                            <br><small class="text-muted">{{ $order->payment_type }}</small>
                        </td>
                        <td>
                            {{ $order->created_at->format('M d, Y') }}
                            <br><small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin_product_order_view', $order->id) }}" 
                                   class="btn btn-info btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($order->is_cancellable)
                                <button class="btn btn-warning btn-sm" 
                                        onclick="updateOrderStatus({{ $order->id }}, 'cancelled')" 
                                        title="Cancel Order">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                
                                @if($order->is_shippable)
                                <button class="btn btn-primary btn-sm" 
                                        onclick="updateOrderStatus({{ $order->id }}, 'shipped')" 
                                        title="Mark as Shipped">
                                    <i class="fas fa-shipping-fast"></i>
                                </button>
                                @endif
                                
                                @if($order->is_deliverable)
                                <button class="btn btn-success btn-sm" 
                                        onclick="updateOrderStatus({{ $order->id }}, 'delivered')" 
                                        title="Mark as Delivered">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders found</h5>
                            <p class="text-muted">No orders match your current filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($getAllOrder->hasPages())
        <div class="card-footer">
            {{ $getAllOrder->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Order Status Update Modal -->
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

@endsection

@section('cusjs')

<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}
.stats-card h3 {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0;
}
.stats-card p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}
.order-status-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 12px;
}
.filter-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.order-row:hover {
    background-color: #f8f9fa;
}
.action-buttons .btn {
    margin: 2px;
}
</style>

<script>
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

// Auto-submit form on status change
$('#statusUpdateForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            location.reload();
        },
        error: function(xhr) {
            alert('Error updating order status: ' + xhr.responseText);
        }
    });
});
</script>
@endsection