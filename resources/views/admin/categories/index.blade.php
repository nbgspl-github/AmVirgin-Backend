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
								<tr id="{{'category_row_'.$category->getKey()}}">
									<td>{{$loop->index+1}}</td>
									<td class="text-center">
										@if($category->getPoster()!=null)
											<img src="{{Storage::disk('public')->url($category->getPoster())}}" style="width: 100px; height: 100px" alt="{{$category->getName()}}" @include('admin.extras.tooltip.right', ['title' => $category->getName()])/>
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
												<a class="btn btn-outline-danger shadow-sm shadow-danger" href="{{route('admin.categories.edit',$category->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											</div>
											<div class="col-sm-6 px-0">
												<a class="btn btn-outline-primary shadow-sm shadow-primary" href="javascript:void(0);" onclick="deleteCategory('{{$category->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
	<script type="application/javascript">
		$(document).ready(() => {
			$('#datatable').DataTable();
		});

		/**
		 * Returns route for Update/Status route.
		 * @param id
		 * @returns {string}
		 */
		updateStatusRoute = (id) => {
			return 'categories/' + id + '/status';
		};

		/**
		 * Returns route for Genre/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteRoute = (id) => {
			return 'categories/' + id;
		};

		/**
		 * Callback for active status changes.
		 * @param id
		 * @param state
		 */
		toggleStatus = (id, state) => {
			axios.put(updateStatusRoute(id),
				{id: id, status: state})
				.then(response => {
					if (response.status === 200) {
						toastr.success(response.data.message);
					} else {
						toastr.error(response.data.message);
					}
				})
				.catch(reason => {
					console.log(reason);
					toastr.error('Failed to update status.');
				});
		};

		/**
		 * Callback for delete genre trigger.
		 * @param id
		 */
		deleteCategory = (id) => {
			window.genreId = id;
			alertify.confirm("Are you sure you want to delete this category? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteRoute(id))
						.then(response => {
							if (response.data.status === 200) {
								$('#category_row_' + id).remove();
								toastr.success(response.data.message);
							} else {
								toastr.error(response.data.message);
							}
						})
						.catch(error => {
							toastr.error('Something went wrong. Please try again in a while.');
						});
				},
				(ev) => {
					ev.preventDefault();
				});
		}
	</script>
@stop