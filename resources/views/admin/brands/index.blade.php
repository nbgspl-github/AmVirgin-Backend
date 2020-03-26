@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Brands','action'=>['link'=>route('admin.brands.create'),'text'=>'Add a Brand']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Name</th>
							<th class="text-center">Logo</th>
							<th class="text-center">Active</th>
							<th class="text-center">Actions</th>
						</tr>
						</thead>

						<tbody>
						@foreach($brands as $brand)
							<tr>
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">{{$brand->name()}}</td>
								<td class="text-center">
									@if(\App\Storage\SecuredDisk::access()->exists($brand->logo()))
										<img src="{{\App\Storage\SecuredDisk::access()->url($brand->logo())}}" style="width: 140px; height: 100px; filter: drop-shadow(2px 2px 8px black)" alt="{{$brand->logo()}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="text-center">{{__boolean($brand->active())}}</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.brands.edit',$brand->id())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
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