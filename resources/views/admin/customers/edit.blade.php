@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit customer details'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.customers.update',$customer->id)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Name</label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the user's name" minlength="4" maxlength="50" value="{{$customer->name}}"/>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="text" name="email" class="form-control" required placeholder="Type here the user's email" data-parsley-type="email" value="{{$customer->email}}"/>
								</div>
								<div class="form-group">
									<label>Mobile</label>
									<input type="text" name="mobile" class="form-control" required placeholder="Type here the user's mobile number" data-parsley-type="digits" minlength="10" maxlength="10" value="{{$customer->mobile}}"/>
								</div>
								<div class="form-group">
									<label>Active</label>
									<select class="form-control" name="active">
										@if($customer->active)
											<option value="1" selected>Yes</option>
											<option value="0">No</option>
										@else
											<option value="1">Yes</option>
											<option value="0" selected>No</option>
										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Avatar</label>
									<input name="avatar" type="file" id="input-file-now" class="dropify form-control file-gallery" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2M" data-default-file="{{$customer->avatar}}"/>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Update
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
@section('javascript')
	<script>
		$(document).ready(() => {
			const gallery = $('.file-gallery').dropify({});
		})
	</script>
@endsection