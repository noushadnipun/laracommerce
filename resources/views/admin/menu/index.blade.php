@extends('admin.layouts.master')

@section('title', 'Menu Management')

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin_menu.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Menu Name</th>
                                    <th>Items Count</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    @php
                                        $itemsCount = \DB::table('admin_menu_items')->where('menu', $menu->id)->count();
                                    @endphp
                                    <tr>
                                        <td>{{ $menu->id }}</td>
                                        <td>
                                            <strong>{{ ucfirst($menu->name) }}</strong>
                                            @if(in_array($menu->name, ['primary', 'secondary', 'footer-1', 'footer-2', 'mobile']))
                                                <span class="badge badge-info ml-2">System Menu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $itemsCount }} items</span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($menu->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin_menu.items', $menu->id) }}" 
                                                   class="btn btn-info btn-sm" title="Manage Items">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <a href="{{ route('admin_menu.edit', $menu->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!in_array($menu->name, ['primary', 'secondary', 'footer-1', 'footer-2', 'mobile']))
                                                    <form action="{{ route('admin_menu.destroy', $menu->id) }}" 
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                title="Delete" 
                                                                onclick="return confirm('Are you sure you want to delete this menu?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No menus found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

