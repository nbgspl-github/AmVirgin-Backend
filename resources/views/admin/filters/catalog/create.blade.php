@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create a catalog filter'])
				</div>
				<form id="uploadForm" action="{{route('admin.filters.catalog.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										<div class="row">
											<div class="col-8 my-auto">Filters allow a customer to cut-short the given list of products as per their requirement.</div>
											<div class="col-4"><input type="text" class="form-control" name="" id="" placeholder="Search for an attribute" onkeyup="handleSearch(this.value);"></div>
										</div>
									</div>
									<div class="card-body">
										<div class="jumbotron py-1 px-3">
											<h6 class="display-6">Important!</h6>
											<hr class="my-2">
											<ul class="px-3">
												<li><p>Use attribute sets to define all characteristics of a product, such as Color, Size, Machine Washable, Material, Texture, Capacity, Pattern etc.</p></li>
												<li>
													<p>You can have one attribute set per category because a particular type of category will only categorize products having the same characteristics. For example - A category named
														<mark>T Shirt</mark>
														will only contain products which quality for a T Shirt and not something like a
														<mark>Mobile Phone</mark>
														or
														<mark>Shoe</mark>
														.
													</p>
												</li>
												<li><p>You can choose all the attributes that you find eligible for a particular category and which you see fit to describe a particular product entirely, and add them to a set.</p></li>
												<li>
													<p>Once a set is created and bound to a category, it will automatically be retrieved for the seller once he chooses the category he wishes to add a product into.</p>
												</li>
												<li>
													<p>During the process of product creation, the seller will be required to fill or select values for all attributes marked as required.</p>
												</li>
												<li>
													<p>All attributes within a set which are marked to be used to create variants, their values will be used to create all possible combinations of the product.</p>
												</li>
												<li>
													<p>Other attributes which are not marked to be used to create variants will form the product description and specification.</p>
												</li>
											</ul>
										</div>
										<div class="form-group">
											<label for="label">Label <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Filter section title as will appear to customers, leave blank for no title'])></i></label>
											<input id="label" type="text" name="label" class="form-control" placeholder="Type a label" value="{{old('label')}}"/>
										</div>
										<div class="form-group">
											<label>@required(Category)</label>
											<select name="categoryId" class="form-control" id="categoryId" required onchange="handleCategoryChanged(this.value);">
												<option value="" disabled selected>Choose</option>
												@foreach($roots as $root)
													@foreach($root['children']['items'] as $category)
														<option value="{{ $category['key'] }}" data-type="{{$category['type']}}" id="option-item-{{$category['key']}}">{{$root['name']}} ► {{$category['name']}}</option>
														@foreach($category['children']['items'] as $subCategory)
															<option value="{{ $subCategory['key'] }}" data-type="{{$subCategory['type']}}" id="option-item-{{$subCategory['key'] }}">{{$root['name']}} ► {{$category['name']}} ► {{$subCategory['name']}}</option>
															@foreach($subCategory['children']['items'] as $vertical)
																<option value="{{ $vertical['key'] }}" data-type="{{$vertical['type']}}" id="option-item-{{$vertical['key'] }}">{{$root['name']}} ► {{$category['name']}} ► {{$subCategory['name']}} ► {{$vertical['name']}}</option>
															@endforeach
														@endforeach
													@endforeach
												@endforeach
											</select>
										</div>
										<div class="form-group" id="isCustomValueContainer">
											<label>Is this a built in filter? <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'If an inbuilt filter does not suffice for your needs, you may create a custom filter targeting an attribute.'])></i></label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="required" name="builtIn" onchange="handleCustomFilterChanged();" checked>
													<label class="custom-control-label" for="required">Yes</label>
												</div>
											</div>
										</div>
										<div class="form-group" id="builtInFilterContainer">
											<label for="exampleFormControlSelect2">Select an inbuilt filter</label>
											<select name="builtInType" id="builtInFilter" class="form-control">
												@foreach(\App\Models\CatalogFilter::BuiltInFilters as $key=>$value)
													<option value="{{$value}}">{{$key}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group" id="attributesContainer">
											<label for="exampleFormControlSelect2">Select attribute to bind to this filter</label>
											<select name="attributeId" id="attributeId" class="form-control" disabled>

											</select>
										</div>
										<div class="form-group" id="allowMultiValueContainer">
											<label for="exampleFormControlSelect2">Allow multiple options to be selected by customers <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'If enabled, all options for this filter will be shown with check boxes, else they\'ll be drawn with radio buttons'])></i></label>
											<select name="allowMultiValue" id="allowMultiValue" class="form-control" disabled>
												<option value="0">Disable</option>
												<option value="1">Enable</option>
											</select>
										</div>
										<div class="form-group mb-0" id="isCustomValueContainer">
											<label>Activate this filter, once created?</label>
											<div>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="required" name="active" checked>
													<label class="custom-control-label" for="active">Yes</label>
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
		onInitialize = () => {
			$('#attributesContainer').hide();
			$('#builtInFilterContainer').hide();
			$('#allowMultiValueContainer').hide();
			$('#isCustomValueContainer').hide();
		};

		countCheckboxes = () => {
			if (event.target.checked) {
				count++;
			} else {
				count--;
			}
		};

		handleSearch = (value) => {
			$("tr[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};

		handleCategoryChanged = (categoryId) => {
			$('#attributesContainer').fadeIn();
			$('#builtInFilterContainer').fadeIn();
			$('#allowMultiValueContainer').fadeIn();
			$('#isCustomValueContainer').fadeIn();
			axios.get('category/' + categoryId + '/attributes').then((response) => {
				$('#attributeId').html(response.data.options);
			}).catch((error) => {
				alertify.log('Something went wrong!');
			});
		};

		handleCustomFilterChanged = () => {
			if (!event.target.checked) {
				$('#attributeId').prop('disabled', false);
				$('#allowMultiValue').prop('disabled', false);
				$('#builtInFilter').prop('disabled', true);
			} else {
				$('#attributeId').prop('disabled', true);
				$('#allowMultiValue').prop('disabled', true);
				$('#builtInFilter').prop('disabled', false);
			}
		};
	</script>
@stop