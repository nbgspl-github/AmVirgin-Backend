@extends('admin.layouts.header')
@section('content')
	@include('admin.layouts.breadcrumbs', ['data' => ['Users'=>route('users.all'),'Add'=>route('users.new')],'active'=>2])
	<div class="row px-2">
		<div class="card card-body">
			<h4 class="mt-0 header-title">Add a User</h4>
			<p class="text-muted m-b-30 font-14">Fill user details and hit Save</p>
			<form action="{{route('users.save')}}" method="POST" data-parsley-validate="true">
				@csrf
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" class="form-control" required placeholder="Type here the user's name" minlength="4" maxlength="50"/>
				</div>
				<div class="form-group">
					<label>Email</label>
					<input type="text" name="email" class="form-control" required placeholder="Type here the user's email" data-parsley-type="email"/>
				</div>
				<div class="form-group">
					<label>Mobile</label>
					<input type="text" name="mobile" class="form-control" required placeholder="Type here the user's mobile number" data-parsley-type="digits" minlength="10" maxlength="10"/>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="text" name="password" class="form-control" placeholder="Type a password or leave blank for default (password@123456)"/>
				</div>
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" name="status">
						<option value="0">Active</option>
						<option value="1">Disabled</option>
					</select>
				</div>
				<div class="form-group">
					<div>
						<button type="submit" class="btn btn-primary waves-effect waves-light">
							Save
						</button>
						<button type="button" onclick="window.location.href='{{route("users.all")}}'" class="btn btn-secondary waves-effect m-l-5">
							Cancel
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@stop