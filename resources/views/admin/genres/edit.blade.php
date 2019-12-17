@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
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
									<div class="card m-b-30" style="border: 1px solid #ced4da">
										<div class="card-header">
											<div class="row">
												<div class="d-none">
													<input id="pickImage" type="file" name="poster" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster',$genre->getPoster())}}">
												</div>
												<div class="col-md-6"><h3 class="my-0 header-title">Preview</h3></div>
												<div class="col-md-6">
													<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="openImagePicker();">Choose Image</button>
												</div>
											</div>
										</div>
										<div class="card-body p-0 rounded">
											<div class="row">
												<div class="col-12 text-center">
													@if(old('poster',$genre->getPoster())!=null)
														<img class="rounded img-fluid" id="posterPreview" src="{{route('images.genre.poster',$genre->getKey())}}" height="399px" alt="">
													@else
														<img class="rounded img-fluid" id="posterPreview" src="" height="399px" alt="No poster available">
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Status</label>
									@if ($genre->getStatus()==1)
										<select class="form-control" name="status">
											<option value="1" selected>Active</option>
											<option value="0">Inactive</option>
										</select>
									@else
										<select class="form-control" name="status">
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
		let lastFile = null;
		previewImage = (event) => {
			document.getElementById('pickImage').files[0] = null;
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('posterPreview');
				output.src = reader.result;
			};
			lastFile = event.target.files[0];
			reader.readAsDataURL(lastFile);
		};

		switchActive = () => {

		};

		openImagePicker = () => {
			$('#pickImage').trigger('click');
		}
	</script>
@stop