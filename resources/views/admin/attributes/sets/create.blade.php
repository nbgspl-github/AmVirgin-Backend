@extends('admin.app.app')
@section('content')
	@include('admin.modals.multiEntryModal',['key'=>'values'])
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Create an attribute'])
				</div>
				<form id="uploadForm" action="{{route('admin.attributes.sets.store')}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										<div class="row">
											<div class="col-8 my-auto">Attributes sets define characteristic for a particular type of product.</div>
											<div class="col-4"><input type="text" class="form-control" name="" id="" placeholder="Search for an attribute" onkeyup="handleSearch(this.value);"></div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label for="name">@required (Name) <i class="mdi mdi-help-circle-outline" @include('admin.extras.tooltip.top', ['title' => 'Attribute label or name as will appear to admin, seller and customer'])></i></label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label>@required(Category)</label>
											<select name="categoryId" class="form-control" id="categoryId" required>
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
										<div class="form-group mb-0">
											<label for="exampleFormControlSelect2">Select attributes to build a set</label>
											<ul class="list-group">
												@foreach($attributes as $attribute)
													<li class="list-group-item" data-name="{{$attribute->name()}}">
														<div class="row">
															<div class="col-6">
																<div>
																	<div class="custom-control custom-checkbox">
																		<input type="checkbox" class="custom-control-input" id="attribute-{{$attribute->id()}}" name="selected[]" value="{{$attribute->id()}}">
																		<label class="custom-control-label" for="attribute-{{$attribute->id()}}">{{$attribute->name()}} <span class="badge badge-light font-14">{{$attribute->code()}}</span></label>
																	</div>
																</div>
															</div>
															<div class="col-6"><span class="badge badge-secondary float-right font-14">{{\App\Classes\Str::join(', ',$attribute->values())}}</span></div>
														</div>
													</li>
												@endforeach
											</ul>
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
			$("li[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};
	</script>
@stop