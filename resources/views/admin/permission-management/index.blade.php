@extends('admin.layouts.master')

@section('title', 'Permission Management')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-key"></i> Permission Management
                        </h3>
                        <div>
                            @can('create permissions')
                            <a href="{{ route('admin_permissions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Permission
                            </a>
                            @endcan
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#bulkCreateModal">
                                <i class="fas fa-layer-group"></i> Bulk Create
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Search and Filter -->
                <div class="card-body">
                    <form method="GET" action="{{ route('admin_permissions.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search permissions..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="module" class="form-control">
                                        <option value="">All Modules</option>
                                        @foreach($modules as $module)
                                            <option value="{{ $module }}" 
                                                    {{ request('module') == $module ? 'selected' : '' }}>
                                                {{ ucfirst($module) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-3 text-right">
                                <a href="{{ route('admin_permissions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Permissions Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Permission Name</th>
                                    <th>Module</th>
                                    <th>Description</th>
                                    <th>Roles Count</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>
                                        <code>{{ $permission->name }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ ucfirst(explode(' ', $permission->name)[0]) }}
                                        </span>
                                    </td>
                                    <td>{{ $permission->description ?? 'No description' }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $permission->roles_count }}</span>
                                    </td>
                                    <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @can('view permissions')
                                            <a href="{{ route('admin_permissions.show', $permission) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('edit permissions')
                                            <a href="{{ route('admin_permissions.edit', $permission) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('delete permissions')
                                            <form action="{{ route('admin_permissions.destroy', $permission) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No permissions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $permissions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Create Modal -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin_permissions.bulk-create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Create Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="module">Module Name</label>
                        <input type="text" name="module" id="module" class="form-control" 
                               placeholder="e.g., products, users, orders" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Actions</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="view" class="form-check-input" checked>
                                    <label class="form-check-label">View</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="create" class="form-check-input" checked>
                                    <label class="form-check-label">Create</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="edit" class="form-check-input" checked>
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="delete" class="form-check-input" checked>
                                    <label class="form-check-label">Delete</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="export" class="form-check-input">
                                    <label class="form-check-label">Export</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="actions[]" value="import" class="form-check-input">
                                    <label class="form-check-label">Import</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
