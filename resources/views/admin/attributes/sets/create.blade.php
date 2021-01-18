@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute set'])
				</div>
				<form id="uploadForm" action="{{route('admin.attributes.sets.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header bg-secondary">
										<div class="row">
											<div class="col-8 my-auto">Attributes sets define characteristic for a particular type of product.</div>
											<div class="col-4">
												<input type="text" class="form-control" name="" id="" placeholder="Search for an attribute" onkeyup="handleSearch(this.value);">
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="jumbotron py-1 px-3">
											<h6 class="display-6">Important!</h6>
											<hr class="my-2">
											<ul class="px-3">
												<li>
													<p>Use attribute sets to define all characteristics of a product, such as Color, Size, Machine Washable, Material, Texture, Capacity, Pattern etc.</p>
												</li>
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
												<li>
													<p>You can choose all the attributes that you find eligible for a particular category and which you see fit to describe a particular product entirely, and add them to a set.</p>
												</li>
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
											<label>@required(Category)</label>
											<select name="categoryId" class="form-control selectpicker" id="categoryId" title="Choose..." required>
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
										<div class="form-group mb-0">
											<label for="exampleFormControlSelect2">@required(Select attributes to add to this set)</label>
											<table class="table table-hover mb-0">
												<thead class="thead-light">
												<tr>
													<th scope="col" class="text-center">Mark</th>
													<th scope="col">Label</th>
													<th scope="col">Code</th>
													<th scope="col">Values</th>
												</tr>
												</thead>
												<tbody>
												@foreach($attributes as $attribute)
													<tr data-name="{{$attribute->name}}">
														<td class="text-center">
															<div>
																<div class="custom-control custom-checkbox">
																	<input type="checkbox" class="custom-control-input" id="attribute-{{$attribute->id}}" name="selected[]" value="{{$attribute->id}}" onchange="handleMarkChanged({{$attribute->id}});">
																	<label class="custom-control-label" for="attribute-{{$attribute->id}}">&nbsp;</label>
																</div>
															</div>
														</td>
														<td>{{$attribute->name}}</td>
														<td>{{$attribute->code}}</td>
														<td>
															@if($attribute->predefined)
																@foreach($attribute->values as $value)
																	<span class="badge badge-secondary font-14 font-weight-normal">{{$value}}</span>
																@endforeach
															@else
																{{\App\Library\Utils\Extensions\Str::NotAvailable}}
															@endif
														</td>
													</tr>
												@endforeach
												</tbody>
											</table>
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

		handleMarkChanged = (code) => {
			if (event.target.checked) {
				$('#' + code).prop('disabled', false);
			} else {
				$('#' + code).prop('disabled', true);
			}
		};
	</script>
@stop