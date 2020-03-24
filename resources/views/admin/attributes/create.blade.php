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
											<label for="name">@required (Category)</label>
											<div class="form-control p-0" style="min-height: 302px;">
												<ul style="list-style-type: none; max-height: 300px!important; overflow-y: scroll;" class="px-1 py-0 mb-0 " id="list">
													@foreach($categories as $topLevel)
														<li>
															<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$topLevel['name']}}">
																<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$topLevel['id']}}" @if($topLevel['popularCategory']) checked @endif value="{{$topLevel['id']}}">
																<label class="custom-control-label" for="check_{{$topLevel['id']}}">{{$topLevel['name']}}</label>
															</div>
															@if($topLevel['hasInner']==true)
																<ul style="list-style-type: none;">
																	@foreach($topLevel['inner'] as $inner)
																		<li>
																			<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$inner['name']}}">
																				<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$inner['id']}}" @if($inner['popularCategory']) checked @endif value="{{$inner['id']}}">
																				<label class="custom-control-label" for="check_{{$inner['id']}}">{{$inner['name']}}</label>
																			</div>
																			@if($inner['hasInner']==true)
																				<ul style="list-style-type: none;">
																					@foreach($inner['inner'] as $innerNext)
																						<li>
																							<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$innerNext['name']}}">
																								<input type="checkbox" name="category[]" class="custom-control-input" id="check_{{$innerNext['id']}}" @if($innerNext['popularCategory']) checked @endif value="{{$innerNext['id']}}">
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
											<label for="name">@required (Name or Key)</label>
											<input id="name" type="text" name="name" class="form-control" required placeholder="Type a name" value="{{old('name')}}"/>
										</div>
										<div class="form-group">
											<label for="code">@required (Code)</label>
											<input id="code" type="text" name="code" class="form-control" required placeholder="Type a code (without spaces)" value="{{old('code')}}"/>
										</div>
										<div class="form-group">
											<label for="sellerInterfaceType">@required (User interface for seller panel)</label>
											<select name="sellerInterfaceType" id="sellerInterfaceType" class="form-control" required onchange="handleSellerInterfaceTypeChanged(this.value);">
												<option value="" selected disabled>Choose</option>
												<option value="select">Select</option>
												<option value="text">Text</option>
												<option value="text-area">Text Area</option>
												<option value="radio">Radio</option>
											</select>
										</div>
										<div class="form-group" id="values-container">
											<label for="values">@required (Valid set of values)</label>
											<input id="values" type="text" name="values" class="form-control" required placeholder="Type here the valid set of values for this attribute" value="{{old('values')}}"/>
										</div>
										<div class="form-group">
											<label for="customerInterfaceType">@required (User interface for customer)</label>
											<select name="customerInterfaceType" id="customerInterfaceType" class="form-control" required>
												<option value="" selected disabled>Choose</option>
												<option value="readable">Text Label</option>
												<option value="options">Options</option>
											</select>
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
	<script src="{{asset('assets/admin/utils/MultiEntryModal.js')}}"></script>
	<script>
		window.onload = () => {
			$('#values-container').hide();
			MultiEntryModal.setupMultiEntryModal({
				title: 'Valid attribute values',
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

		handleSellerInterfaceTypeChanged = (value) => {
			if (value === 'select' || value === 'radio') {
				$('#values-container').show();
				$('#values').prop('disabled', false);
			} else {
				$('#values-container').hide();
				$('#values').prop('disabled', true);
			}
		};

		handleSearch = (value) => {
			$("div[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};
	</script>
@stop