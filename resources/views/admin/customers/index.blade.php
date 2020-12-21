@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Customers','action'=>['link'=>route('admin.customers.create'),'text'=>'Add Customer']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Mobile</th>
							<th class="text-center">Email</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($users as $user)
							<tr>
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($user->name,25)}}</td>
								<td class="text-center">{{$user->mobile}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($user->email,50)}}</td>
								<td class="text-center">{{$user->active?'Active':'Inactive'}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="javascript:showDetails('{{$user->getKey()}}')" @include('admin.extras.tooltip.bottom', ['title' => 'View customer details'])><i class="mdi mdi-lightbulb-outline"></i></a>
											<a class="btn btn-outline-danger" href="{{route('admin.customers.edit',$user->id())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit customer details'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="{{route('admin.customers.delete',$user->id())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit customer details'])><i class="mdi mdi-pencil"></i></a>
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

		showDetails = key => {
			axios.get(`/admin/customers/${key}`)
				.then(response => {
					console.log(response.data.status);
				})
				.catch(error => {
					toastr.error('Something went wrong. Please try again in a while.');
				});
		}
	</script>
@stop