@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Customers','action'=>['link'=>route('admin.customers.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Email</th>
							<th>Status</th>
							<th>Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($users as $user)
							<tr>
								<td>{{$loop->index+1}}</td>
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
					{{ $users->links() }}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		$(document).ready(() => {

		});

		showDetails = key => {
			setLoading(true, () => {
				axios.get(`/admin/customers/${key}`)
					.then(response => {
						setLoading(false);
						bootbox.dialog({
							title: 'Customer Details',
							message: response.data,
							centerVertical: false,
							size: 'medium',
							scrollable: true,
						});
					})
					.catch(error => {
						setLoading(false);
						toastr.error('Something went wrong. Please try again in a while.');
					});
			});
		}

		deleteCustomer = key => {
			alertify.confirm("Are you sure you want to delete this customer? ",
				yes => {
					axios.delete(`/admin/customers/${key}`).then(response => {
						location.reload();
					}).catch(e => {
						toastr.error('Something went wrong. Please retry!');
					});
				}
			)
		}
	</script>
@stop