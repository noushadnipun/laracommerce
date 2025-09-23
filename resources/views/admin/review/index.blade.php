@extends('admin.layouts.master')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product Reviews Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reviews</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Reviews</p>
                            <h4 class="mb-0">{{$stats['total']}}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <i class="fas fa-comments font-size-18 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Pending Reviews</p>
                            <h4 class="mb-0 text-warning">{{$stats['pending']}}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <i class="fas fa-clock font-size-18 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Approved Reviews</p>
                            <h4 class="mb-0 text-success">{{$stats['approved']}}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <i class="fas fa-check-circle font-size-18 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Average Rating</p>
                            <h4 class="mb-0">{{number_format($stats['average_rating'], 1)}}/5</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-soft">
                                <i class="fas fa-star font-size-18 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{route('admin_review_index')}}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Reviews</option>
                                <option value="pending" {{request('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                                <option value="approved" {{request('status') == 'approved' ? 'selected' : ''}}>Approved</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                <option value="">All Ratings</option>
                                <option value="5" {{request('rating') == '5' ? 'selected' : ''}}>5 Stars</option>
                                <option value="4" {{request('rating') == '4' ? 'selected' : ''}}>4 Stars</option>
                                <option value="3" {{request('rating') == '3' ? 'selected' : ''}}>3 Stars</option>
                                <option value="2" {{request('rating') == '2' ? 'selected' : ''}}>2 Stars</option>
                                <option value="1" {{request('rating') == '1' ? 'selected' : ''}}>1 Star</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by reviewer name, email, or product..." value="{{request('search')}}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="created_at" {{request('sort') == 'created_at' ? 'selected' : ''}}>Date</option>
                                <option value="rating" {{request('sort') == 'rating' ? 'selected' : ''}}>Rating</option>
                                <option value="name" {{request('sort') == 'name' ? 'selected' : ''}}>Name</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Reviews ({{$reviews->total()}} found)</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" id="bulk-approve-btn" disabled>
                                <i class="fas fa-check"></i> Approve Selected
                            </button>
                            <button type="button" class="btn btn-danger" id="bulk-reject-btn" disabled>
                                <i class="fas fa-times"></i> Reject Selected
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <div class="checkbox-container">
                                            <input type="checkbox" id="select-all" class="custom-checkbox">
                                        </div>
                                    </th>
                                    <th>Reviewer</th>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="checkbox-container">
                                            <input type="checkbox" class="custom-checkbox review-checkbox" value="{{$review->id}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{$review->name}}</strong><br>
                                            <small class="text-muted">{{$review->email}}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('admin_product_edit', $review->product->id)}}" class="text-decoration-none">
                                            {{$review->product->title}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">{{$review->rating}}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->comment)
                                            <div class="comment-preview" style="max-width: 200px;">
                                                {{Str::limit($review->comment, 100)}}
                                            </div>
                                        @else
                                            <span class="text-muted">No comment</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{$review->created_at->format('M d, Y')}}<br>
                                        <small class="text-muted">{{$review->created_at->format('h:i A')}}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{route('admin_review_edit', $review->id)}}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$review->is_approved)
                                                <button class="btn btn-outline-success approve-btn" data-id="{{$review->id}}" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-outline-danger reject-btn" data-id="{{$review->id}}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button class="btn btn-outline-danger delete-btn" data-id="{{$review->id}}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-comment-slash" style="font-size: 48px; color: #ddd;"></i>
                                        <h5 class="mt-2">No reviews found</h5>
                                        <p class="text-muted">There are no reviews matching your criteria.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{$reviews->appends(request()->query())->links()}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Custom CSS -->
<style>
.comment-preview {
    word-wrap: break-word;
    white-space: pre-wrap;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.avatar-sm {
    width: 3rem;
    height: 3rem;
}

.bg-soft {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.bg-warning.bg-soft {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-success.bg-soft {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.bg-info.bg-soft {
    background-color: rgba(23, 162, 184, 0.1) !important;
}

/* Custom Checkbox Styling */
.custom-checkbox {
    width: 18px;
    height: 18px;
    margin: 0;
    vertical-align: middle;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    border: 2px solid #dee2e6;
    border-radius: 3px;
    appearance: none;
    color-adjust: exact;
    cursor: pointer;
    position: relative;
    display: inline-block;
}

.custom-checkbox:checked {
    background-color: #007bff;
    border-color: #007bff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
}

.custom-checkbox:indeterminate {
    background-color: #007bff;
    border-color: #007bff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e");
}

.custom-checkbox:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.custom-checkbox:hover {
    border-color: #007bff;
}

/* Table row hover effect */
tbody tr:hover {
    background-color: #f8f9fa;
}

/* Checkbox column styling */
th:first-child, td:first-child {
    text-align: center;
    vertical-align: middle;
    width: 50px;
    padding: 8px 4px;
}

/* Checkbox container styling */
.checkbox-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
</style>

<!-- Custom JavaScript -->
<script>
// Check if jQuery is available
if (typeof jQuery === 'undefined') {
    // Fallback to vanilla JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        initializeVanillaJS();
    });
} else {
    // jQuery is available
    // Wait for jQuery to be available
    (function($) {
        'use strict';
        
        // Initialize when document is ready
        $(document).ready(function() {
        
        // Initialize checkbox functionality
        initializeCheckboxes();
        
    
    function initializeCheckboxes() {
        // Select all checkbox
        $('#select-all').off('change').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.review-checkbox').prop('checked', isChecked);
            updateBulkButtons();
        });
        
        // Individual checkboxes
        $('.review-checkbox').off('change').on('change', function() {
            updateBulkButtons();
            updateSelectAllState();
        });
    }
    
    // Update bulk action buttons
    function updateBulkButtons() {
        var checkedCount = $('.review-checkbox:checked').length;
        var totalCount = $('.review-checkbox').length;
        
        $('#bulk-approve-btn, #bulk-reject-btn').prop('disabled', checkedCount === 0);
        
        // Update button text with count
        if (checkedCount > 0) {
            $('#bulk-approve-btn').html('<i class="fas fa-check"></i> Approve (' + checkedCount + ')');
            $('#bulk-reject-btn').html('<i class="fas fa-times"></i> Reject (' + checkedCount + ')');
        } else {
            $('#bulk-approve-btn').html('<i class="fas fa-check"></i> Approve Selected');
            $('#bulk-reject-btn').html('<i class="fas fa-times"></i> Reject Selected');
        }
    }
    
    // Update select all checkbox state
    function updateSelectAllState() {
        var totalCheckboxes = $('.review-checkbox').length;
        var checkedCheckboxes = $('.review-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all').prop('indeterminate', true);
        }
    }
    
    // Bulk approve (using event delegation)
    $(document).on('click', '#bulk-approve-btn', function() {
        var selectedIds = $('.review-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedIds.length === 0) {
            showNotification('Please select at least one review to approve.', 'error');
            return;
        }
        
        var $btn = $(this);
        
        if (confirm('Are you sure you want to approve ' + selectedIds.length + ' review(s)?')) {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');
            
            $.ajax({
                url: '{{route("admin_review_bulk_approve")}}',
                method: 'POST',
                data: {
                    review_ids: selectedIds,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        // Reset checkboxes
                        $('.review-checkbox').prop('checked', false);
                        $('#select-all').prop('checked', false).prop('indeterminate', false);
                        updateBulkButtons();
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    showNotification(response ? response.message : 'Something went wrong!', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Approve Selected');
                }
            });
        }
    });
    
    // Bulk reject (using event delegation)
    $(document).on('click', '#bulk-reject-btn', function() {
        var selectedIds = $('.review-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedIds.length === 0) {
            showNotification('Please select at least one review to reject.', 'error');
            return;
        }
        
        var $btn = $(this);
        
        if (confirm('Are you sure you want to reject and delete ' + selectedIds.length + ' review(s)? This action cannot be undone.')) {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Rejecting...');
            
            $.ajax({
                url: '{{route("admin_review_bulk_reject")}}',
                method: 'POST',
                data: {
                    review_ids: selectedIds,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        // Reset checkboxes
                        $('.review-checkbox').prop('checked', false);
                        $('#select-all').prop('checked', false).prop('indeterminate', false);
                        updateBulkButtons();
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    showNotification(response ? response.message : 'Something went wrong!', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-times"></i> Reject Selected');
                }
            });
        }
    });
    
    // Approve individual review (using event delegation)
    $(document).on('click', '.approve-btn', function() {
        var reviewId = $(this).data('id');
        var $btn = $(this);
        
        if (confirm('Are you sure you want to approve this review?')) {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '{{route("admin_review_approve", ":id")}}'.replace(':id', reviewId),
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    showNotification(response ? response.message : 'Something went wrong!', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
                }
            });
        }
    });
    
    // Reject individual review (using event delegation)
    $(document).on('click', '.reject-btn', function() {
        var reviewId = $(this).data('id');
        var $btn = $(this);
        
        if (confirm('Are you sure you want to reject and delete this review? This action cannot be undone.')) {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '{{route("admin_review_reject", ":id")}}'.replace(':id', reviewId),
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    showNotification(response ? response.message : 'Something went wrong!', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-times"></i>');
                }
            });
        }
    });
    
    // Delete individual review (using event delegation)
    $(document).on('click', '.delete-btn', function() {
        var reviewId = $(this).data('id');
        var $btn = $(this);
        
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '{{route("admin_review_delete", ":id")}}'.replace(':id', reviewId),
                method: 'DELETE',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    showNotification(response ? response.message : 'Something went wrong!', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });
    
    // Notification system
    function showNotification(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var notification = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
            '<strong>' + (type === 'success' ? 'Success!' : 'Error!') + '</strong> ' + message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>';
        
        $('body').append(notification);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
    
    }); // End of $(document).ready
    
    })(jQuery); // End of jQuery wrapper
    
} // End of jQuery check

// Vanilla JavaScript fallback
function initializeVanillaJS() {
    
    // Select all checkbox
    var selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            var isChecked = this.checked;
            var checkboxes = document.querySelectorAll('.review-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
            updateBulkButtonsVanilla();
        });
    }
    
    // Individual checkboxes
    var checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateBulkButtonsVanilla();
            updateSelectAllStateVanilla();
        });
    });
    
    // Approve individual review
    document.addEventListener('click', function(e) {
        if (e.target.closest('.approve-btn')) {
            var btn = e.target.closest('.approve-btn');
            var reviewId = btn.getAttribute('data-id');
            
            if (confirm('Are you sure you want to approve this review?')) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch('{{route("admin_review_approve", ":id")}}'.replace(':id', reviewId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotificationVanilla(data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    showNotificationVanilla('Something went wrong!', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                });
            }
        }
    });
    
    // Reject individual review
    document.addEventListener('click', function(e) {
        if (e.target.closest('.reject-btn')) {
            var btn = e.target.closest('.reject-btn');
            var reviewId = btn.getAttribute('data-id');
            
            if (confirm('Are you sure you want to reject and delete this review? This action cannot be undone.')) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch('{{route("admin_review_reject", ":id")}}'.replace(':id', reviewId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotificationVanilla(data.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    showNotificationVanilla('Something went wrong!', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-times"></i>';
                });
            }
        }
    });
    
    // Bulk approve functionality
    var bulkApproveBtn = document.getElementById('bulk-approve-btn');
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            var selectedIds = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(function(checkbox) {
                return checkbox.value;
            });
            
            if (selectedIds.length === 0) {
                showNotificationVanilla('Please select at least one review to approve.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to approve ' + selectedIds.length + ' review(s)?')) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';
                
                fetch('{{route("admin_review_bulk_approve")}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    body: JSON.stringify({
                        review_ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotificationVanilla(data.message, 'success');
                        // Reset checkboxes
                        document.querySelectorAll('.review-checkbox').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        var selectAllCheckbox = document.getElementById('select-all');
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                            selectAllCheckbox.indeterminate = false;
                        }
                        updateBulkButtonsVanilla();
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    showNotificationVanilla('Something went wrong!', 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check"></i> Approve Selected';
                });
            }
        });
    }
    
    // Bulk reject functionality
    var bulkRejectBtn = document.getElementById('bulk-reject-btn');
    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function() {
            var selectedIds = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(function(checkbox) {
                return checkbox.value;
            });
            
            if (selectedIds.length === 0) {
                showNotificationVanilla('Please select at least one review to reject.', 'error');
                return;
            }
            
            if (confirm('Are you sure you want to reject and delete ' + selectedIds.length + ' review(s)? This action cannot be undone.')) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
                
                fetch('{{route("admin_review_bulk_reject")}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    body: JSON.stringify({
                        review_ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotificationVanilla(data.message, 'success');
                        // Reset checkboxes
                        document.querySelectorAll('.review-checkbox').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        var selectAllCheckbox = document.getElementById('select-all');
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                            selectAllCheckbox.indeterminate = false;
                        }
                        updateBulkButtonsVanilla();
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                })
                .catch(error => {
                    showNotificationVanilla('Something went wrong!', 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-times"></i> Reject Selected';
                });
            }
        });
    }
    
}

function updateBulkButtonsVanilla() {
    var checkedCount = document.querySelectorAll('.review-checkbox:checked').length;
    var bulkApproveBtn = document.getElementById('bulk-approve-btn');
    var bulkRejectBtn = document.getElementById('bulk-reject-btn');
    
    if (bulkApproveBtn && bulkRejectBtn) {
        bulkApproveBtn.disabled = checkedCount === 0;
        bulkRejectBtn.disabled = checkedCount === 0;
        
        if (checkedCount > 0) {
            bulkApproveBtn.innerHTML = '<i class="fas fa-check"></i> Approve (' + checkedCount + ')';
            bulkRejectBtn.innerHTML = '<i class="fas fa-times"></i> Reject (' + checkedCount + ')';
        } else {
            bulkApproveBtn.innerHTML = '<i class="fas fa-check"></i> Approve Selected';
            bulkRejectBtn.innerHTML = '<i class="fas fa-times"></i> Reject Selected';
        }
    }
}

function updateSelectAllStateVanilla() {
    var totalCheckboxes = document.querySelectorAll('.review-checkbox').length;
    var checkedCheckboxes = document.querySelectorAll('.review-checkbox:checked').length;
    var selectAllCheckbox = document.getElementById('select-all');
    
    if (selectAllCheckbox) {
        if (checkedCheckboxes === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes === totalCheckboxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
}

// Vanilla JavaScript notification function
function showNotificationVanilla(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var notification = document.createElement('div');
    notification.className = 'alert ' + alertClass + ' alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = '<strong>' + (type === 'success' ? 'Success!' : 'Error!') + '</strong> ' + message +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>';
    
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.remove();
    }, 5000);
}
</script>
@endsection



















