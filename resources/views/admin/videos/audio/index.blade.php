@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Customers','action'=>null])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Language</th>
							<th>Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($audios as $audio)
							<tr>
								<td>{{($users->firstItem()+$loop->index)}}</td>
								<td>{{$audio->language->name}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="javascript:showDetails('{{$audio->id}}')" @include('admin.extras.tooltip.bottom', ['title' => 'View customer details'])><i class="mdi mdi-lightbulb-outline"></i></a>
											<a class="btn btn-outline-danger" href="{{route('admin.customers.edit',$audio->id)}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit customer details'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:deleteCustomer('{{$audio->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete customer'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{ $audios->links() }}
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
							title: 'Details',
							message: response.data,
							centerVertical: false,
							size: 'small',
							scrollable: true,
						});
					})
					.catch(error => {
						setLoading(false);
						alertify.confirm('Something went wrong. Retry?', yes => {
							showDetails(key);
						});
					});
			});
		}

		deleteCustomer = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/customers/${key}`).then(response => {
						location.reload();
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							showDetails(key);
						});
					});
				}
			)
		}
	</script>
@stop