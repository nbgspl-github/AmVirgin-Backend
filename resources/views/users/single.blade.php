@extends('layouts.header')
@section('content')
	<div class="row py-4">
		<div class="col-xl-12">
			<div class="card m-b-30 shadow-sm">
				<div class="card-body">
					<div class="row px-1 mb-3">
						<div class="col-sm-6"><h4 class="mt-0 header-title pt-1">User Details</h4></div>
						<div class="col-sm-6">

						</div>
					</div>
					<p class="text-muted m-b-30 font-14"></p>
					<form action="#">
						<div class="form-group"><label>Name</label> <input type="text" class="form-control" value="{{$user->getName()}}"></div>
						<div class="form-group"><label>Email</label> <input type="text" class="form-control" value="{{$user->getEmail()}}"></div>
						<div class="form-group"><label>Mobile</label> <input type="text" class="form-control" value="{{$user->getMobile()}}"></div>
						<div class="form-group"><input type="button" class="btn btn-primary form-control" value="Submit"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop