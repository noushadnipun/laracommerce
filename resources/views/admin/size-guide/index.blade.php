@extends('admin.layouts.master')

@section('site-title')
Size Guides
@endsection

@section('page-title')
Size Guide Management
@endsection

@section('page-content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-ruler"></i> Size Guides ({{$sizeGuides->count()}})
            </h3>
            <a href="{{route('admin_size_guide_create')}}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Add New Size Guide
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Product</th>
                        <th>Title</th>
                        <th>Size Type</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sizeGuides as $guide)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{$guide->product->getFeaturedImageUrl()}}" alt="{{$guide->product->title}}" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" 
                                         onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'">
                                    <div class="ml-2">
                                        <strong>{{$guide->product->title}}</strong>
                                        <br>
                                        <small class="text-muted">{{$guide->product->sku}}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{$guide->title}}</strong>
                                @if($guide->description)
                                    <br>
                                    <small class="text-muted">{{Str::limit($guide->description, 50)}}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ucfirst($guide->size_type)}}</span>
                            </td>
                            <td>
                                @if($guide->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{$guide->created_at->format('M d, Y')}}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{route('admin_size_guide_edit', $guide->id)}}" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-{{$guide->is_active ? 'warning' : 'success'}}" 
                                            onclick="toggleStatus({{$guide->id}})" 
                                            title="{{$guide->is_active ? 'Deactivate' : 'Activate'}}">
                                        <i class="fas fa-{{$guide->is_active ? 'pause' : 'play'}}"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" 
                                            onclick="deleteGuide({{$guide->id}})" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-ruler" style="font-size: 48px; color: #ddd;"></i>
                                <h5 class="mt-2">No size guides found</h5>
                                <p class="text-muted">Create your first size guide to get started.</p>
                                <a href="{{route('admin_size_guide_create')}}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Size Guide
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@endsection

@section('cusjs')
<script>
function toggleStatus(id) {
    if (confirm('Are you sure you want to change the status of this size guide?')) {
        $.ajax({
            url: '/admin/size-guides/' + id + '/toggle-status',
            method: 'POST',
            data: {
                _token: '{{csrf_token()}}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error updating status');
            }
        });
    }
}

function deleteGuide(id) {
    if (confirm('Are you sure you want to delete this size guide? This action cannot be undone.')) {
        $.ajax({
            url: '/admin/size-guides/' + id,
            method: 'DELETE',
            data: {
                _token: '{{csrf_token()}}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error deleting size guide');
            }
        });
    }
}

// Auto-dismiss alerts
setTimeout(function() {
    $('.alert').fadeOut();
}, 5000);
</script>
@endsection





