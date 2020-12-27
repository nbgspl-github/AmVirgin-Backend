@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Orders'])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Number</th>
							<th>Customer</th>
							<th>Seller(s)</th>
							<th>Quantity</th>
							<th>Sub Total</th>
							<th>Total</th>
							<th>Status</th>
							<th>Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($orders as $order)
							<tr id="content_row_{{$order->getKey()}}">
								<td>{{$loop->index+1}}</td>
								<td>{{$order->orderNumber}}</td>
								<td>{{$order->customer->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
								<td>
									@foreach($order->subOrders as $subOrder)
										{{$subOrder->seller->name}},
									@endforeach
								</td>
								<td>{{$order->quantity}}</td>
								<td>{{$order->subTotal}}</td>
								<td>{{$order->total}}</td>
								<td>{{$order->status->description}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="javascript:showDetails('{{$order->id}}')" @include('admin.extras.tooltip.bottom', ['title' => 'View customer details'])><i class="mdi mdi-lightbulb-outline"></i></a>
											<a class="btn btn-outline-danger" href="{{route('admin.customers.edit',$order->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit customer details'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:deleteCustomer('{{$order->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete customer'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>

					{{$orders->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let dataTable = null;

		$(document).ready(() => {

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