@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card m-b-30">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="invoice-title">
								<h4 class="font-16"><strong>Order #{{$order->orderNumber}}</strong></h4>
							</div>
							<hr>
							<ul class="timeline d-none" id="timeline">
								<li class="li complete">
									<div class="timestamp">
										<span class="author">Abhi Sharma</span>
										<span class="date">11/15/2014</span>
									</div>
									<div class="status pt-3">
										<span> Shift Created </span>
									</div>
								</li>
							</ul>
							<div class="row">
								<div class="col-6">
									<address>
										<strong>Billed To:</strong><br>
										{{$order->address->name}}<br>
										{{$order->address->address}}<br>
										{{$order->address->locality}}<br>
										{{$order->address->city->name}}
									</address>
								</div>
								<div class="col-6 text-right">
									<address>
										<strong>Shipped To:</strong><br>
										{{$order->billingAddress->name}}<br>
										{{$order->billingAddress->address}}<br>
										{{$order->billingAddress->locality}}<br>
										{{$order->billingAddress->city->name}}
									</address>
								</div>
							</div>
							<div class="row">
								<div class="col-6 m-t-30">
									<address>
										<strong>Payment Method:</strong><br>
										{{\App\Library\Enums\Orders\Payments\Methods::fromValue($order->paymentMode)->description}}
										<br>
										{{$order->transaction!=null?$order->transaction->paymentId:\App\Library\Utils\Extensions\Str::NotAvailable}}
									</address>
								</div>
								<div class="col-6 m-t-30 text-right">
									<address>
										<strong>Order Date:</strong><br>
										{{$order->created_at->format(\App\Library\Utils\Extensions\Time::SIMPLIFIED_FORMAT)}}
										<br><br>
									</address>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="panel panel-default">
								<div class="my-3">
									<strong>Order summary</strong>
								</div>
								<div class="">
									<div class="table-responsive">
										<table class="table">
											<thead>
											<tr>
												<td><strong>Item</strong></td>
												<td class="text-center"><strong>Price</strong></td>
												<td class="text-center"><strong>Quantity</strong>
												</td>
												<td class="text-right"><strong>Totals</strong></td>
											</tr>
											</thead>
											<tbody>
											@foreach($order->items as $item)
												<tr>
													<td>{{$item->product->name}}</td>
													<td class="text-center">₹{{$item->sellingPrice}}</td>
													<td class="text-center">{{$item->quantity}}</td>
													<td class="text-right">₹{{$item->total}}</td>
												</tr>
											@endforeach
											<tr>
												<td class="thick-line"></td>
												<td class="thick-line"></td>
												<td class="thick-line text-center">
													<strong>Subtotal</strong></td>
												<td class="thick-line text-right">₹{{$order->subTotal}}</td>
											</tr>
											<tr>
												<td class="no-line"></td>
												<td class="no-line"></td>
												<td class="no-line text-center">
													<strong>Shipping</strong></td>
												<td class="no-line text-right">₹{{$order->tax}}</td>
											</tr>
											<tr>
												<td class="no-line"></td>
												<td class="no-line"></td>
												<td class="no-line text-center">
													<strong>Total</strong></td>
												<td class="no-line text-right"><h4 class="m-0">₹{{$order->total}}</h4>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@foreach($order->subOrders as $subOrder)
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="invoice-title">
									<h4 class="font-16">
										<strong>Sub Order {{$loop->index+1}} #{{$subOrder->number}}</strong></h4>
								</div>
								<hr>
								<div class="row">
									<div class="col-6">
										<address>
											<strong>Seller:</strong><br>
											{{$subOrder->seller->name}}<br>
											{{$subOrder->seller->businessName}}<br>
										</address>
									</div>
									<div class="col-6 text-right">
										<address>
											<strong>Shipped To:</strong><br>
											{{$order->billingAddress->name}}<br>
											{{$order->billingAddress->address}}<br>
											{{$order->billingAddress->locality}}<br>
											{{$order->billingAddress->city->name}}
										</address>
									</div>
								</div>
								<div class="row">
									<div class="col-6 m-t-30">
										<address>
											<strong>Current Status:</strong><br>
											{{$subOrder->status->description}}
										</address>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="panel panel-default">
									<div class="my-3">
										<strong>Order summary</strong>
									</div>
									<div class="">
										<div class="table-responsive">
											<table class="table">
												<thead>
												<tr>
													<td><strong>Item</strong></td>
													<td class="text-center"><strong>Price</strong></td>
													<td class="text-center"><strong>Quantity</strong>
													</td>
													<td class="text-right"><strong>Totals</strong></td>
												</tr>
												</thead>
												<tbody>
												@foreach($subOrder->items as $item)
													<tr>
														<td>{{$item->product->name}}</td>
														<td class="text-center">₹{{$item->sellingPrice}}</td>
														<td class="text-center">{{$item->quantity}}</td>
														<td class="text-right">₹{{$item->total}}</td>
													</tr>
												@endforeach
												<tr>
													<td class="thick-line"></td>
													<td class="thick-line"></td>
													<td class="thick-line text-center">
														<strong>Subtotal</strong></td>
													<td class="thick-line text-right">₹{{$subOrder->subTotal}}</td>
												</tr>
												<tr>
													<td class="no-line"></td>
													<td class="no-line"></td>
													<td class="no-line text-center">
														<strong>Shipping</strong></td>
													<td class="no-line text-right">₹{{$subOrder->tax}}</td>
												</tr>
												<tr>
													<td class="no-line"></td>
													<td class="no-line"></td>
													<td class="no-line text-center">
														<strong>Total</strong></td>
													<td class="no-line text-right">
														<h4 class="m-0">₹{{$subOrder->total}}</h4>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@stop

@section('javascript')
	<script type="application/javascript">

	</script>
@stop