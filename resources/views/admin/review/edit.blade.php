@extends('admin.layouts.master')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Review</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin_review_index')}}">Reviews</a></li>
                        <li class="breadcrumb-item active">Edit Review</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Review Details</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin_review_update', $review->id)}}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Reviewer Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{old('name', $review->name)}}" required>
                                    @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" value="{{old('email', $review->email)}}" required>
                                    @error('email')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product</label>
                                    <input type="text" class="form-control" value="{{$review->product->title}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rating *</label>
                                    <select name="rating" class="form-select" required>
                                        <option value="1" {{old('rating', $review->rating) == 1 ? 'selected' : ''}}>1 Star - Poor</option>
                                        <option value="2" {{old('rating', $review->rating) == 2 ? 'selected' : ''}}>2 Stars - Fair</option>
                                        <option value="3" {{old('rating', $review->rating) == 3 ? 'selected' : ''}}>3 Stars - Good</option>
                                        <option value="4" {{old('rating', $review->rating) == 4 ? 'selected' : ''}}>4 Stars - Very Good</option>
                                        <option value="5" {{old('rating', $review->rating) == 5 ? 'selected' : ''}}>5 Stars - Excellent</option>
                                    </select>
                                    @error('rating')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Review Comment</label>
                            <textarea class="form-control" name="comment" rows="5" placeholder="Enter review comment...">{{old('comment', $review->comment)}}</textarea>
                            @error('comment')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_approved" value="1" 
                                       {{old('is_approved', $review->is_approved) ? 'checked' : ''}}>
                                <label class="form-check-label">Approve this review</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Review Information</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <strong>Submitted:</strong><br>
                                        {{$review->created_at->format('M d, Y h:i A')}}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <strong>Last Updated:</strong><br>
                                        {{$review->updated_at->format('M d, Y h:i A')}}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <strong>User ID:</strong><br>
                                        {{$review->user_id ?: 'Guest'}}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{route('admin_review_index')}}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Reviews
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



















