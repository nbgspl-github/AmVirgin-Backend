@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Subscription Plans','action'=>['link'=>route('admin.subscription-plans.create'),'text'=>'Add plan']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Banner</th>
							<th class="text-center">Name</th>
							<th class="text-center">Description</th>
							<th class="text-center">Original Price</th>
							<th class="text-center">Offer Price</th>
							<th class="text-center">Duration</th>
							<th class="text-center">Active</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($plans as $plan)
							<tr id="content_row_{{$plan->getKey()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($plan->getBanner()!=null&&Storage::disk('secured')->exists($plan->getBanner()))
										<img src="{{Storage::disk('secured')->url($plan->getBanner())}}" style="width: 100px; height: 100px" alt="{{$plan->getName()}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="text-center">{{$plan->getName()}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($plan->getDescription(),40)}}</td>
								<td class="text-center">{{$plan->getOriginalPrice()}}</td>
								<td class="text-center">{{$plan->getOfferPrice()}}</td>
								<td class="text-center">{{$plan->getDuration()}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::stringifyBoolean($plan->isActive())}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.subscription-plans.edit',$plan->getKey())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit details'])><i class="mdi mdi-table-edit"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);" onclick="deleteResource('{{$plan->getKey()}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete this plan'])><i class="mdi mdi-delete"></i></a>
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