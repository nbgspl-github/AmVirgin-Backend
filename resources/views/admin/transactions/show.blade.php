@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card m-b-30">
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="invoice-title">
								<h4 class="font-16"><strong>Transaction #{{$transaction->reference_id}}</strong></h4>
							</div>
							<hr>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="panel panel-default">
								<div class="my-3">
									<strong>Orders</strong>
								</div>
								<div class="">
									<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
										<tr>
											<th>#</th>
											<th>Number</th>
											<th>SubTotal</th>
											<th>Total</th>
											<th>Status</th>
											<th>Action(s)</th>
										</tr>
										</thead>
										<tbody>
										@foreach ($users as $user)
											<tr>
												<td>{{($users->firstItem()+$loop->index)}}</td>
												<td>{{\App\Library\Utils\Extensions\Str::ellipsis($user->name,25)}}</td>
												<td>{{$user->mobile}}</td>
												<td>{{\App\Library\Utils\Extensions\Str::ellipsis($user->email,50)}}</td>
												<td>{{$user->active?'Active':'Inactive'}}</td>
												<td>
													<div class="btn-toolbar" role="toolbar">
														<div class="btn-group" role="group">
															<a class="btn btn-outline-danger" href="javascript:showDetails('{{$user->id}}')" @include('admin.extras.tooltip.bottom', ['title' => 'View customer details'])><i class="mdi mdi-lightbulb-outline"></i></a>
															<a class="btn btn-outline-danger" href="{{route('admin.customers.edit',$user->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit customer details'])><i class="mdi mdi-pencil"></i></a>
															<a class="btn btn-outline-primary" href="javascript:deleteCustomer('{{$user->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete customer'])><i class="mdi mdi-minus-circle-outline"></i></a>
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
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">

	</script>
@stop