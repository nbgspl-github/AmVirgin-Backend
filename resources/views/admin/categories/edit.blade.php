@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit category details'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-md-6 mx-auto">
							<form action="{{route('admin.categories.update',$main->getKey())}}" method="POST" data-parsley-validate="true" enctype="multipart/form-data">
								@csrf
								<div class="jumbotron py-1 px-3">
									<h6 class="display-6">Important!</h6>
									<hr class="my-2">
									<ul class="px-3">
										<li><p>There are 4 nesting levels or types for categories.</p></li>
										<li><p>Main<br>Main ► Category<br>Main ► Category ► Sub-Category<br>Main ►
												Category ► Sub-Category ► Vertical</p></li>
										<li>
											<p>Products can only be added to categories having type as
												<mark>Vertical</mark>
												.
											</p>
										</li>
										<li><p>You can add as many categories as you need.</p></li>
										<li>
											<p>To make a category eligible for product addition, set its type to
												<mark>Vertical</mark>
												.
											</p>
										</li>
										<li>
											<p>You may additionally set any category to inherit attributes of its
												parent. For example - if you are creating verticals named
												<mark>Casual Shoes</mark>
												, and
												<mark>Sports Shoes</mark>
												under
												<mark>Footwear</mark>
												, it is only logical to enable attribute inheritance for both of them
												since they both share attributes such as
												<mark>Color</mark>
												and
												<mark>Size</mark>
												, and
												<mark>Footwear</mark>
												as a parent can have both of these attributes.
											</p>
										</li>
									</ul>
								</div>
								<div class="form-group">
									<label>@required(Name)</label>
									<input type="text" name="name" class="form-control" required placeholder="Type category name" minlength="1" maxlength="255" value="{{old('name',$main->name)}}"/>
								</div>
								<div class="form-group">
									<label>@required(Parent)</label>
									<select name="parent_id" class="form-control" id="parentId" required onchange="handleTypeChanged(this.value,document.getElementById('option-item-'+this.value).getAttribute('data-type'));">
										<option value="" disabled selected>Choose</option>
										@foreach($roots as $root)
											<option value="{{$root['key']}}" @if($root['key']==$main->parent_id) selected @endif data-type="{{$root['type']}}" id="option-item-{{$root['key']}}">{{$root['name']}}</option>
											@foreach($root['children']['items'] as $category)
												<option value="{{ $category['key'] }}" @if($category['key']==$main->parent_id) selected @endif  data-type="{{$category['type']}}" id="option-item-{{$category['key']}}">{{$root['name']}}
													► {{$category['name']}}</option>
												@foreach($category['children']['items'] as $subCategory)
													<option value="{{ $subCategory['key'] }}" @if($subCategory['key']==$main->parent_id) selected @endif data-type="{{$subCategory['type']}}" id="option-item-{{$subCategory['key'] }}">{{$root['name']}}
														► {{$category['name']}} ► {{$subCategory['name']}}</option>
												@endforeach
											@endforeach
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="">@required(Type)</label>
									<select name="type" id="type" class="form-control">
										<option value="" disabled selected>Choose</option>
										<option value="{{\App\Library\Enums\Categories\Types::Category}}" @if($main->type==\App\Library\Enums\Categories\Types::Category) selected @endif>
											Category
										</option>
										<option value="{{\App\Library\Enums\Categories\Types::SubCategory}}" @if($main->type==\App\Library\Enums\Categories\Types::SubCategory) selected @endif>
											Sub-Category
										</option>
										<option value="{{\App\Library\Enums\Categories\Types::Vertical}}" @if($main->type==\App\Library\Enums\Categories\Types::Vertical) selected @endif>
											Vertical
										</option>
									</select>
								</div>
								<div class="form-group">
									<label>Listing</label>
									<select name="listing" class="form-control">
										@if($main->listingStatus==\App\Models\Category::LISTING_ACTIVE)
											<option value="{{\App\Models\Category::LISTING_ACTIVE}}" selected>
												Active
											</option>
											<option value="{{\App\Models\Category::LISTING_INACTIVE}}">Inactive
											</option>
										@else
											<option value="{{\App\Models\Category::LISTING_ACTIVE}}">
												Active
											</option>
											<option value="{{\App\Models\Category::LISTING_INACTIVE}}" selected>
												Inactive
											</option>

										@endif
									</select>
								</div>
								<div class="form-group">
									<label>Listing Order</label>
									<select name="order" class="form-control">
										<option value="0">0</option>
										@for($i=1;$i<=255;$i++)
											<option value="{{$i}}" @if($i==$main->order) selected @endif>{{$i}}</option>
										@endfor
									</select>
								</div>
								<div class="form-group">
									<label>Inherit Parent Attributes?</label>
									<div>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="inheritParentAttributes" name="inheritParentAttributes" onchange="handleMultiValueChanged();" @if($main->inheritParentAttributes==true) checked @endif>
											<label class="custom-control-label" for="inheritParentAttributes">Yes</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Icon</label>
									<input type="file" data-max-file-size="1M" name="icon" id="icon" data-allowed-file-extensions="jpg png jpeg" data-default-file="{{$main->icon}}">
								</div>
								<div class="form-group">
									<label>Catalog Template</label>
									<input name="catalog" class="form-control" type="file" accept=".xls, .xlsx" style="padding: .290rem .300rem">
								</div>
								<div class="form-group mb-0">
									<div class="row">
										<div class="col-6 pr-0">
											<button type="submit" class="btn btn-primary waves-effect waves-light btn-block">
												Update
											</button>
										</div>
										<div class="col-6">
											<a type="button" href="{{route('admin.categories.index')}}" class="btn btn-secondary waves-effect m-l-5 btn-block">
												Cancel
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script>
		$(document).ready(() => {
			$('#icon').dropify();
		});

		handleTypeChanged = (value, type) => {
			console.log(type);
			if (type === 'root') {
				$("select option[value=category]").prop('disabled', false);
				$("#type").val('category');
				$("select option[value=sub-category]").prop('disabled', true);
				$("select option[value=vertical]").prop('disabled', true);
			} else if (type === 'category') {
				$("select option[value=category]").prop('disabled', true);
				$("select option[value=sub-category]").prop('disabled', false);
				$("#type").val('sub-category');
				$("select option[value=vertical]").prop('disabled', true);
			} else if (type === 'sub-category') {
				$("select option[value=category]").prop('disabled', true);
				$("select option[value=sub-category]").prop('disabled', true);
				$("select option[value=vertical]").prop('disabled', false);
				$("#type").val('vertical');
			} else {

			}
		};
	</script>
@stop