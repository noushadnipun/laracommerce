@extends('admin.layouts.master')

@section('title', 'Edit Permission')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key"></i> Edit Permission: {{ $permission->name }}
                    </h3>
                </div>
                
                <form action="{{ route('admin_permissions.update', $permission) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $permission->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Use lowercase with hyphens (e.g., view-users, edit-products)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guard_name">Guard Name</label>
                                    <select name="guard_name" id="guard_name" class="form-control @error('guard_name') is-invalid @enderror">
                                        <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                                    </select>
                                    @error('guard_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="3" placeholder="Describe what this permission allows users to do...">{{ old('description', $permission->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Permission
                        </button>
                        <a href="{{ route('admin_permissions.show', $permission) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Permission
                        </a>
                        <a href="{{ route('admin_permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Permissions
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
