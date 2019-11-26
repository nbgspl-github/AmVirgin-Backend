@extends('layouts.header')
@section('content')
	@include('layouts.breadcrumbs', ['data' => ['Genres'=>route('genres.index'),'Add'=>'#']])
	<div class="row px-2">
		<div class="card card-body">
			<h4 class="mt-0 header-title">Add a Genre</h4>
			<p class="text-muted m-b-30 font-14">Add genre details and hit Save</p>
			<form action="{{route('genres.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group">
					<label>Name<span class="text-primary">*</span></label>
					<input type="text" name="name" class="form-control" required placeholder="Type here the genre's name or title" minlength="1" maxlength="100" value="{{old('name')}}"/>
				</div>
				<div class="form-group">
					<label>Description</label>
					<input type="text" name="description" class="form-control" placeholder="Type here the genre's description" value="{{old('description')}}"/>
				</div>
				<div class="form-group">
					<label>Poster</label>
					<div class="card m-b-30" style="border: 1px solid #ced4da; max-width: 400px">
						<div class="card-header">
							<div class="row">
								<div class="d-none">
									<input id="pickImage" type="file" name="poster" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster')}}">
								</div>
								<div class="col-md-6"><h3 class="my-0 header-title">Preview</h3></div>
								<div class="col-md-6">
									<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="openImagePicker();">Choose Image</button>
								</div>
							</div>
						</div>
						<div class="card-body p-0 rounded">
							<div class="row">
								<div class="col-md-12 text-center">
									<img id="posterPreview" width="398px" height="399px"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Status</label>
					@if (old('status',-1)==1)
						<select class="form-control" name="status">
							<option value="1" selected>Active</option>
							<option value="0">Inactive</option>
						</select>
					@elseif(old('status',-1)==0)
						<select class="form-control" name="status">
							<option value="1">Active</option>
							<option value="0" selected>Inactive</option>
						</select>
					@else
						<select class="form-control" name="status">
							<option value="1">Active</option>
							<option value="0">Inactive</option>
						</select>
					@endif
				</div>
				<div class="form-group">
					<div>
						<button type="submit" class="btn btn-primary waves-effect waves-light">
							Save
						</button>
						<a href="{{route("genres.index")}}" class="btn btn-secondary waves-effect m-l-5">
							Cancel
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		var lastFile = null;
		previewImage = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('posterPreview');
				output.src = reader.result;
			};
			lastFile = event.target.files[0];
			reader.readAsDataURL(lastFile);
		};

		openImagePicker = () => {
			$('#pickImage').trigger('click');
		}
	</script>
@stop