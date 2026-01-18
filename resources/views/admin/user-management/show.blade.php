@extends('admin.layouts.master')

@section('title', 'User Details')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- User Header -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i> User Details
                        </h3>
                        <div class="btn-group">
                            @can('edit users')
                            <a href="{{ route('admin_users.edit', $user) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            @endcan
                            
                            @can('edit users')
                            <form action="{{ route('admin_users.toggle-status', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-{{ ($user->is_active ?? true) ? 'secondary' : 'success' }} btn-sm"
                                        title="{{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ ($user->is_active ?? true) ? 'ban' : 'check' }}"></i>
                                    {{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @endcan
                            
                            <a href="{{ route('admin_users.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Users
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- User Avatar and Basic Info -->
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle mb-3" 
                                         width="120" height="120">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                         style="width: 120px; height: 120px;">
                                        <span class="text-white fw-bold" style="font-size: 48px;">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <h4>{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->email }}</p>
                                
                                <div class="mt-3">
                                    @if($user->is_active ?? true)
                                        <span class="badge badge-success badge-lg">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-lg">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Details -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-info-circle"></i> Personal Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $user->phone ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5><i class="fas fa-shield-alt"></i> Account Status</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($user->is_active ?? true)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email Verified:</strong></td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge badge-success">Verified</span>
                                                    <small class="text-muted d-block">{{ $user->email_verified_at->format('M d, Y') }}</small>
                                                @else
                                                    <span class="badge badge-warning">Not Verified</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Login:</strong></td>
                                            <td>
                                                @if($user->last_login_at ?? false)
                                                    {{ $user->last_login_at->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Roles and Permissions -->
            <div class="row mt-4">
                <!-- Roles -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-user-tag"></i> Assigned Roles
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->roles->count() > 0)
                                <div class="row">
                                    @foreach($user->roles as $role)
                                    <div class="col-md-6 mb-2">
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
                                    <i class="fas fa-exclamation-triangle"></i> No roles assigned to this user.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Permissions -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-key"></i> Direct Permissions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->permissions->count() > 0)
                                <div class="row">
                                    @foreach($user->permissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <div class="alert alert-info">
                                            <strong>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</strong>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No direct permissions assigned. User permissions come from roles only.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Permissions (from roles) -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-list"></i> All Permissions (Including from Roles)
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $allPermissions = $user->getAllPermissions();
                            @endphp
                            
                            @if($allPermissions->count() > 0)
                                <div class="row">
                                    @foreach($allPermissions->groupBy(function($permission) {
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
                                    <i class="fas fa-exclamation-triangle"></i> This user has no permissions assigned.
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
                                @can('edit users')
                                <a href="{{ route('admin_users.edit', $user) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit User
                                </a>
                                @endcan
                                
                                @can('edit users')
                                <form action="{{ route('admin_users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-{{ ($user->is_active ?? true) ? 'secondary' : 'success' }}"
                                            onclick="return confirm('Are you sure you want to {{ ($user->is_active ?? true) ? 'deactivate' : 'activate' }} this user?')">
                                        <i class="fas fa-{{ ($user->is_active ?? true) ? 'ban' : 'check' }}"></i>
                                        {{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }} User
                                    </button>
                                </form>
                                @endcan
                                
                                @can('delete users')
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin_users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                </form>
                                @endif
                                @endcan
                                
                                <a href="{{ route('admin_users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Users
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
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

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
