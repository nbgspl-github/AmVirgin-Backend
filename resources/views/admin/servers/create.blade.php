@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create a media server'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.servers.store')}}" method="POST" data-parsley-validate="true">
								@csrf
								<div class="form-group">
									<label>Name <span class="text-primary">*</span> </label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the name you want to give to this server" minlength="4" maxlength="50" value="{{old('name')}}"/>
								</div>
								<div class="form-group">
									<label>IP Address</label>
									<input type="text" name="email" class="form-control" required placeholder="Type here the IP address of this server" data-parsley-pattern="\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b" value="{{old('ipAddress')}}"/>
								</div>
								<div class="form-group">
									<label for="useAuth">Uses Authentication</label>
									<select name="useAuth" id="useAuth" class="form-control">
										<option value="0">No</option>
										<option value="1">Yes</option>
									</select>
								</div>
								<div class="form-group">
									<label>Base Path</label>
									<input type="url" name="basePath" class="form-control" placeholder="Type a base path of this media server" value="{{old('basePath')}}"/>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Save
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.servers.index")}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
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