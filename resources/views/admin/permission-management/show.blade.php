@extends('admin.layouts.master')

@section('title', 'Permission Details')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Permission Header -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-key"></i> Permission Details
                        </h3>
                        <div class="btn-group">
                            @can('edit permissions')
                            <a href="{{ route('admin_permissions.edit', $permission) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Permission
                            </a>
                            @endcan
                            
                            <a href="{{ route('admin_permissions.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Permissions
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Permission Basic Info -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle"></i> Permission Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $permission->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td><code>{{ $permission->name }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Display Name:</strong></td>
                                    <td>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Guard:</strong></td>
                                    <td>{{ $permission->guard_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $permission->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $permission->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Permission Statistics -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-chart-bar"></i> Permission Statistics</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Users with this permission:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">{{ $permission->users->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Roles with this permission:</strong></td>
                                    <td>
                                        <span class="badge badge-success">{{ $permission->roles->count() }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Roles with this permission -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-user-tag"></i> Roles with this Permission
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($permission->roles->count() > 0)
                                <div class="row">
                                    @foreach($permission->roles as $role)
                                    <div class="col-md-4 mb-2">
                                        <div class="alert alert-primary">
                                            <strong>{{ ucfirst($role->name) }}</strong>
                                            @if($role->description)
                                                <br><small class="text-muted">{{ $role->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No roles assigned to this permission.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Users with this permission -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-users"></i> Users with this Permission
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($permission->users->count() > 0)
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
                                            @foreach($permission->users as $user)
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
                                    <i class="fas fa-info-circle"></i> No users assigned to this permission.
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
                                @can('edit permissions')
                                <a href="{{ route('admin_permissions.edit', $permission) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Permission
                                </a>
                                @endcan
                                
                                @can('delete permissions')
                                <form action="{{ route('admin_permissions.destroy', $permission) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this permission? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Permission
                                    </button>
                                </form>
                                @endcan
                                
                                <a href="{{ route('admin_permissions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Permissions
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

code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}
</style>
@endsection
