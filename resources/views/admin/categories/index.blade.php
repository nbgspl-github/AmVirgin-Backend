@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>trans('admin.categories.index')])
				</div>
				<div class="card-body animatable">
					<div class="row px-2 mb-3">
						<div class="col-sm-6"><h4 class="mt-0 header-title pt-1">All Categories</h4></div>
						<div class="col-sm-6">
							<a class="float-right btn btn-outline-primary waves-effect waves-light" href="{{route('admin.categories.create')}}">
								Create Category
							</a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead>
							<tr>
								<th>No.</th>
								<th>Poster</th>
								<th>Name</th>
								<th>Parent Category</th>
								<th>Description</th>
								<th>Visibility</th>
								<th>Action(s)</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($categories as $category)
								<tr>
									<td>{{$loop->index+1}}</td>
									<td class="text-center">
										@if($category->getPoster()!=null)
											<img src="{{route('images.category.poster',$category->getId())}}" style="width: 100px; height: 100px" alt="{{$category->getName()}}" @include('admin.extras.tooltip.right', ['title' => $category->getName()])/>
										@else
											<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 90px"></i>
										@endif
									</td>
									<td>{{$category->getName()}}</td>
									<td>{{__blank($category->getParentName())}}</td>
									<td>{{$category->getDescription()}}</td>
									<td>{{__visibility($category->isVisible())}}</td>
									<td class="text-center">
										<div class="btn-group">
											<div class="col-sm-6 px-0">
												<a class="btn btn-outline-danger shadow-sm shadow-danger" href="{{route('admin.categories.edit',$category->getId())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											</div>
											<div class="col-sm-6 px-0">
												<a class="btn btn-outline-primary shadow-sm shadow-primary" href="javascript:void(0);" onclick="deleteCategory('{{$category->getId()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
											</div>
										</div>
									</td>
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

@section('javascript')

@stop