@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute type'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.types.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										Attribute type defines what type of value(s) can be given to an attribute.
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">@required (Name)</label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name for this attribute types such as Length, Weight etc" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label for="description">@required (Description)</label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type attribute-type description here">{{old('description')}}</textarea>
										</div>
										<div class="form-group">
											<label for="primitiveType">@required (Primitive Type)</label>
											<select name="primitiveType" id="primitiveType" class="form-control" required>
												<option value="" selected disabled>Choose</option>
												@foreach($types as $type)
													<option value="{{$type->typeCode()}}" @include('admin.extras.tooltip.right', ['title' => sprintf('$Name is %s',$type->name())])>{{$type->name()}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label>Limit input range?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="bounded" name="bounded" onchange="handleBoundStatusChanged();">
													<label class="custom-control-label" for="bounded">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group" id="minMaxContainer">
											<label>Enter upper and lower limit</label>
											<div class="row">
												<div class="col-6"><input type="text" class="form-control" placeholder="Lower limit"></div>
												<div class="col-6"><input type="text" class="form-control" placeholder="Upper limit"></div>
											</div>
										</div>
										<div class="form-group">
											<label>Enable multiple value input?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="multiValue" name="multiValue" onchange="handleMultiValueChanged();">
													<label class="custom-control-label" for="multiValue">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group" id="maxValuesContainer">
											<label for="">Maximum number of input values</label>
											<input id="maxValues" type="number" name="maxValues" class="form-control" required placeholder="Type max number of values here" value="{{old('maxValues')}}" min="1" max="10000"/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route('admin.products.attributes.index')}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
											Cancel
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script src="{{asset('assets/admin/utils/MultiEntryModal.js')}}"></script>
	<script>
		window.onload = () => {
			$('#minMaxContainer').hide();
			$('#maxValuesContainer').hide();
		};

		handleBoundStatusChanged = () => {
			const checked = event.target.checked;
			if (checked) {
				$('#minMaxContainer').show();
			} else {
				const element = $('#minMaxContainer');
				element.hide();
				element.attr('disabled', true);
			}
		};

		handleMultiValueChanged = () => {
			const checked = event.target.checked;
			if (checked) {
				$('#maxValuesContainer').show();
			} else {
				const element = $('#maxValuesContainer');
				element.hide();
				element.attr('disabled', true);
			}
		};
	</script>
@stop