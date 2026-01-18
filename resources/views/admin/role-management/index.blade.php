@extends('admin.layouts.master')

@section('title', 'Role Management')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-user-tag"></i> Role Management
                        </h3>
                        @can('create roles')
                        <a href="{{ route('admin_roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Role
                        </a>
                        @endcan
                    </div>
                </div>
                
                <!-- Search -->
                <div class="card-body">
                    <form method="GET" action="{{ route('admin_roles.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by role name or description..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('admin_roles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Roles Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Users Count</th>
                                    <th>Permissions</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                                    </td>
                                    <td>{{ $role->description ?? 'No description' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $role->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $role->permissions_count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @can('view roles')
                                            <a href="{{ route('admin_roles.show', $role) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('edit roles')
                                            <a href="{{ route('admin_roles.edit', $role) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('delete roles')
                                            @if(!in_array($role->name, ['super-admin', 'admin', 'customer']))
                                            <form action="{{ route('admin_roles.destroy', $role) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No roles found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $roles->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
