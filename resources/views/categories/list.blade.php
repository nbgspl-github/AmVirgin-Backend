@extends('layouts.header')
@section('content')
	<div class="row py-4">
		<div class="col-xl-12">
			<div class="card m-b-30 shadow-sm">
				<div class="card-body">
					<div class="row px-2 mb-3">
						<div class="col-sm-6"><h4 class="mt-0 header-title pt-1">All Categories</h4></div>
						<div class="col-sm-6">
							<button type="button" class="float-right btn btn-outline-primary waves-effect waves-light" onclick="window.location.href='{{route('categories.new')}}'">
								Create Category
							</button>
						</div>
						@include('flash::message')
					</div>
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead>
							<tr>
								<th>No.</th>
								<th>Name</th>
								<th>Parent Category</th>
								<th>Description</th>
								<th>Keywords</th>
								<th>Order</th>
								<th>Visibility</th>
								<th>Action(s)</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($categories as $category)
								<tr>
									<td>{{$loop->index+1}}</td>
									<td>{{$category->getSlug()}}</td>
									<td>{{__blank($category->getParentId())}}</td>
									<td>{{$category->getDescription()}}</td>
									<td>{{$category->getKeywords()}}</td>
									<td>{{$category->getOrder()}}</td>
									<td>{{__visibility($category->getVisibility())}}</td>
									<td><a style="text-decoration: underline" class="text-primary" href="{{route('users.single',$category->getId())}}">View Details</a></td>
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