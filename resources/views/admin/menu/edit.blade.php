@extends('admin.layouts.master')

@section('title', 'Edit Menu')

@section('page-content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h3 class="card-title">Edit Menu</h3>
					<a href="{{ route('admin_menu.index') }}" class="btn btn-secondary btn-sm">Back</a>
				</div>
				<div class="card-body">
					<form action="{{ route('admin_menu.update', $menu->id) }}" method="POST">
						@csrf
						@method('PUT')
						<div class="form-group">
							<label for="name">Menu Name</label>
							<input type="text" name="name" id="name" class="form-control" value="{{ $menu->name }}" required>
						</div>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


