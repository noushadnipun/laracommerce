@extends('admin.layouts.master')

@section('title', 'Menu Items - ' . $menu->name)

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu Items - {{ ucfirst($menu->name) }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin_menu.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Menus
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
                            <i class="fas fa-plus"></i> Add Menu Item
                        </button>
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
                        <table class="table table-bordered table-striped" id="menuItemsTable">
                            <thead>
                                <tr>
                                    <th width="50">Sort</th>
                                    <th>Label</th>
                                    <th>Link</th>
                                    <th>Parent</th>
                                    <th>CSS Class</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menuItems as $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <span class="sort-handle" style="cursor: move;">
                                                <i class="fas fa-grip-vertical"></i>
                                            </span>
                                            <input type="hidden" class="sort-order" value="{{ $item->sort }}">
                                        </td>
                                        <td>
                                            <strong>{{ $item->label }}</strong>
                                            @if($item->depth > 0)
                                                <span class="badge badge-info ml-2">Child</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ $item->link }}" target="_blank" class="text-primary">
                                                {{ $item->link }}
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->parent > 0)
                                                @php
                                                    $parent = \DB::table('admin_menu_items')->find($item->parent);
                                                @endphp
                                                {{ $parent ? $parent->label : 'Unknown' }}
                                            @else
                                                <span class="text-muted">Root</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->class)
                                                <code>{{ $item->class }}</code>
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm edit-item" 
                                                        data-id="{{ $item->id }}" 
                                                        data-label="{{ $item->label }}"
                                                        data-link="{{ $item->link }}"
                                                        data-parent="{{ $item->parent }}"
                                                        data-sort="{{ $item->sort }}"
                                                        data-class="{{ $item->class }}"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin_menu.items.delete', [$menu->id, $item->id]) }}" 
                                                      method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Delete" 
                                                            onclick="return confirm('Are you sure you want to delete this menu item?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No menu items found.</td>
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

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin_menu.items.store', $menu->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="label">Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="label" name="label" required>
                    </div>
                    <div class="form-group">
                        <label for="link">Link <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="link" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="parent">Parent Item</label>
                        <select class="form-control" id="parent" name="parent">
                            <option value="">Select Parent (Optional)</option>
                            @foreach($menuItems->where('parent', 0) as $parentItem)
                                <option value="{{ $parentItem->id }}">{{ $parentItem->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sort">Sort Order</label>
                        <input type="number" class="form-control" id="sort" name="sort" value="{{ $menuItems->max('sort') + 1 }}" min="0">
                    </div>
                    <div class="form-group">
                        <label for="class">CSS Class</label>
                        <input type="text" class="form-control" id="class" name="class" placeholder="e.g., fa fa-home">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_label">Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_label" name="label" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_link">Link <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_link" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_parent">Parent Item</label>
                        <select class="form-control" id="edit_parent" name="parent">
                            <option value="">Select Parent (Optional)</option>
                            @foreach($menuItems->where('parent', 0) as $parentItem)
                                <option value="{{ $parentItem->id }}">{{ $parentItem->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_sort">Sort Order</label>
                        <input type="number" class="form-control" id="edit_sort" name="sort" min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_class">CSS Class</label>
                        <input type="text" class="form-control" id="edit_class" name="class" placeholder="e.g., fa fa-home">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('cusjs')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    // Sortable functionality
    const tbody = document.querySelector('#menuItemsTable tbody');
    if (tbody) {
        new Sortable(tbody, {
            handle: '.sort-handle',
            animation: 150,
            onEnd: function(evt) {
                updateSortOrder();
            }
        });
    }

    // Edit item functionality
    $('.edit-item').click(function() {
        const id = $(this).data('id');
        const label = $(this).data('label');
        const link = $(this).data('link');
        const parent = $(this).data('parent');
        const sort = $(this).data('sort');
        const cssClass = $(this).data('class');

        $('#edit_label').val(label);
        $('#edit_link').val(link);
        $('#edit_parent').val(parent);
        $('#edit_sort').val(sort);
        $('#edit_class').val(cssClass);
        
        const baseUpdateUrl = '{{ url('admin/menu/'.$menu->id.'/items') }}';
        $('#editItemForm').attr('action', baseUpdateUrl + '/' + id);
        $('#editItemModal').modal('show');
    });

    function updateSortOrder() {
        const items = [];
        $('#menuItemsTable tbody tr').each(function(index) {
            const id = $(this).data('id');
            if (id) {
                items.push({
                    id: id,
                    sort: index + 1
                });
            }
        });

        $.ajax({
            url: '{{ route("admin_menu.update-order", $menu->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: items
            },
            success: function(response) {
                if (response.success) {
                    // Update sort order values
                    items.forEach(function(item, index) {
                        $(`tr[data-id="${item.id}"] .sort-order`).val(item.sort);
                    });
                }
            }
        });
    }
});
</script>
@endsection
