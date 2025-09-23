@extends('admin.layouts.master')

@section('site-title')
Create Size Guide
@endsection

@section('page-title')
Create New Size Guide
@endsection

@section('page-content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-plus"></i> Create Size Guide
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('admin_size_guide_store')}}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="product_id">Product <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}" {{old('product_id') == $product->id ? 'selected' : ''}}>
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
                               value="{{old('title')}}" placeholder="e.g., Clothing Size Guide" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Brief description of the size guide">{{old('description')}}</textarea>
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
                            <option value="clothing" {{old('size_type') == 'clothing' ? 'selected' : ''}}>Clothing</option>
                            <option value="shoes" {{old('size_type') == 'shoes' ? 'selected' : ''}}>Shoes</option>
                            <option value="accessories" {{old('size_type') == 'accessories' ? 'selected' : ''}}>Accessories</option>
                            <option value="custom" {{old('size_type') == 'custom' ? 'selected' : ''}}>Custom</option>
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
                                  rows="4" placeholder="Instructions on how to measure">{{old('measurement_guide')}}</textarea>
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
                                   {{old('is_active', true) ? 'checked' : ''}}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Size Guide
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
                    <i class="fas fa-info-circle"></i> Size Guide Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Clothing:</strong> Include chest, waist, hip measurements
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Shoes:</strong> Include foot length and width measurements
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Accessories:</strong> Include relevant dimensions
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Custom:</strong> Create your own size chart
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection





