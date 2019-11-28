@extends('admin.layouts.header')
@section('content')
	<div class="row py-4">
		<div class="col-xl-12">
			<div class="card m-b-30 shadow-sm">
				<div class="card-body">
					<div class="row px-2 mb-3">
						<div class="col-sm-6"><h4 class="mt-0 header-title pt-1">All Users</h4></div>
						<div class="col-sm-6">
							<a class="float-right btn btn-outline-primary waves-effect waves-light" href="{{route('users.new')}}">
								Add User
							</a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead>
							<tr>
								<th>No.</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Status</th>
								<th>Action(s)</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($users as $user)
								<tr>
									<td>{{$loop->index+1}}</td>
									<td>{{$user->getName()}}</td>
									<td>{{$user->getMobile()}}</td>
									<td>{{$user->getEmail()}}</td>
									<td>{{__status($user->getStatus())}}</td>
									<td><a style="text-decoration: underline" class="text-primary" href="{{route('users.single',$user->getId())}}">View Details</a></td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop