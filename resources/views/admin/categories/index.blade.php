@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>trans('admin.categories.index'),'action'=>['link'=>route('admin.categories.create'),'text'=>'Add Category']])
				</div>
				<div class="card-body animatable">
					<div class="table-responsive">
						<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
							<thead>
							<tr>
								<th>No.</th>
								<th>Name</th>
								<th>Description</th>
								<th>Parent</th>
								<th>Type</th>
								<th>Order</th>
								<th>Listing Status</th>
								<th>Products</th>
								<th>Action(s)</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($categories as $category)
								<tr id="{{'category_row_'.$category->getKey()}}">
									<td>{{$loop->index+1}}</td>
									<td class="text-center">{{$category->name()}}</td>
									<td class="text-center">{{\App\Classes\Str::ellipsis($category->description(),50)}}</td>
									<td class="text-center">{{\App\Models\Category::parents($category)}}</td>
									<td class="text-center">{{$category->type()}}</td>
									<td class="text-center">{{$category->order()}}</td>
									<td class="text-center">{{$category->listingStatus()}}</td>
									<td class="text-center">{{$category->productCount()}}</td>
									<td class="text-center">
										<div class="btn-toolbar" role="toolbar">
											<div class="btn-group mx-auto" role="group">
												<a class="btn btn-outline-danger" href="{{route('admin.categories.edit',$category->id())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
												<a class="btn btn-outline-primary" href="javascript:void(0);" onclick="deleteCategory('{{$category->id()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
		let dataTable = null;

		$(document).ready(() => {
			dataTable = $('#datatable').DataTable({
				initComplete: function () {
					$('#datatable_wrapper').addClass('px-0 mx-0');
				}
			});
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