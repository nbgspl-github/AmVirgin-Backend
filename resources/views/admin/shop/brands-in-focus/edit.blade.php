@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Modify Brands in Focus'])
				</div>
				<form action="{{route('admin.shop.brands-in-focus.update')}}" method="post" onsubmit="handleSubmit()">
					@csrf
					<div class="card-body animatable">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="row">
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">First item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Second item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Third item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Fourth item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Fifth item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Sixth item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Seventh item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 pr-0">
										<div class="card shadow-sm border animated slideInLeft">
											<div class="card-body">
												<h6 class="card-title">Eighth item</h6>
												<div class="row mt-4">
													<div class="col-12">
														<select name="focus[]" id="" class="form-control">
															@foreach($categories as $topLevel)
																<option value="{{$topLevel['id']}}">{{$topLevel['name']}}</option>
															@endforeach
														</select>
													</div>
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
									<div class="col-6 pr-0">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Save
										</button>
									</div>
									<div class="col-6 pr-0">
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
		handleSubmit = () => {
			event.preventDefault();
			const elements = document.getElementsByName('focus[]');
			elements.forEach((item) => {
				console.log($(item).val());
			});
		}
	</script>
@stop