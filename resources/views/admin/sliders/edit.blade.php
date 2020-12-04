@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit a slider'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
							<form action="{{route('admin.sliders.update',$slide->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Title<span class="text-primary">*</span></label>
									<input type="text" name="title" class="form-control" required placeholder="Type title here" minlength="1" maxlength="100" value="{{old('title',$slide->title)}}"/>
								</div>
								<div class="form-group">
									<label>Description<span class="text-primary">*</span></label>
									<textarea name="description" class="form-control" placeholder="Type description here">{{old('description',$slide->description)}}</textarea>
								</div>
								<div class="form-group">
									<label>@required(Type)</label>
									<select name="type" class="form-control" onchange="handleTypeChanged(this.value);">
										<option value="{{\App\Models\Slider::TargetType['ExternalLink']}}">External Link</option>
										<option value="{{\App\Models\Slider::TargetType['VideoKey']}}">Video</option>
									</select>
								</div>
								<div class="form-group">
									<label>Target link<span class="text-primary">*</span></label>
									<input type="text" name="targetLink" id="targetLink" class="form-control" placeholder="Type target url here" value="{{old('target')}}"/>
								</div>
								<div class="form-group">
									<label>Choose video<span class="text-primary">*</span></label>
									<select name="targetKey" id="targetKey" disabled class="form-control">
										@foreach($videos as $video)
											<option value="{{$video->id()}}">{{$video->title()}}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label>Rating<span class="text-primary">*</span></label>
									<select name="rating" class="form-control">
										@if(old('stars',$slide->stars)==0)
											<option value="0" selected>Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('stars',$slide->stars)==1)
											<option value="0">Not rated</option>
											<option value="1" selected>1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('stars',$slide->stars)==2)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2" selected>2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('stars',$slide->stars)==3)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3" selected>3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('stars',$slide->stars)==4)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4" selected>4</option>
											<option value="5">5</option>
										@endif
										@if(old('stars',$slide->stars)==5)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5" selected>5</option>
										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Active<span class="text-primary">*</span></label>
									<select name="active" class="form-control">
										@if(old('active',$slide->isActive())==true)
											<option value="1" selected>Yes</option>
											<option value="0">No</option>
										@else
											<option value="1">Yes</option>
											<option value="0" selected>No</option>
										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Banner<span class="text-primary">*</span></label>
									<div class="card" style="border: 1px solid #ced4da;">
										<div class="card-header">
											<div class="row">
												<div class="d-none">
													<input id="pickImage" type="file" name="banner" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('banner')}}">
												</div>
												<div class="col-6">
													<h3 class="my-0 header-title">Preview</h3>
												</div>
												<div class="col-6">
													<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="openImagePicker();">Choose Image</button>
												</div>
											</div>
										</div>
										<div class="card-body p-0 rounded">
											<div class="row">
												<div class="col-12 text-center">
													<img id="posterPreview" class="img-fluid" style="max-height: 400px!important;" src="{{Storage::disk('secured')->url($slide->banner)}}"/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.sliders.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
											Cancel
										</a>
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
		let targetTypes = {
			ExternalLink: '{{\App\Models\Slider::TargetType['ExternalLink']}}',
			VideoKey: '{{\App\Models\Slider::TargetType['VideoKey']}}'
		};
		let elements = {
			targetKey: null,
			targetLink: null
		};
		window.onload = () => {
			elements = {
				targetKey: $('#targetKey'),
				targetLink: $('#targetLink'),
			};
		};
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
		};

		handleTypeChanged = (value) => {
			if (value === targetTypes.ExternalLink) {
				disable(elements.targetKey);
				enable(elements.targetLink);
			} else {
				enable(elements.targetKey);
				disable(elements.targetLink);
			}
		};

		enable = (e) => {
			e.prop('disabled', false);
		};

		disable = (e) => {
			e.prop('disabled', true);
		};
	</script>
@stop