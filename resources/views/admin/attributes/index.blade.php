@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Attributes','action'=>['link'=>route('admin.products.attributes.create'),'text'=>'Create an attribute']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Name</th>
							<th class="text-center">Code</th>
							<th class="text-center">Category</th>
							<th class="text-center">Seller Interface Type</th>
							<th class="text-center">Customer Interface Type</th>
							<th class="text-center">Required</th>
							<th class="text-center">Filterable</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>

						<tbody>
						@foreach($attributes as $attribute)
							<tr>
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">{{$attribute->name()}}</td>
								<td class="text-center">{{$attribute->code()}}</td>
								<td class="text-center">{{$attribute->category->name}}</td>
								<td class="text-center">{{$attribute->sellerInterfaceType}}</td>
								<td class="text-center">{{$attribute->customerInterfaceType}}</td>
								<td class="text-center">{{__boolean($attribute->required)}}</td>
								<td class="text-center">{{__boolean($attribute->filterable)}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="" @include('admin.extras.tooltip.left', ['title' => 'Edit attribute details'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.products.attributes.values.edit',$attribute->id())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit attribute values'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);" onclick="deleteMovie('');" @include('admin.extras.tooltip.right', ['title' => 'Delete this attribute'])><i class="mdi mdi-delete"></i></a>
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
			notyf.error('You must fill out the form before moving forward');
		});

		/**
		 * Returns route for Resource/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteRoute = (id) => {
			return 'subscription-plans/' + id;
		};

		/**
		 * Callback for delete resource trigger.
		 * @param id
		 */
		deleteResource = (id) => {
			window.genreId = id;
			alertify.confirm("Are you sure you want to delete this subscription plan? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteRoute(id))
						.then(response => {
							if (response.status === 200) {
								dataTable.rows('#content_row_' + id).remove().draw();
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