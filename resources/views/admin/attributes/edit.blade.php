@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Update attribute details'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.update',$attribute->id)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header bg-secondary">Attributes help categorizing variants of the same product.</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">@required (Label)
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute label or name as will appear to admin, seller and customer'])></i></label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name',$attribute->name)}}"/>
										</div>
										<div class="form-group">
											<label for="description">@required (Description)
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute description as will appear to seller'])></i></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type attribute description here">{{old('description',$attribute->description)}}</textarea>
										</div>
										<div class="form-group">
											<label for="group">@required (Group)
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Groups help collect attributes belonging to same logical category'])></i></label>
											<select name="group" id="group" class="form-control selectpicker" title="Choose..." required>
												<option value="Main" @if($attribute->group=="Main") selected @endif>Main</option>
												<option value="Material & Care" @if($attribute->group=="Material & Care") selected @endif>Material & Care</option>
												<option value="Size & Fit" @if($attribute->group=="Size & Fit") selected @endif>Size & Fit</option>
												<option value="Specifications" @if($attribute->group=="Specifications") selected @endif>Specifications</option>
											</select>
										</div>
										@if($attribute->predefined)
											<div class="form-group mb-0">
												<label for="values">Values</label>
												<input id="values" type="text" name="values" class="form-control" placeholder="Click to provide values" value="{{old('values',$attribute->values)}}" readonly/>
											</div>
										@endif
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
		let elements = {
			minValueInput: null,
			maxValueInput: null,
			multiValueCheckBox: null,
			valuesInput: null
		};

		window.onload = () => {
			elements = {
				minValueInput: $('#minValues'),
				maxValueInput: $('#maxValues'),
				multiValueCheckBox: $('#multiValue'),
				valuesInput: $('#values')
			};
			MultiEntryModal.setupMultiEntryModal({
				title: 'Attribute values',
				separator: '|',
				key: 'values',
				boundEditBoxId: 'values',
				modalId: 'values_multiEntryModal',
				inputClass: 'values_input',
				listGroupId: 'values_listGroup',
				addMoreButtonId: 'values_addMoreButton',
				doneButtonId: 'values_doneButton',
				deleteButtonClass: 'values_delete-button',
				template: `<li class="list-group-item px-0 py-1 border-0 animated slideInDown">
								\t\t\t\t\t\t<div class="col-auto px-0">
								\t\t\t\t\t\t\t<div class="input-group mb-2">
								\t\t\t\t\t\t\t\t<input type="text" class="form-control values_input" placeholder="Type here..." value=@{{value}}>
								\t\t\t\t\t\t\t\t<div class="input-group-append">
								\t\t\t\t\t\t\t\t\t<div class="input-group-text text-white bg-primary values_delete-button">&times;</div>
								\t\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t\t</div>
								\t\t\t\t\t\t</div>
								\t\t\t\t\t
							</li>`
			});
		};

		handleMultiValueChanged = () => {
			if (event.target.checked) {
				enable(elements.maxValueInput);
				enable(elements.minValueInput);
				required(elements.maxValueInput);
				required(elements.minValueInput);
			} else {
				disable(elements.maxValueInput);
				disable(elements.minValueInput);
				optional(elements.maxValueInput);
				optional(elements.minValueInput);
				clear(elements.maxValueInput);
				clear(elements.minValueInput);
			}
		};

		handlePredefinedChanged = () => {
			if (event.target.checked) {
				enable(elements.valuesInput);
				required(elements.valuesInput);
			} else {
				disable(elements.valuesInput);
				optional(elements.valuesInput);
				clear(elements.valuesInput);
			}
		};

		enable = (e) => {
			e.prop('disabled', false);
		};

		disable = (e) => {
			e.prop('disabled', true);
		};

		required = (e) => {
			e.attr('required', true);
		};

		optional = (e) => {
			e.prop('required', false);
		};

		trigger = (e, name) => {
			e.trigger(name);
		};

		clear = (e) => {
			e.parsley().reset();
		};

		checked = (e) => {
			return e.prop('checked') === true;
		};
	</script>
@stop