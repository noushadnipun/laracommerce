@extends('admin.layouts.master')

@section('title', 'Create Role')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tag"></i> Create New Role
                    </h3>
                </div>
                
                <form action="{{ route('admin_roles.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                           value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permissions</label>
                                    <div class="row">
                                        @foreach($permissions as $module => $modulePermissions)
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="card-title mb-0">
                                                        <input type="checkbox" class="module-checkbox" data-module="{{ $module }}">
                                                        {{ ucfirst($module) }} Module
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($modulePermissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                               id="permission_{{ $permission->id }}" class="form-check-input permission-checkbox"
                                                               data-module="{{ $module }}"
                                                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label for="permission_{{ $permission->id }}" class="form-check-label">
                                                            {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('permissions')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Role
                        </button>
                        <a href="{{ route('admin_roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Module checkbox functionality
    const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    moduleCheckboxes.forEach(moduleCheckbox => {
        const module = moduleCheckbox.dataset.module;
        
        moduleCheckbox.addEventListener('change', function() {
            const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            modulePermissions.forEach(permission => {
                permission.checked = this.checked;
            });
        });
        
        // Check if all permissions in module are selected
        const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
        const checkedPermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
        
        if (modulePermissions.length === checkedPermissions.length && modulePermissions.length > 0) {
            moduleCheckbox.checked = true;
        }
    });
    
    // Individual permission checkbox functionality
    permissionCheckboxes.forEach(permissionCheckbox => {
        permissionCheckbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
            const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const checkedPermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]:checked`);
            
            if (modulePermissions.length === checkedPermissions.length) {
                moduleCheckbox.checked = true;
            } else {
                moduleCheckbox.checked = false;
            }
        });
    });
});
</script>
@endsection
