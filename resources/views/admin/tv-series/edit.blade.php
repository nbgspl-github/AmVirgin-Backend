@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<video src="{{Storage::disk('secured')->get($video)}}" width="600px" height="400px" type="video/mkv" poster="{{Storage::disk('public')->get($poster)}}" data-overlay="1" data-title="The curious case of Chameleon...">
			</video>
			{{--			<div class="card shadow-sm custom-card">--}}
			{{--				<div class="card-header py-0">--}}
			{{--					@include('admin.extras.header', ['title'=>'Edit a slider'])--}}
			{{--				</div>--}}
			{{--				<div class="card-body">--}}
			{{--					<div class="row">--}}
			{{--						<div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">--}}
			{{--							<form action="{{route('admin.sliders.update',$slide->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">--}}
			{{--								@csrf--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Title<span class="text-primary">*</span></label>--}}
			{{--									<input type="text" name="title" class="form-control" required placeholder="Type title here" minlength="1" maxlength="100" value="{{old('title',$slide->getTitle())}}"/>--}}
			{{--								</div>--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Description<span class="text-primary">*</span></label>--}}
			{{--									<textarea name="description" class="form-control" placeholder="Type description here">{{old('description',$slide->getDescription())}}</textarea>--}}
			{{--								</div>--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Target<span class="text-primary">*</span></label>--}}
			{{--									<input type="text" name="target" class="form-control" placeholder="Type target url here" value="{{old('target',$slide->getTarget())}}"/>--}}
			{{--								</div>--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Rating<span class="text-primary">*</span></label>--}}
			{{--									<select name="stars" class="form-control">--}}
			{{--										@if(old('stars',$slide->getStars())==0)--}}
			{{--											<option value="0" selected>Not rated</option>--}}
			{{--											<option value="1">1</option>--}}
			{{--											<option value="2">2</option>--}}
			{{--											<option value="3">3</option>--}}
			{{--											<option value="4">4</option>--}}
			{{--											<option value="5">5</option>--}}
			{{--										@endif--}}
			{{--										@if(old('stars',$slide->getStars())==1)--}}
			{{--											<option value="0">Not rated</option>--}}
			{{--											<option value="1" selected>1</option>--}}
			{{--											<option value="2">2</option>--}}
			{{--											<option value="3">3</option>--}}
			{{--											<option value="4">4</option>--}}
			{{--											<option value="5">5</option>--}}
			{{--										@endif--}}
			{{--										@if(old('stars',$slide->getStars())==2)--}}
			{{--											<option value="0">Not rated</option>--}}
			{{--											<option value="1">1</option>--}}
			{{--											<option value="2" selected>2</option>--}}
			{{--											<option value="3">3</option>--}}
			{{--											<option value="4">4</option>--}}
			{{--											<option value="5">5</option>--}}
			{{--										@endif--}}
			{{--										@if(old('stars',$slide->getStars())==3)--}}
			{{--											<option value="0">Not rated</option>--}}
			{{--											<option value="1">1</option>--}}
			{{--											<option value="2">2</option>--}}
			{{--											<option value="3" selected>3</option>--}}
			{{--											<option value="4">4</option>--}}
			{{--											<option value="5">5</option>--}}
			{{--										@endif--}}
			{{--										@if(old('stars',$slide->getStars())==4)--}}
			{{--											<option value="0">Not rated</option>--}}
			{{--											<option value="1">1</option>--}}
			{{--											<option value="2">2</option>--}}
			{{--											<option value="3">3</option>--}}
			{{--											<option value="4" selected>4</option>--}}
			{{--											<option value="5">5</option>--}}
			{{--										@endif--}}
			{{--										@if(old('stars',$slide->getStars())==5)--}}
			{{--											<option value="0">Not rated</option>--}}
			{{--											<option value="1">1</option>--}}
			{{--											<option value="2">2</option>--}}
			{{--											<option value="3">3</option>--}}
			{{--											<option value="4">4</option>--}}
			{{--											<option value="5" selected>5</option>--}}
			{{--										@endif--}}
			{{--									</select>--}}
			{{--								</div>--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Active<span class="text-primary">*</span></label>--}}
			{{--									<select name="active" class="form-control">--}}
			{{--										@if(old('active',$slide->isActive())==true)--}}
			{{--											<option value="1" selected>Yes</option>--}}
			{{--											<option value="0">No</option>--}}
			{{--										@else--}}
			{{--											<option value="1">Yes</option>--}}
			{{--											<option value="0" selected>No</option>--}}
			{{--										@endif--}}
			{{--									</select>--}}
			{{--								</div>--}}
			{{--								<div class="form-group">--}}
			{{--									<label>Poster<span class="text-primary">*</span></label>--}}
			{{--									<div class="card" style="border: 1px solid #ced4da;">--}}
			{{--										<div class="card-header">--}}
			{{--											<div class="row">--}}
			{{--												<div class="d-none">--}}
			{{--													<input id="pickImage" type="file" name="poster" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster')}}">--}}
			{{--												</div>--}}
			{{--												<div class="col-6">--}}
			{{--													<h3 class="my-0 header-title">Preview</h3>--}}
			{{--												</div>--}}
			{{--												<div class="col-6">--}}
			{{--													<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="openImagePicker();">Choose Image</button>--}}
			{{--												</div>--}}
			{{--											</div>--}}
			{{--										</div>--}}
			{{--										<div class="card-body p-0 rounded">--}}
			{{--											<div class="row">--}}
			{{--												<div class="col-12 text-center">--}}
			{{--													<img id="posterPreview" class="img-fluid" style="max-height: 400px!important;" src="{{route('images.slider.poster',$slide->getKey())}}"/>--}}
			{{--												</div>--}}
			{{--											</div>--}}
			{{--										</div>--}}
			{{--									</div>--}}
			{{--								</div>--}}
			{{--								<div class="form-row">--}}
			{{--									<div class="col-6">--}}
			{{--										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">--}}
			{{--											Update--}}
			{{--										</button>--}}
			{{--									</div>--}}
			{{--									<div class="col-6">--}}
			{{--										<a href="{{route("admin.sliders.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">--}}
			{{--											Cancel--}}
			{{--										</a>--}}
			{{--									</div>--}}
			{{--								</div>--}}
			{{--							</form>--}}
			{{--						</div>--}}
			{{--					</div>--}}
			{{--				</div>--}}
			{{--			</div>--}}
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