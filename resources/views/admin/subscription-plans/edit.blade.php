@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit plan details'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 mx-auto">
							<form action="{{route('admin.subscription-plans.update',$plan->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label>@required (Name)</label>
									<input type="text" name="name" class="form-control" required placeholder="Type here the plan's name" minlength="1" maxlength="100" value="{{old('name',$plan->name)}}"/>
								</div>
								<div class="form-group">
									<label>@required (Description)</label>
									<textarea name="description" class="form-control" placeholder="Type here the plan's description" minlength="1" maxlength="5000">{{old('description',$plan->description)}}</textarea>
								</div>
								<div class="form-group">
									<label>@required (Original Price)</label>
									<input type="number" name="originalPrice" class="form-control" placeholder="Type here plan's original price" value="{{old('originalPrice',$plan->originalPrice)}}" min="0" max="100000" step="1"/>
								</div>
								<div class="form-group">
									<label>Offer Price</label>
									<input type="number" name="offerPrice" class="form-control" placeholder="Type here plan's offer/discounted price" value="{{old('offerPrice',$plan->offerPrice)}}" min="0" max="100000" step="1"/>
								</div>
								<div class="form-group">
									<label>@required (Duration)</label>
									<input type="number" name="duration" class="form-control" placeholder="Type here plan's duration (in days)" value="{{old('duration',$plan->duration)}}" min="0" max="1200" step="1"/>
								</div>
								<div class="form-group">
									<label>@required (Banner)</label>
									<input type="file" name="banner" id="banner" data-default-file="{{$plan->banner}}" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" data-show-remove="false">
								</div>
								<div class="form-group">
									<label>Status</label>
									@if (old('active',$plan->active)==1)
										<select class="form-control" name="active">
											<option value="1" selected>Yes</option>
											<option value="0">No</option>
										</select>
									@elseif(old('active',$plan->active)==0)
										<select class="form-control" name="active">
											<option value="1">Yes</option>
											<option value="0" selected>No</option>
										</select>
									@else
										<select class="form-control" name="active">
											<option value="1">Yes</option>
											<option value="0">No</option>
										</select>
									@endif
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
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
		$(document).ready(() => {
			$('#banner').dropify({});
		});
	</script>
@stop