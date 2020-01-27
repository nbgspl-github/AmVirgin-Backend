@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['breadcrumbs' =>['Dashboard'=>route('admin.home'),'Customers'=>route('admin.customers.index'),'Add'=>'#'],'title'=>trans('admin.customers.create')])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.customers.store')}}" method="POST" data-parsley-validate="true">
								@csrf
								<div class="form-group">
									<label>Name</label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the customer's name" minlength="4" maxlength="50" value="{{old('name')}}"/>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="text" name="email" class="form-control" required placeholder="Type here the customer's email" data-parsley-type="email" value="{{old('email')}}"/>
								</div>
								<div class="form-group">
									<label>Mobile</label>
									<input type="text" name="mobile" class="form-control" required placeholder="Type here the customer's mobile number" data-parsley-type="digits" minlength="10" maxlength="10" value="{{old('mobile')}}"/>
								</div>
								<div class="form-group">
									<label>Password</label>
									<input type="text" name="password" class="form-control" placeholder="Type a password for the customer" value="{{old('password','password@123456')}}"/>
								</div>
								<div class="form-group">
									<label>Active</label>
									<select class="form-control" name="active">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Save
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.customers.index")}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
												Cancel
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop