@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit slide'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
							<form action="{{route('admin.shop.sliders.update',$slide->id)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
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
									<label>Target<span class="text-primary">*</span></label>
									<input type="text" name="target" class="form-control" placeholder="Type target url here" value="{{old('target',$slide->target)}}"/>
								</div>
								<div class="form-group">
									<label>Rating<span class="text-primary">*</span></label>
									<select name="rating" class="form-control">
										@if(old('rating',$slide->rating)==0)
											<option value="0" selected>Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('rating',$slide->rating)==1)
											<option value="0">Not rated</option>
											<option value="1" selected>1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('rating',$slide->rating)==2)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2" selected>2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('rating',$slide->rating)==3)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3" selected>3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										@endif
										@if(old('rating',$slide->rating)==4)
											<option value="0">Not rated</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4" selected>4</option>
											<option value="5">5</option>
										@endif
										@if(old('rating',$slide->rating)==5)
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
										@if(old('active',$slide->active)==true)
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
									<input type="file" name="banner" id="banner" data-default-file="{{$slide->banner}}" data-allowed-formats=".jpg, .png, .jpeg" data-max-file-size="2M" data-show-remove="false">
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.shop.sliders.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
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
		$(document).ready(() => {
			$('#banner').dropify({});
		});
	</script>
@stop