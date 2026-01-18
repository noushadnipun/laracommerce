@extends('admin.layouts.master')

@section('title', 'Edit Role')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tag"></i> Edit Role: {{ $role->name }}
                    </h3>
                </div>
                
                <form action="{{ route('admin_roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $role->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                           value="{{ old('description', $role->description) }}">
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
                                                    <h6 class="mb-0">{{ ucfirst($module) }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($modulePermissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                               id="permission_{{ $permission->id }}" class="form-check-input"
                                                               {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
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
                            <i class="fas fa-save"></i> Update Role
                        </button>
                        <a href="{{ route('admin_roles.show', $role) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Role
                        </a>
                        <a href="{{ route('admin_roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
