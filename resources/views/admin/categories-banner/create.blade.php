@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					
					@include('admin.extras.header', ['title'=>'Create a categories-banner'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.categories-banner.store')}}" method="POST" data-parsley-validate="true" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>Title</label>
						<input type="text" name="title" class="form-control" required placeholder="Type category banner Title" minlength="1" maxlength="500" value="{{old('title')}}"/>
								</div>
								<div class="form-group">
									<label>order</label>
									<select name="order" class="form-control" required>
                                        <option value="">Select order type</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
									</select>
								</div>
								<div class="form-group">
									<label>Section Title</label>
									<textarea type="text" name="sectionTitle" class="form-control" required placeholder="Describe your Section Title">{{old('sectionTitle')}}</textarea>
								</div>
								<div class="form-group">
									<label>Visibility</label>
									<select name="status" class="form-control">
										<option value="1" selected>Visible</option>
										<option value="0">Hidden</option>
									</select>
                                </div>
                                <div class="form-group">
									<label>Layout Type</label>
									<select name="layoutType" class="form-control" required>
                                        <option value="1" selected>Singal</option>
                                        <option value="2">Duble</option>
                                        
                                       
									</select>
                                </div>
                                <div class="form-group">
                                    <label>ValidFrom</label>
                                    <input type="date" name="validFrom" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>ValidUntil</label>
                                    <input type="date" name="validUntil" class="form-control" required>
                                </div>
								<div class="form-group">
									<label>Image</label>
									
									<div class="card" style="border: 1px solid #ced4da;">
										<div class="card-header">
											<div class="row">
												<div class="d-none">
												   <input id="pickImage" type="file" name="image[]"  onchange="previewImage(event);" multiple>
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
												<div class="col-12 text-center gallery">
													
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
	/*	var lastFile = null;
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
		}*/

		$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
			
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#pickImage').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});

openImagePicker = () => {
			$('#pickImage').trigger('click');
		}
	</script>
@stop
