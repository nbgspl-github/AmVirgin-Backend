@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Add a plan'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
							<form action="{{route('admin.subscription-plans.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>@required (Name)</label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the plan's name" minlength="1" maxlength="100" value="{{old('name')}}"/>
								</div>
								<div class="form-group">
									<label>@required (Description)</label>
									<textarea name="description" class="form-control" placeholder="Type here the plan's description" minlength="1" maxlength="5000">{{old('description')}}</textarea>
								</div>
								<div class="form-group">
									<label>@required (Original Price)</label>
									<input type="number" name="originalPrice" class="form-control" placeholder="Type here plan's original price" value="{{old('originalPrice')}}" min="0" max="100000" step="1"/>
								</div>
								<div class="form-group">
									<label>Offer Price</label>
									<input type="number" name="offerPrice" class="form-control" placeholder="Type here plan's offer/discounted price" value="{{old('discountedPrice')}}" min="0" max="100000" step="1"/>
								</div>
								<div class="form-group">
									<label>@required (Duration)</label>
									<input type="number" name="duration" class="form-control" placeholder="Type here plan's duration (in days)" value="{{old('duration')}}" min="0" max="1200" step="1"/>
								</div>
								<div class="form-group">
									<label>@required (Banner)</label>
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
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.subscription-plans.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
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