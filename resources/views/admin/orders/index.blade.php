@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					<div class="row">
						<div class="col-8">
							<h5 class="page-title animatable">Orders</h5>
						</div>
						<div class="col-4 my-auto">
							<form action="{{route('admin.orders.index')}}">
								<div class="form-row float-right">
									<div class="col-auto my-1">
										<input type="text" name="query" class="form-control" id="inlineFormCustomSelect" value="{{request('query')}}" placeholder="Type order number">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Number</th>
							<th>Sub-Orders</th>
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
								<td>{{($orders->firstItem()+$loop->index)}}</td>
								<td>{{$order->orderNumber}}</td>
								<td>{{$order->subOrders->count()}}</td>
								<td>{{$order->customer->name??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
								<td>
									@foreach($order->subOrders as $subOrder)
										<span class="badge badge-default">{{$subOrder->seller->name}}</span>
									@endforeach
								</td>
								<td>{{$order->quantity}}</td>
								<td>{{$order->subTotal}}</td>
								<td>{{$order->total}}</td>
								<td>{{$order->status->description}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.orders.show',$order->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'View order details'])><i class="mdi mdi-lightbulb-outline"></i></a>
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

	</script>
@stop