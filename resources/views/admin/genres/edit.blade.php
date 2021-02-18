@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header',['title'=>'Edit genre details'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.genres.update',$genre->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Name<span class="text-primary">*</span></label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the genre's name or title" minlength="1" maxlength="100" value="{{old('name',$genre->name)}}"/>
								</div>
								<div class="form-group">
									<label>Description</label>
									<input type="text" name="description" class="form-control" placeholder="Type here the genre's description" value="{{old('description',$genre->description)}}"/>
								</div>
								<div class="form-group">
									<label>Poster</label>
									<input type="file" name="poster" id="poster" data-default-file="{{$genre->poster}}" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" data-show-remove="false">
								</div>
								<div class="form-group">
									<label>Status</label>
									@if ($genre->active==1)
										<select class="form-control" name="active">
											<option value="1" selected>Active</option>
											<option value="0">Inactive</option>
										</select>
									@else
										<select class="form-control" name="active">
											<option value="1">Active</option>
											<option value="0" selected>Inactive</option>
										</select>
									@endif
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 mr-0 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light w-100">
												Update
											</button>
										</div>
										<div class="col-6">
											<a href="{{route("admin.genres.index")}}" class="btn btn-secondary waves-effect m-l-5 w-100">
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
@endsection

@section('javascript')
	<script>
		$(document).ready(() => {
			$('#poster').dropify({});
		});
	</script>
@stop