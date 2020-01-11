@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="javascript:void(0);" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-movie" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Videos</h4>
							<h5 class="">{{$payload['video']}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-step-forward" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Series</h4>
							<h5 class="">128</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Live TV</h4>
							<h5 class="">25</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-0 mt-md-3">
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-movie" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Videos</h4>
							<h5 class="">8</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-step-forward" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Series</h4>
							<h5 class="">8</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Live TV</h4>
							<h5 class="">2</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-lg-12">
			<div class="card custom-card shadow-sm animatable">
				<div class="card-header">
					<h3 class="mt-0 page-title mb-0">More Views</h3>
				</div>
				<div class="card-body rounded-lg">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0">
							<div class="card card-body border border-danger p-3 animatable" style="box-shadow: 0 2px 10px rgba(253,110,119,0.3);">
								<h6 class="m-0 mb-2">Videos</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0">
							<div class="card card-body border border-danger p-3 animatable" style="box-shadow: 0 2px 10px rgba(253,110,119,0.3);">
								<h6 class="m-0 mb-2">Series Average</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0">
							<div class="card card-body border border-danger p-3 animatable" style="box-shadow: 0 2px 10px rgba(253,110,119,0.3);">
								<h6 class="m-0 mb-2">Episodes</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
							<div class="card card-body border border-danger p-3 animatable" style="box-shadow: 0 2px 10px rgba(253,110,119,0.3);">
								<h6 class="m-0 mb-2">Live TV</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>

		$(document).ready(() => {
			anime.timeline({loop: false}).add({
				targets: ['.animatableX'],
				translateX: [-40, 0],
				translateZ: 0,
				opacity: [0, 1],
				easing: "easeOutExpo",
				duration: 1000,
				delay: (el, i) => 100 * i
			});
			anime.timeline({loop: false}).add({
				targets: ['.animatable'],
				translateY: [-40, 0],
				translateZ: 0,
				opacity: [0, 1],
				easing: "easeOutExpo",
				duration: 2000,
				delay: (el, i) => 200 * i
			});
		});
	</script>
@stop