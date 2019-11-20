@extends('layouts.header')
@section('content')
	<div class="row mx-1">
		<div class="card card-body mt-4">
			<h5>Site Statistics</h5>
			<div class="row pt-4 pb-1">
				<div class="col-lg-3">
					<div class="card bg-secondary text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-user" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Total Users</h4>
									<h5 class="">128</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-danger  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-video-clapper" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Total Videos</h4>
									<h5 class="">89</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-warning  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-id-badge" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Subscribers</h4>
									<h5 class="">128</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-success  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-bell" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Total Events</h4>
									<h5 class="">25</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mx-1">
		<div class="card card-body mt-4 mb-4">
			<h5>Todays Statistics</h5>
			<div class="row pt-4 pb-1">
				<div class="col-lg-3">
					<div class="card bg-secondary  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-user" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">New Users</h4>
									<h5 class="">12</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-danger  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-video-clapper" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">New Videos</h4>
									<h5 class="">8</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-warning  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-id-badge" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">New Subscribers</h4>
									<h5 class="">8</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="card bg-success  text-white shadow-sm">
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="ti-bell" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Todays Events</h4>
									<h5 class="">2</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection