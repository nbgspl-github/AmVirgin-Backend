@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Sellers','action'=>['link'=>route('admin.sellers.create'),'text'=>'Add Seller']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
						@foreach ($sellers as $seller)
							<tr>
								<td>{{$loop->index+1}}</td>
								<td>{{$seller->getName()}}</td>
								<td>{{$seller->getMobile()}}</td>
								<td>{{$seller->getEmail()}}</td>
								<td>{{__status($seller->isActive())}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.sellers.edit',$seller->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit seller details'])><i class="mdi mdi-pencil"></i></a>
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
		});
	</script>
@stop