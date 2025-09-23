@extends('admin.layouts.master')

@section('page-content')
<div class="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Inventory Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin_inventory_index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">{{ $product->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Product Info -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Product Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    @if($product->getFeaturedImageUrl())
                                        <img src="{{ $product->getFeaturedImageUrl() }}" alt="{{ $product->title }}" class="img-fluid">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h4>{{ $product->title }}</h4>
                                    <p><strong>Code:</strong> {{ $product->code }}</p>
                                    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                                    <p><strong>Brand:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                                    <p><strong>Regular Price:</strong> ${{ number_format($product->regular_price, 2) }}</p>
                                    @if($product->sale_price)
                                        <p><strong>Sale Price:</strong> ${{ number_format($product->sale_price, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#addStockModal">
                                <i class="fas fa-plus"></i> Add Stock
                            </button>
                            <button class="btn btn-warning btn-block mb-2" data-toggle="modal" data-target="#removeStockModal">
                                <i class="fas fa-minus"></i> Remove Stock
                            </button>
                            <button class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#updateInventoryModal">
                                <i class="fas fa-edit"></i> Update Inventory
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Inventory Status -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Current Stock</span>
                            <span class="info-box-number">{{ $product->inventory ? $product->inventory->current_stock : 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-warehouse"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Stock</span>
                            <span class="info-box-number">{{ $product->inventory ? $product->inventory->total_stock : 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Low Stock Threshold</span>
                            <span class="info-box-number">{{ $product->inventory ? $product->inventory->low_stock_threshold : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Value</span>
                            <span class="info-box-number">${{ $product->inventory ? number_format($product->inventory->total_value, 2) : '0.00' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Movements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock Movements</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Previous Stock</th>
                                    <th>New Stock</th>
                                    <th>User</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $movement->type_badge_class }}">
                                                {{ $movement->type_label }}
                                            </span>
                                        </td>
                                        <td>{{ $movement->quantity }}</td>
                                        <td>{{ $movement->previous_stock }}</td>
                                        <td>{{ $movement->new_stock }}</td>
                                        <td>{{ $movement->user->name ?? 'System' }}</td>
                                        <td>{{ $movement->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No stock movements found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $stockMovements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin_inventory_add_stock', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Quantity to Add</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Reason for adding stock..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Stock Modal -->
<div class="modal fade" id="removeStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin_inventory_remove_stock', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Remove Stock</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Quantity to Remove</label>
                        <input type="number" name="quantity" class="form-control" min="1" max="{{ $product->inventory ? $product->inventory->current_stock : 0 }}" required>
                        <small class="text-muted">Available stock: {{ $product->inventory ? $product->inventory->current_stock : 0 }}</small>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Reason for removing stock..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Remove Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Inventory Modal -->
<div class="modal fade" id="updateInventoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin_inventory_update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current Stock</label>
                        <input type="number" name="current_stock" class="form-control" value="{{ $product->inventory ? $product->inventory->current_stock : 0 }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="{{ $product->inventory ? $product->inventory->low_stock_threshold : 10 }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Unit Cost</label>
                        <input type="number" name="unit_cost" class="form-control" value="{{ $product->inventory ? $product->inventory->unit_cost : 0 }}" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Reason for update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update Inventory</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection





