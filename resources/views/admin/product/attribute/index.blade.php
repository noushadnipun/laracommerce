@extends('admin.layouts.master')

@section('site-title')
Product Attributes
@endsection

@section('page-title')
Product Attributes Management
@endsection

@section('page-content')

<!-- Examples Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-lightbulb text-warning"></i> Attribute Examples
                </h5>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Color Attribute:</strong>
                        <ul class="small">
                            <li>Type: Color</li>
                            <li>Display: Color Swatch</li>
                            <li>Values: Red, Blue, Green</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <strong>Size Attribute:</strong>
                        <ul class="small">
                            <li>Type: Select</li>
                            <li>Display: Dropdown</li>
                            <li>Values: S, M, L, XL</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <strong>Material Attribute:</strong>
                        <ul class="small">
                            <li>Type: Select</li>
                            <li>Display: Radio</li>
                            <li>Values: Cotton, Silk, Wool</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <strong>Image Attribute:</strong>
                        <ul class="small">
                            <li>Type: Image</li>
                            <li>Display: Image Grid</li>
                            <li>Values: Pattern images</li>
                        </ul>
                    </div>
                </div>
            </div>
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

<div class="row">
    <!-- Attribute Management -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header {{ (!empty($attribute)) ? 'bg-primary text-white' : 'bg-info text-white' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tags"></i> 
                        {{ (!empty($attribute)) ? 'Edit Attribute' : 'Add New Attribute' }}
                    </h3>
                    @if(!empty($attribute))
                        <a href="{{route('admin_product_attribute_index')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form id="attributeForm" role="form" method="POST" action="{{ (!empty($attribute)) ? route('admin_product_attribute_update') : route('admin_product_attribute_store') }}">
            @if(!empty($attribute))
                <input type="hidden" name="id" value="{{$attribute->id}}" />
            @endif
            @csrf
                    
                    <div class="form-group">
                        <label for="name">Attribute Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               placeholder="e.g., Color, Size, Material" 
                               value="{{ (!empty($attribute)) ? $attribute->name : old('name') }}" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Enter a descriptive name for this attribute (e.g., "Color", "Size", "Material")
                        </small>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Attribute Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    @foreach(\App\Models\ProductAttribute::getTypes() as $key => $label)
                                        <option value="{{$key}}" {{ (!empty($attribute) && $attribute->type == $key) || old('type') == $key ? 'selected' : '' }}>
                                            {{$label}}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Select:</strong> Dropdown list | 
                                    <strong>Color:</strong> Color swatches | 
                                    <strong>Text:</strong> Text input | 
                                    <strong>Image:</strong> Image selection
                                </small>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="display_type">Display Type <span class="text-danger">*</span></label>
                                <select name="display_type" id="display_type" class="form-control @error('display_type') is-invalid @enderror" required>
                                    <option value="">Select Display</option>
                                    @foreach(\App\Models\ProductAttribute::getDisplayTypes() as $key => $label)
                                        <option value="{{$key}}" {{ (!empty($attribute) && $attribute->display_type == $key) || old('display_type') == $key ? 'selected' : '' }}>
                                            {{$label}}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Dropdown:</strong> Select box | 
                                    <strong>Radio:</strong> Radio buttons | 
                                    <strong>Color Swatch:</strong> Color squares | 
                                    <strong>Image Grid:</strong> Image thumbnails
                                </small>
                                @error('display_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="2" placeholder="Brief description of this attribute">{{ (!empty($attribute)) ? $attribute->description : old('description') }}</textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Optional: Add a description to help users understand this attribute
                        </small>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       placeholder="0" min="0" 
                                       value="{{ (!empty($attribute)) ? $attribute->sort_order : (old('sort_order') ?? 0) }}">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Lower numbers appear first (0 = highest priority)
                                </small>
                                @error('sort_order')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_required" id="is_required" class="form-check-input" 
                                           {{ (!empty($attribute) && $attribute->is_required) || old('is_required') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        Required Attribute
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                           {{ (!empty($attribute) && $attribute->is_active) || old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-{{ (!empty($attribute)) ? 'primary' : 'success' }}">
                            <i class="fas fa-save"></i> 
                            {{ (!empty($attribute)) ? 'Update Attribute' : 'Add Attribute' }}
                        </button>
                        @if(!empty($attribute))
                            <a href="{{route('admin_product_attribute_index')}}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Attribute List -->
    <div class="col-md-6">
            <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-list"></i> All Attributes ({{$getAttribute->count()}})
                    </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Display</th>
                                <th>Values</th>
                                <th>Status</th>
                                <th width="80">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getAttribute as $data)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{$data->name}}</strong>
                                            @if($data->is_required)
                                                <span class="badge badge-warning badge-sm">Required</span>
                                            @endif
                                        </div>
                                        @if($data->description)
                                            <small class="text-muted">{{Str::limit($data->description, 50)}}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ucfirst($data->type)}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ucfirst($data->display_type)}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{$data->values->count()}}</span>
                                    </td>
                                    <td>
                                        @if($data->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{route('admin_product_attribute_edit', $data->id)}}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{route('admin_product_attribute_delete', $data->id)}}" 
                                               class="btn btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this attribute? This will also delete all its values.')" 
                                               title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-tags" style="font-size: 48px; color: #ddd;"></i>
                                        <h5 class="mt-2">No attributes found</h5>
                                        <p class="text-muted">Start by adding your first product attribute.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attribute Values Section -->
<div class="row mt-4">
    <!-- Attribute Value Management -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header {{ (!empty($attributevalue)) ? 'bg-primary text-white' : 'bg-warning text-white' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list-ul"></i> 
                        {{ (!empty($attributevalue)) ? 'Edit Attribute Value' : 'Add Attribute Value' }}
                    </h3>
                    @if(!empty($attributevalue))
                        <a href="{{route('admin_product_attribute_values_index')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    @endif
                </div>
            </div>
                <div class="card-body">
                <form id="attributeValueForm" role="form" method="POST" action="{{ (!empty($attributevalue)) ? route('admin_product_attribute_value_update') : route('admin_product_attribute_value_store') }}">
                    @if(!empty($attributevalue))
                        <input type="hidden" name="id" value="{{$attributevalue->id}}" />
                    @endif
                    @csrf
                    
                    <div class="form-group">
                        <label for="attribute_id">Attribute <span class="text-danger">*</span></label>
                        <select name="attribute_id" id="attribute_id" class="form-control @error('attribute_id') is-invalid @enderror" required>
                            <option value="">Select Attribute</option>
                            @foreach($getAttribute as $item)
                                <option value="{{$item->id}}" 
                                        data-type="{{$item->type}}"
                                        {{ (!empty($attributevalue) && $attributevalue->attribute_id == $item->id) || old('attribute_id') == $item->id ? 'selected' : '' }}>
                                    {{$item->name}} ({{ucfirst($item->type)}})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Select the attribute this value belongs to
                        </small>
                        @error('attribute_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="value">Value <span class="text-danger">*</span></label>
                        <input type="text" name="value" id="value" class="form-control @error('value') is-invalid @enderror" 
                               placeholder="e.g., Red, Small, Cotton" 
                               value="{{ (!empty($attributevalue)) ? $attributevalue->value : old('value') }}" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Enter the display value (e.g., "Red", "Small", "Cotton")
                        </small>
                        @error('value')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Color Code Field (shown for color attributes) -->
                    <div class="form-group" id="color_code_group" style="display: none;">
                        <label for="color_code">Color Code (Hex) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="color" name="color_code" id="color_code" class="form-control @error('color_code') is-invalid @enderror" 
                                   value="{{ (!empty($attributevalue)) ? $attributevalue->color_code : old('color_code', '#FF0000') }}"
                                   style="width: 60px; height: 38px; padding: 0; border: none;">
                            <input type="text" id="color_code_text" class="form-control" 
                                   placeholder="#FF0000" maxlength="7" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" id="color_preview" style="width: 40px; background-color: #ddd;"></span>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Click the color box to pick a color, or enter hex code manually
                        </small>
                        @error('color_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                       placeholder="0" min="0" 
                                       value="{{ (!empty($attributevalue)) ? $attributevalue->sort_order : (old('sort_order') ?? 0) }}">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Lower numbers appear first
                                </small>
                                @error('sort_order')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                    <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_active" id="is_active_value" class="form-check-input" 
                                           {{ (!empty($attributevalue) && $attributevalue->is_active) || old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_value">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-{{ (!empty($attributevalue)) ? 'primary' : 'warning' }}">
                            <i class="fas fa-save"></i> 
                            {{ (!empty($attributevalue)) ? 'Update Value' : 'Add Value' }}
                        </button>
                        @if(!empty($attributevalue))
                            <a href="{{route('admin_product_attribute_values_index')}}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Attribute Values List -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-list-ul"></i> All Attribute Values ({{$getAttributeValue->count()}})
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Value</th>
                                <th>Attribute</th>
                                <th>Type</th>
                                <th>Color</th>
                                <th>Status</th>
                                <th width="80">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getAttributeValue as $data)
                                <tr>
                                    <td>
                                        <strong>{{$data->value}}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{$data->attribute->name ?? 'N/A'}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ucfirst($data->attribute->type ?? 'N/A')}}</span>
                                    </td>
                                    <td>
                                        @if($data->color_code)
                                            <span class="badge" style="background-color: {{$data->color_code}}; color: white;">
                                                {{$data->color_code}}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{route('admin_product_attribute_value_edit', $data->id)}}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{route('admin_product_attribute_value_delete', $data->id)}}" 
                                               class="btn btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this attribute value?')" 
                                               title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-list-ul" style="font-size: 48px; color: #ddd;"></i>
                                        <h5 class="mt-2">No attribute values found</h5>
                                        <p class="text-muted">Start by adding values to your attributes.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
                    </div>
                </div>
            </div>
@endsection
@section('cusjs')
<!-- Custom CSS -->
<style>
.badge-sm {
    font-size: 0.7em;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9em;
}

.card-header h3 {
    font-size: 1.1em;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

#color_preview {
    border: 1px solid #ddd;
    cursor: pointer;
}
</style>

<!-- Custom JavaScript -->

<script>
$(document).ready(function() {
    
    // Smart display type logic
    function updateDisplayTypeOptions() {
        var attributeType = $('#type').val();
        var displayTypeSelect = $('#display_type');
        var currentValue = displayTypeSelect.val();
        
        // Clear existing options
        displayTypeSelect.empty();
        displayTypeSelect.append('<option value="">Select Display</option>');
        
        // Add appropriate display types based on attribute type
        if (attributeType === 'color') {
            displayTypeSelect.append('<option value="color_swatch">Color Swatches</option>');
            displayTypeSelect.append('<option value="dropdown">Dropdown</option>');
        } else if (attributeType === 'image') {
            displayTypeSelect.append('<option value="image_grid">Image Grid</option>');
            displayTypeSelect.append('<option value="dropdown">Dropdown</option>');
        } else if (attributeType === 'text') {
            displayTypeSelect.append('<option value="text_input">Text Input</option>');
        } else {
            // For select, radio, checkbox
            displayTypeSelect.append('<option value="dropdown">Dropdown</option>');
            displayTypeSelect.append('<option value="radio">Radio Buttons</option>');
            displayTypeSelect.append('<option value="checkbox">Checkboxes</option>');
        }
        
        // Restore previous value if it's still valid
        if (currentValue && displayTypeSelect.find('option[value="' + currentValue + '"]').length > 0) {
            displayTypeSelect.val(currentValue);
        }
    }
    
    // Update display type when attribute type changes
    $('#type').on('change', function() {
        updateDisplayTypeOptions();
    });
    
    // Show/hide color code field based on attribute type
    $('#attribute_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var attributeType = selectedOption.data('type');
        
        if (attributeType === 'color') {
            $('#color_code_group').show();
            $('#color_code').prop('required', true);
        } else {
            $('#color_code_group').hide();
            $('#color_code').prop('required', false);
        }
    });
    
    // Color picker functionality
    $('#color_code').on('change', function() {
        var colorCode = $(this).val();
        $('#color_code_text').val(colorCode);
        $('#color_preview').css('background-color', colorCode);
    });
    
    // Text input for color code
    $('#color_code_text').on('input', function() {
        var colorCode = $(this).val();
        if (colorCode.match(/^#[0-9A-F]{6}$/i)) {
            $('#color_code').val(colorCode);
            $('#color_preview').css('background-color', colorCode);
        } else {
            $('#color_preview').css('background-color', '#ddd');
        }
    });
    
    // Initialize color preview on page load
    var initialColor = $('#color_code').val();
    if (initialColor) {
        $('#color_code_text').val(initialColor);
        $('#color_preview').css('background-color', initialColor);
    }
    
    // Form validation
    $('#attributeForm, #attributeValueForm').on('submit', function(e) {
        var form = $(this);
        var requiredFields = form.find('[required]');
        var isValid = true;
        
        requiredFields.each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endsection