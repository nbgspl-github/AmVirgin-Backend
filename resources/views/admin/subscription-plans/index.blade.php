@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Subscription Plans','action'=>['link'=>route('admin.subscription-plans.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Banner</th>
							<th>Name</th>
                            <th>Description</th>
                            <th>Original Price</th>
                            <th>Offer Price</th>
                            <th>Duration</th>
                            <th>Active</th>
                            <th>Action(s)</th>
                        </tr>
                        </thead>

                        <tbody>
                        <x-blank-table-indicator :data="$plans"/>
                        @foreach($plans as $plan)
							<tr id="content_row_{{$plan->getKey()}}">
								<td>{{$loop->index+1}}</td>
								<td>
									@if($plan->banner!=null)
										<img src="{{$plan->banner}}" style="max-height: 75px;" class="img-thumbnail" alt="{{$plan->name}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td>{{$plan->name}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::ellipsis($plan->description,255)}}</td>
								<td>{{$plan->originalPrice}}</td>
								<td>{{$plan->offerPrice}}</td>
								<td>{{$plan->duration}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::stringifyBoolean($plan->active)}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.subscription-plans.edit',$plan->getKey())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit details'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:_delete('{{$plan->getKey()}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete this plan'])><i class="mdi mdi-delete"></i></a>
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
		function _delete(key) {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/subscription-plans/${key}`).then(response => {
						alertify.alert(response.data.message, () => {
							location.reload();
						});
					}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							_delete(key);
						});
					});
				}
			)
		}
	</script>
@stop