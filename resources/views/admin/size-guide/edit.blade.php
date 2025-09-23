@extends('admin.layouts.master')

@section('site-title')
Edit Size Guide
@endsection

@section('page-title')
Edit Size Guide
@endsection

@section('page-content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit"></i> Edit Size Guide
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('admin_size_guide_update', $sizeGuide->id)}}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="product_id">Product <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}" {{($sizeGuide->product_id == $product->id || old('product_id') == $product->id) ? 'selected' : ''}}>
                                    {{$product->title}} ({{$product->sku}})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{old('title', $sizeGuide->title)}}" placeholder="e.g., Clothing Size Guide" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Brief description of the size guide">{{old('description', $sizeGuide->description)}}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="size_type">Size Type <span class="text-danger">*</span></label>
                        <select name="size_type" id="size_type" class="form-control @error('size_type') is-invalid @enderror" required>
                            <option value="">Select Size Type</option>
                            <option value="clothing" {{($sizeGuide->size_type == 'clothing' || old('size_type') == 'clothing') ? 'selected' : ''}}>Clothing</option>
                            <option value="shoes" {{($sizeGuide->size_type == 'shoes' || old('size_type') == 'shoes') ? 'selected' : ''}}>Shoes</option>
                            <option value="accessories" {{($sizeGuide->size_type == 'accessories' || old('size_type') == 'accessories') ? 'selected' : ''}}>Accessories</option>
                            <option value="custom" {{($sizeGuide->size_type == 'custom' || old('size_type') == 'custom') ? 'selected' : ''}}>Custom</option>
                        </select>
                        @error('size_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="measurement_guide">Measurement Guide</label>
                        <textarea name="measurement_guide" id="measurement_guide" class="form-control @error('measurement_guide') is-invalid @enderror" 
                                  rows="4" placeholder="Instructions on how to measure">{{old('measurement_guide', $sizeGuide->measurement_guide)}}</textarea>
                        @error('measurement_guide')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                   {{($sizeGuide->is_active || old('is_active')) ? 'checked' : ''}}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Size Guide
                        </button>
                        <a href="{{route('admin_size_guide_index')}}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Size Guide Info
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Created:</strong> {{$sizeGuide->created_at->format('M d, Y H:i')}}</p>
                <p><strong>Last Updated:</strong> {{$sizeGuide->updated_at->format('M d, Y H:i')}}</p>
                <p><strong>Status:</strong> 
                    @if($sizeGuide->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection





