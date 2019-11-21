@extends('layouts.header')
@section('content')
	@include('layouts.breadcrumbs', ['data' => ['Dashboard'=>route('home')],'active'=>1])
	<div class="row mx-1">
		<div class="card card-body">
			<div class="row">
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
						<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="mdi mdi-movie" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">Movies</h4>
									<h5 class="">89</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
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
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
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
			<div class="row mt-4">
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
						<a href="/" class="mt-2 text-right mr-2 text-white"><i class="mdi mdi-settings" style="font-size: 25px"></i></a>
						<div class="card-body">
							<div class="text-center">
								<div>
									<i class="mdi mdi-movie" style="font-size: 35px"></i>
								</div>
								<div>
									<h4 class="mt-4">New Movies</h4>
									<h5 class="">8</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
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
				<div class="col-lg-4">
					<div class="card bg-primary text-white" style="box-shadow: 0 2px 6px #fd6e77;">
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
		</div>
	</div>


	<div class="row mx-1 mt-4">
		<div class="col-lg-12 px-0">
			<div class="card m-b-30">
				<div class="card-body p-0 rounded-lg">
					<h3 class="mt-0 header-title card-header">More Views</h3>
					<div class="row mt-3">
						<div class="col-md-3">
							<div class="card card-body border border-danger p-3 ml-3 mb-3" style="box-shadow: 0 2px 10px rgba(253,110,119,0.51);">
								<h6 class="m-0 mb-2">Movies</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-body border border-danger p-3" style="box-shadow: 0 2px 10px rgba(253,110,119,0.51);">
								<h6 class="m-0 mb-2">Series Average</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-body border border-danger p-3" style="box-shadow: 0 2px 10px rgba(253,110,119,0.51);">
								<h6 class="m-0 mb-2">Episodes</h6>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-0">Cras justo odio</li>
									<li class="list-group-item px-0">Dapibus ac facilisis in</li>
								</ul>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-body border border-danger p-3 mr-3 mb-3" style="box-shadow: 0 2px 10px rgba(253,110,119,0.51);">
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