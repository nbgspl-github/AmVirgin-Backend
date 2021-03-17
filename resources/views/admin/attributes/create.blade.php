@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
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
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label for="description">@required (Description)
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute description as will appear to seller'])></i></label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type attribute description here">{{old('description')}}</textarea>
										</div>
										<div class="form-group">
											<label for="group">@required (Group)
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Groups help collect attributes belonging to same logical category'])></i></label>
                                            <select name="group" id="group" class="form-control" title="Choose..."
                                                    required>
                                                <option value="Main">Main</option>
                                                <option value="Material & Care">Material & Care</option>
                                                <option value="Size & Fit">Size & Fit</option>
                                                <option value="Specifications">Specifications</option>
                                            </select>
                                        </div>
                                        <div class="card p-3 shadow-none mb-3">
                                            <div class="form-group">
                                                <label>Allow entering multiple values?
                                                    <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Allow the seller to enter more than one value for this attribute for example - color for color-blocked t shirts.'])></i></label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="multiValue" name="multiValue" onchange="handleMultiValueChanged();">
														<label class="custom-control-label" for="multiValue">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group" id="maxValuesContainer">
												<label for="">Minimum number of input values</label>
												<input id="minValues" type="number" name="minValues" class="form-control" placeholder="Type a number here" value="{{old('minValues')}}" min="1" max="9999" disabled/>
											</div>
											<div class="form-group mb-0" id="maxValuesContainer">
												<label for="">Maximum number of input values</label>
												<input id="maxValues" type="number" name="maxValues" class="form-control" placeholder="Type a number here" value="{{old('maxValues')}}" min="2" max="10000" disabled/>
											</div>
										</div>
										<div class="form-group">
											<label>Attribute is required?
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Seller must fill or choose a value for this attribute, blank is not allowed.'])></i></label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="required" name="required">
													<label class="custom-control-label" for="required">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Use attribute in layered navigation?
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Enable showing this attribute and corresponding values as a filter in catalog listing.'])></i></label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="useInLayeredNavigation" name="useInLayeredNavigation">
													<label class="custom-control-label" for="useInLayeredNavigation">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Use attribute to create product variations?
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Use this attribute\'s value to create variations of product.'])></i></label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="useToCreateVariants" name="useToCreateVariants">
													<label class="custom-control-label" for="useToCreateVariants">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Show values of this attribute as options in catalog listing?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="showInCatalogListing" name="showInCatalogListing">
													<label class="custom-control-label" for="showInCatalogListing">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Combine multiple values as one?
												<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'When a seller is assigning multiple values to this attribute, should they be treated as one value. You should turn this on for attributes which have multi value option enabled and might need more than one value to describe that trait, but all the values should be treated as one value. For example - when creating an attribute Brand Color you should turn this on, since there can be garments which may have more than one color, but all those colors are treated as one color for the product.'])></i></label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="combineMultipleValues" name="combineMultipleValues">
													<label class="custom-control-label" for="combineMultipleValues">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Will this attribute and its values be visible to customer (front-end)?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="visibleToCustomers" name="visibleToCustomers" checked>
													<label class="custom-control-label" for="visibleToCustomers">Yes</label>
												</div>
											</div>
										</div>
										<div class="card p-3 shadow-none mb-0">
											<div class="form-group">
												<label>Attribute has predefined values?
													<i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Predefine a set of values that the seller must choose from such as size (L, M, S) etc.'])></i></label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="predefined" name="predefined" onchange="handlePredefinedChanged();">
														<label class="custom-control-label" for="predefined">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group mb-0">
												<label for="values">Values</label>
												<input id="values" type="text" name="values" class="form-control" placeholder="Click to provide values" value="{{old('values')}}" readonly disabled/>
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