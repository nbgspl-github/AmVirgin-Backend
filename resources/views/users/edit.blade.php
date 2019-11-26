@extends('layouts.header')
@section('content')
	<div class="row py-4 px-2">
		<div class="card card-body">
			<h4 class="mt-0 header-title">Update user details</h4>
			<p class="text-muted m-b-30 font-14">Modify user details and hit Update</p>
			<form action="{{route('users.update',$user->getId())}}" data-parsley-validate="true">
				@method('PUT')
				@csrf
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" class="form-control" required placeholder="Type here the user's name" minlength="4" maxlength="50" value="{{$user->getName()}}"/>
				</div>
				<div class="form-group">
					<label>Email</label>
					<input type="text" name="email" class="form-control" required placeholder="Type here the user's email" data-parsley-type="email" value="{{$user->getEmail()}}"/>
				</div>
				<div class="form-group">
					<label>Mobile</label>
					<input type="text" name="mobile" class="form-control" required placeholder="Type here the user's mobile number" data-parsley-type="digits" minlength="10" maxlength="10" value="{{$user->getMobile()}}"/>
				</div>
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" name="status">
						<option value="0"
								@php
									if(isset($user)&&$user->getStatus()==1){
										echo "selected";
									}
								@endphp
						>Active
						</option>
						<option value="1"
								@php
									if(isset($user)&&$user->getStatus()==0){
										echo "selected";
									}
								@endphp
						>Disabled
						</option>
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