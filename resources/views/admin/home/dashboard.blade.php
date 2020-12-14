@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-movie" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Videos</h4>
							<h5 class="">{{$stats->video}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-step-forward" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Series</h4>
							<h5 class="">{{$stats->series}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Movies</h4>
							<h5 class="">{{$stats->movie}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-0 mt-md-3">
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-movie" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Videos</h4>
							<h5 class="">{{$stats->newVideo}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-step-forward" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Series</h4>
							<h5 class="">{{$stats->newSeries}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">New Movies</h4>
							<h5 class="">{{$stats->newMovie}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-0 mt-md-3">
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-account" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Customers</h4>
							<h5 class="">{{$stats->customers}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-worker" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Sellers</h4>
							<h5 class="">{{$stats->sellers}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-flask" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Products</h4>
							<h5 class="">{{$stats->products}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-0 mt-md-3">
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-movie" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Orders</h4>
							<h5 class="">{{$stats->orders}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-step-forward" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Pending</h4>
							<h5 class="">{{$stats->pendingOrders}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0 pr-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Cancelled</h4>
							<h5 class="">{{$stats->cancelledOrders}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3 mb-sm-3 mb-md-0">
			<div class="card bg-primary text-white animatable" style="box-shadow: 0 2px 6px #cf3f43;">
				<div class="card-body">
					<div class="text-center">
						<div>
							<i class="mdi mdi-television" style="font-size: 35px"></i>
						</div>
						<div>
							<h4 class="mt-4">Delivered</h4>
							<h5 class="">{{$stats->deliveredOrders}}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		let token = null;
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