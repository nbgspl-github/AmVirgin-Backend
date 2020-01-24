@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit category details'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.categories.update',$category->getKey())}}" method="POST" data-parsley-validate="true" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Name</label>
									<input type="text" name="name" class="form-control" required placeholder="Type category name" minlength="1" maxlength="100" value="{{old('name',$category->getName())}}"/>
								</div>
								<div class="form-group">
									<label>Parent category</label>
									<select name="parentId" class="form-control" required>
										@foreach($all as $item)
											<optgroup label="{{$item->name}}">
												@if($category->getParentId()==$item->id)
													<option value="{{$item->id}}" selected>{{$item->name}}</option>
												@else
													<option value="{{$item->id}}">{{$item->name}}</option>
												@endif
												@foreach($item->subItems as $subItem)
													@if($category->getParentId()==$subItem->id)
														<option value="{{$subItem->id}}" selected>{{$subItem->name}}</option>
													@else
														<option value="{{$subItem->id}}">{{$subItem->name}}</option>
													@endif
												@endforeach
											</optgroup>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label>Description</label>
									<textarea type="text" name="description" class="form-control" required placeholder="Describe your category">{{old('description',$category->getDescription())}}</textarea>
								</div>
								<div class="form-group">
									<label>Visibility</label>
									<select name="visibility" class="form-control">
										@if(old('visible',$category->isVisible())==1)
											<option value="1" selected>Visible</option>
											<option value="0">Hidden</option>
										@else
											<option value="1">Visible</option>
											<option value="0" selected>Hidden</option>
										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Icon</label>
									<div class="card" style="border: 1px solid #ced4da;">
										<div class="card-header">
											<div class="row">
												<div class="d-none">
													<input id="pickImage1" type="file" name="icon" onclick="this.value=null;" onchange="previewImage1(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp, .svg" value="{{old('icon')}}">
												</div>
												<div class="col-6">
													<h3 class="my-0 header-title">Preview</h3>
												</div>
												<div class="col-6">
													<button type="button" class="btn btn-outline-primary rounded shadow-sm float-right" onclick="openImagePicker1();">Choose Image</button>
												</div>
											</div>
										</div>
										<div class="card-body p-0 rounded">
											<div class="row">
												<div class="col-12 text-center">
													<img id="posterPreview1" class="img-fluid" style="max-height: 400px!important;"/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Poster</label>
									<div class="card" style="border: 1px solid #ced4da;">
										<div class="card-header">
											<div class="row">
												<div class="d-none">
													<input id="pickImage" type="file" name="poster" onclick="this.value=null;" onchange="previewImage(event);" class="form-control" style="height: unset; padding-left: 6px" accept=".jpg, .png, .jpeg, .bmp" value="{{old('poster')}}">
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
													<img id="posterPreview" class="img-fluid" style="max-height: 400px!important;" src="{{old('poster',Storage::disk('public')->url($category->getPoster()))}}"/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Update
											</button>
										</div>
										<div class="col-6">
											<a type="button" href="{{route('admin.categories.index')}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
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

		var lastFile1 = null;
		previewImage1 = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('posterPreview1');
				output.src = reader.result;
			};
			lastFile1 = event.target.files[0];
			reader.readAsDataURL(lastFile1);
		};

		openImagePicker1 = () => {
			$('#pickImage1').trigger('click');
		}
	</script>
@stop
