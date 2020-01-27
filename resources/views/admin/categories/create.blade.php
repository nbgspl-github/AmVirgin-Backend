@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create a category'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.categories.store')}}" method="POST" data-parsley-validate="true" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Name</label>
									<input type="text" name="name" class="form-control" required placeholder="Type category name" minlength="1" maxlength="100" value="{{old('name')}}"/>
								</div>
								<div class="form-group">
									<label>Parent category</label>
									<select name="parentId" class="form-control" required>
										@foreach($all as $item)
											<optgroup label="{{$item->name}}">
												<option value="{{$item->id}}">{{$item->name}}</option>
												@foreach($item->subItems as $subItem)
													<option value="{{ $subItem->id }}">{{$subItem->name}}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label>Description</label>
									<textarea type="text" name="description" class="form-control" required placeholder="Describe your category">{{old('description')}}</textarea>
								</div>
								<div class="form-group">
									<label>Visibility</label>
									<select name="visibility" class="form-control">
										<option value="1" selected>Visible</option>
										<option value="0">Hidden</option>
									</select>
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
													<img id="posterPreview" class="img-fluid" style="max-height: 400px!important;"/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Create
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
	</script>
@stop
