@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit seller details'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.sellers.update',$seller->getKey())}}" data-parsley-validate="true" method="POST">
								@csrf
								<div class="form-group">
									<label>Name</label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the user's name" minlength="4" maxlength="50" value="{{$seller->getName()}}"/>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="text" name="email" class="form-control" required placeholder="Type here the user's email" data-parsley-type="email" value="{{$seller->getEmail()}}"/>
								</div>
								<div class="form-group">
									<label>Mobile</label>
									<input type="text" name="mobile" class="form-control" required placeholder="Type here the user's mobile number" data-parsley-type="digits" minlength="10" maxlength="10" value="{{$seller->getMobile()}}"/>
								</div>
								<div class="form-group">
									<label>Active</label>
									<select class="form-control" name="active">
										@if($seller->isActive())
											<option value="1" selected>Yes</option>
											<option value="0">No</option>
										@else
											<option value="1">Yes</option>
											<option value="0" selected>No</option>
										@endif
									</select>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Update
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.sellers.index")}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
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