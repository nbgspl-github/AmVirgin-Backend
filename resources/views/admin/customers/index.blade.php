@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['breadcrumbs' =>['Dashboard'=>route('admin.home'),'Customers'=>'#'],'title'=>trans('admin.customers.index')])
				</div>
				<div class="card-body animatable">
					<div class="row px-2 mb-3">
						<div class="col-sm-6"><h4 class="mt-0 header-title pt-1">All Customers</h4></div>
						<div class="col-sm-6">
							<a class="float-right btn btn-outline-primary waves-effect waves-light" href="{{route('admin.customers.create')}}">
								Add Customer
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
									<td>{{__status($user->isActive())}}</td>
									<td><a style="text-decoration: underline" class="text-primary" href="{{route('admin.customers.edit',$user->getKey())}}">View Details</a></td>
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