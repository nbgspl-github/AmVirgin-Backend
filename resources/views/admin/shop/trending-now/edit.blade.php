@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Modify Trending Now Section'])
				</div>
				<form action="{{route('admin.shop.trending-now.update')}}" method="post">
					@csrf
					<div class="card-body animatable">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-header">
										<div class="row">
											<div class="col-8 my-auto">Choose upto 4 categories</div>
											<div class="col-4"><input type="text" class="form-control" name="" id="" placeholder="Search for a category" onkeyup="handleSearch(this.value);"></div>
										</div>
									</div>
									<div class="card-body">
										<ul style="list-style-type: none;" class="px-0 py-0 mb-0" id="list">
											@foreach($categories as $topLevel)
												<li>
													<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$topLevel['name']}}">
														<input type="checkbox" name="choice[]" class="custom-control-input" id="check_{{$topLevel['id']}}" onchange="handleStateChanged();" @if($topLevel['trendingNow']) checked @endif value="{{$topLevel['id']}}">
														<label class="custom-control-label" for="check_{{$topLevel['id']}}">{{$topLevel['name']}}</label>
													</div>
													@if($topLevel['hasInner']==true)
														<ul style="list-style-type: none;">
															@foreach($topLevel['inner'] as $inner)
																<li>
																	<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$inner['name']}}">
																		<input type="checkbox" name="choice[]" class="custom-control-input" id="check_{{$inner['id']}}" onchange="handleStateChanged();" @if($inner['trendingNow']) checked @endif value="{{$inner['id']}}">
																		<label class="custom-control-label" for="check_{{$inner['id']}}">{{$inner['name']}}</label>
																	</div>
																	@if($inner['hasInner']==true)
																		<ul style="list-style-type: none;">
																			@foreach($inner['inner'] as $innerNext)
																				<li>
																					<div class="custom-control custom-checkbox mr-sm-2" data-name="{{$innerNext['name']}}">
																						<input type="checkbox" name="choice[]" class="custom-control-input" id="check_{{$innerNext['id']}}" onchange="handleStateChanged();" @if($innerNext['trendingNow']) checked @endif value="{{$innerNext['id']}}">
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
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-12 col-md-8 mx-auto">
								<div class="row">
									<div class="col-6 pr-0">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.shop.choices")}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
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
@stop

@section('javascript')
	<script type="application/javascript">
		let count = 0;
		window.onload = () => {
			count = $('input[type=checkbox]:checked').length;
		};

		handleStateChanged = () => {
			const checked = event.target.checked;
			if (checked) {
				if (count >= 8) {
					alertify.log('Only 4 categories are allowed.');
					event.target.checked = false;
				} else {
					count++;
				}
			} else {
				if (count >= 0) {
					count--;
				}
			}
		};

		handleSearch = (value) => {
			$("div[data-name]").filter((index, item) => {
				$(item).toggle($(item).attr('data-name').toLowerCase().indexOf(value.toLowerCase()) !== -1);
			});
		};
	</script>
@stop