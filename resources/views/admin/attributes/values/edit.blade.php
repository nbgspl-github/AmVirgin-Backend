@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Modify attribute value(s)'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.values.store',$attribute->id())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										Attribute values provide descriptions and options for various traits of a product.
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">Attribute name</label>
											<input class="form-control bg-white" value="{{$attribute->name()}}" readonly disabled/>
										</div>
										<div class="form-group">
											<label for="name">Description</label>
											<input class="form-control bg-white" value="{{$attribute->description()}}" readonly disabled/>
										</div>
										<div class="form-group">
											<label for="name">Category</label>
											<input class="form-control bg-white" value="{{$parent}}" readonly disabled/>
										</div>
										<div class="form-group mb-0">
											<label for="values">@required(Values)</label>
											<input id="values" type="text" name="values" class="form-control" required placeholder="Click here to input attribute values/options" value="{{old('values',$values)}}"/>
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
			maxValueInput: null,
			boundedCheckBox: null,
			attributeTypeDropdown: null,
			segmentPriority: null
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
				maxValueInput: $('#maxValues'),
				boundedCheckBox: $('#bounded'),
				attributeTypeDropdown: $('#attributeType'),
				segmentPriority: $('#segmentPriority')
			};
			MultiEntryModal.setupMultiEntryModal({
				title: 'Input attribute values',
				separator: '/',
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
				required(elements.maxValueInput);
			} else {
				disable(elements.maxValueInput);
				optional(elements.maxValueInput);
				clear(elements.maxValueInput);
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

		validateChecks = () => {

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