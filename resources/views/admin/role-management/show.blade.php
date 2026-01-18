@extends('admin.layouts.master')

@section('title', 'Role Details')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Role Header -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-user-tag"></i> Role Details
                        </h3>
                        <div class="btn-group">
                            @can('edit roles')
                            <a href="{{ route('admin_roles.edit', $role) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Role
                            </a>
                            @endcan
                            
                            <a href="{{ route('admin_roles.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Roles
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Role Basic Info -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle"></i> Role Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $role->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $role->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $role->description ?? 'No description provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Guard:</strong></td>
                                    <td>{{ $role->guard_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $role->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Role Statistics -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-chart-bar"></i> Role Statistics</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Users with this role:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">{{ $role->users->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Permissions:</strong></td>
                                    <td>
                                        <span class="badge badge-success">{{ $role->permissions->count() }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Permissions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-key"></i> Assigned Permissions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($role->permissions->count() > 0)
                                <div class="row">
                                    @foreach($role->permissions->groupBy(function($permission) {
                                        return explode(' ', $permission->name)[0];
                                    }) as $group => $permissions)
                                    <div class="col-md-4 mb-3">
                                        <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                                        <ul class="list-unstyled">
                                            @foreach($permissions as $permission)
                                            <li class="mb-1">
                                                <span class="badge badge-light">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No permissions assigned to this role.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Users with this role -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-users"></i> Users with this Role
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($role->users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($role->users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->is_active ?? true)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @can('view users')
                                                    <a href="{{ route('admin_users.show', $user) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No users assigned to this role.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="btn-group">
                                @can('edit roles')
                                <a href="{{ route('admin_roles.edit', $role) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Role
                                </a>
                                @endcan
                                
                                @can('delete roles')
                                @if(!in_array($role->name, ['super-admin', 'admin']))
                                <form action="{{ route('admin_roles.destroy', $role) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Role
                                    </button>
                                </form>
                                @endif
                                @endcan
                                
                                <a href="{{ route('admin_roles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Roles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    margin-bottom: 0.5rem;
}

.table-sm td {
    padding: 0.5rem;
}

.card-title {
    margin-bottom: 1rem;
}

.btn-group .btn {
    margin-right: 0.5rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
