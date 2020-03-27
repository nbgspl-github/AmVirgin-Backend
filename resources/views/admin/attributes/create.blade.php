@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute'])
				</div>
				<form id="uploadForm" action="{{route('admin.products.attributes.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data" onsubmit="validateChecks();">
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
											<label for="name">@required (Category)</label>
											<div class="form-control p-0" style="min-height: 302px;">
												<ul style="list-style-type: none; max-height: 300px!important; overflow-y: scroll;" class="px-1 py-0 mb-0 " id="list">
													@foreach($categories as $topLevel)
														<li>
															<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$topLevel['name']}}">
																<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$topLevel['id']}}" value="{{$topLevel['id']}}" onchange="countCheckboxes();">
																<label class="custom-control-label" for="check_{{$topLevel['id']}}">{{$topLevel['name']}}</label>
															</div>
															@if($topLevel['hasInner']==true)
																<ul style="list-style-type: none;">
																	@foreach($topLevel['inner'] as $inner)
																		<li>
																			<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$inner['name']}}">
																				<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$inner['id']}}" value="{{$inner['id']}}" onchange="countCheckboxes();">
																				<label class="custom-control-label" for="check_{{$inner['id']}}">{{$inner['name']}}</label>
																			</div>
																			@if($inner['hasInner']==true)
																				<ul style="list-style-type: none;">
																					@foreach($inner['inner'] as $innerNext)
																						<li>
																							<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$innerNext['name']}}">
																								<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$innerNext['id']}}" value="{{$innerNext['id']}}" onchange="countCheckboxes();">
																								<label class="custom-control-label" for="check_{{$innerNext['id']}}">{{$innerNext['name']}}</label>
																							</div>
																						</li>
																					@endforeach
																				</ul>
																			@endif
																		</li>
																	@endforeach
																</ul>
															@endif
														</li>
													@endforeach
												</ul>
											</div>
										</div>
										<div class="form-group">
											<label for="name">@required (Name)</label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label for="description">@required (Description)</label>
											<textarea id="description" name="description" class="form-control" required placeholder="Type attribute description here">{{old('description')}}</textarea>
										</div>
										<div class="card custom-card p-3 shadow-none mb-3">
											<div class="form-group">
												<label for="sellerInterfaceType">@required (User interface for seller panel)</label>
												<select name="sellerInterfaceType" id="sellerInterfaceType" class="form-control" required onchange="handleSellerInterfaceTypeChanged(this.value);">
													<option value="" selected disabled>Choose</option>
													<option value="dropdown">DropDown</option>
													<option value="input">Input</option>
													<option value="text">Text</option>
													<option value="radio">Radio</option>
												</select>
											</div>
											<div class="form-group">
												<label for="attributeType">@required (Type of value(s) for this attribute)</label>
												<select name="primitiveType" id="attributeType" class="form-control" disabled onchange="handleAttributeTypeChanged(this.value);">
													@foreach($types as $type)
														<option value="{{$type->getKey()}}" @include('admin.extras.tooltip.right', ['title' => $type->name()])>{{$type->name()}}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group">
												<label>Limit input range?</label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="bounded" name="bounded" onchange="handleBoundStatusChanged();" disabled>
														<label class="custom-control-label" for="bounded" id="ghanta">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group mb-0">
												<label>Enter upper and lower limit</label>
												<div class="row">
													<div class="col-6"><input type="number" name="minimum" id="minimum" class="form-control" placeholder="Lower limit" disabled min="0" step="1" value="{{old('minimum')}}"></div>
													<div class="col-6"><input type="number" name="maximum" id="maximum" class="form-control" placeholder="Upper limit" disabled min="0" step="1" value="{{old('maximum')}}"></div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="customerInterfaceType">@required (User interface for customer)</label>
											<select name="customerInterfaceType" id="customerInterfaceType" class="form-control" required>
												<option value="" selected disabled>Choose</option>
												<option value="readable">Text Label</option>
												<option value="options">Options</option>
											</select>
										</div>
										<div class="card custom-card p-3 shadow-none mb-3">
											<div class="form-group">
												<label>Should this attribute's value(s) be used to form the name of the product being associated?</label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="productNameSegment" name="productNameSegment" onchange="handleProductSegmentChanged();">
														<label class="custom-control-label" for="productNameSegment">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group mb-0">
												<label for="segmentPriority">Segment priority</label>
												<select name="segmentPriority" id="segmentPriority" class="form-control" @include('admin.extras.tooltip.top', ['title' => 'Defines a number used to determine where in the product name, the value(s) of this attribute will appear. Valid from 1 through 10.']) disabled>
													<option value="" selected disabled>Choose</option>
													@for($i=1;$i<=10;$i++)
														<option value="{{$i}}">{{$i}}</option>
													@endfor
												</select>
											</div>
										</div>
										<div class="card custom-card p-3 shadow-none mb-3">
											<div class="form-group">
												<label>Enable multiple value input?</label>
												<div>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="multiValue" name="multiValue" onchange="handleMultiValueChanged();" disabled>
														<label class="custom-control-label" for="multiValue">Yes</label>
													</div>
												</div>
											</div>
											<div class="form-group mb-0" id="maxValuesContainer">
												<label for="">Maximum number of input values</label>
												<input id="maxValues" type="number" name="maxValues" class="form-control" placeholder="Type a number here" value="{{old('maxValues')}}" min="2" max="10000" disabled/>
											</div>
										</div>
										<div class="form-group">
											<label>Is this a required attribute?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="required" name="required">
													<label class="custom-control-label" for="required">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group mb-0">
											<label>Should this be visible as a candidate in available filters?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="filterable" name="filterable">
													<label class="custom-control-label" for="filterable">Yes</label>
												</div>
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
	<script>
		let elements = {
			minimumInput: null,
			maximumInput: null,
			maxValueInput: null,
			boundedCheckBox: null,
			multiValueCheckBox: null,
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
				multiValueCheckBox: $('#multiValue'),
				attributeTypeDropdown: $('#attributeType'),
				segmentPriority: $('#segmentPriority')
			};
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
			if (count === 0) {
				event.preventDefault();
				toastr.warning('You must select at-least one category.');
				return false;
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