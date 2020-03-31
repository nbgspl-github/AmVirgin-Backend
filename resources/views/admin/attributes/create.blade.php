@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										<div class="row">
											<div class="col-8 my-auto">Attributes help categorizing variants of the same product.</div>
											<div class="col-4"><input type="text" class="form-control" name="" id="" placeholder="Search for a category" onkeyup="handleSearch(this.value);"></div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">@required (Label) <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute label or name as will appear to admin, seller and customer'])></i></label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label for="description">@required (Description) <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute description or name as will appear to admin, seller and customer'])></i></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type attribute description here">{{old('description')}}</textarea>
										</div>
										<div class="card custom-card p-3 shadow-none mb-3">
											<div class="form-group">
												<label>Allow entering multiple values?</label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="multiValue" name="multiValue" onchange="handleMultiValueChanged();">
														<label class="custom-control-label" for="multiValue">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group" id="maxValuesContainer">
												<label for="">Minimum number of input values</label>
												<input id="minValues" type="number" name="minValues" class="form-control" placeholder="Type a number here" value="{{old('minValues')}}" min="2" max="10000" disabled/>
											</div>
											<div class="form-group mb-0" id="maxValuesContainer">
												<label for="">Maximum number of input values</label>
												<input id="maxValues" type="number" name="maxValues" class="form-control" placeholder="Type a number here" value="{{old('maxValues')}}" min="2" max="10000" disabled/>
											</div>
										</div>
										<div class="form-group">
											<label>Attribute is required?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="required" name="required">
													<label class="custom-control-label" for="required">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Use attribute in layered navigation?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="useInLayeredNavigation" name="useInLayeredNavigation">
													<label class="custom-control-label" for="useInLayeredNavigation">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Use attribute to create product variations?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="useToCreateVariants" name="useToCreateVariants">
													<label class="custom-control-label" for="useToCreateVariants">Yes</label>
												</div>
											</div>
										</div>
										<div class="card custom-card p-3 shadow-none mb-3">
											<div class="form-group">
												<label>Attribute has predefined values?</label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="predefined" name="predefined" onchange="handlePredefinedChanged();">
														<label class="custom-control-label" for="predefined">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group mb-0">
												<label for="values">Values</label>
												<input id="values" type="text" name="values" class="form-control bg-white" required placeholder="Click to provide values" value="{{old('values')}}" readonly disabled/>
											</div>
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
		let elements = {
			minimumInput: null,
			maximumInput: null,
			minValueInput: null,
			maxValueInput: null,
			boundedCheckBox: null,
			multiValueCheckBox: null,
			attributeTypeDropdown: null,
			segmentPriority: null,
			valuesInput: null
		};
		const sellerInterfaceTypes = {
			Input: 'input'
		};
		const attributeTypes = {
			Float: 'float',
			Integer: 'int'
		};
		let count = 0;

		window.onload = () => {
			elements = {
				minimumInput: $('#minimum'),
				maximumInput: $('#maximum'),
				minValueInput: $('#minValues'),
				maxValueInput: $('#maxValues'),
				boundedCheckBox: $('#bounded'),
				multiValueCheckBox: $('#multiValue'),
				attributeTypeDropdown: $('#attributeType'),
				segmentPriority: $('#segmentPriority'),
				valuesInput: $('#values')
			};
			MultiEntryModal.setupMultiEntryModal({
				title: 'Attribute values',
				separator: ';',
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

		countCheckboxes = () => {
			if (event.target.checked) {
				count++;
			} else {
				count--;
			}
		};

		handleSearch = (value) => {
			$("div[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};

		handleSellerInterfaceTypeChanged = (value) => {
			if (value === sellerInterfaceTypes.Input) {
				enable(elements.attributeTypeDropdown);
			} else {
				elements.attributeTypeDropdown[0].selectedIndex = 0;
				trigger(elements.attributeTypeDropdown, 'change');
				disable(elements.attributeTypeDropdown);
			}
		};

		handleAttributeTypeChanged = (value) => {
			if (value === attributeTypes.Float || value === attributeTypes.Integer) {
				enable(elements.boundedCheckBox);
			} else {
				if (checked(elements.boundedCheckBox)) {
					trigger(elements.boundedCheckBox, 'click');
					trigger(elements.boundedCheckBox, 'change');
				}
				disable(elements.boundedCheckBox);
			}
		};

		handleBoundStatusChanged = () => {
			if (event.target.checked) {
				enable(elements.minimumInput);
				required(elements.minimumInput);
				enable(elements.maximumInput);
				required(elements.maximumInput);
			} else {
				disable(elements.minimumInput);
				optional(elements.minimumInput);
				disable(elements.maximumInput);
				optional(elements.maximumInput);
				clear(elements.minimumInput);
				clear(elements.maximumInput);
			}
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

		handleProductSegmentChanged = () => {
			if (event.target.checked) {
				enable(elements.segmentPriority);
				required(elements.segmentPriority);
			} else {
				disable(elements.segmentPriority);
				optional(elements.segmentPriority);
				clear(elements.segmentPriority);
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