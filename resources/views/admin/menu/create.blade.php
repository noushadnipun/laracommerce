@extends('admin.layouts.master')

@section('title', 'Create Menu')

@section('page-content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h3 class="card-title">Create New Menu</h3>
					<a href="{{ route('admin_menu.index') }}" class="btn btn-secondary btn-sm">Back</a>
				</div>
				<div class="card-body">
					<form action="{{ route('admin_menu.store') }}" method="POST">
						@csrf
						<div class="form-group">
							<label for="name">Menu Name</label>
							<input type="text" name="name" id="name" class="form-control" placeholder="e.g., primary" required>
						</div>
						<button type="submit" class="btn btn-primary">Create</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


