@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Shop Sliders','action'=>['link'=>route('admin.shop.sliders.create'),'text'=>'Create slider']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Poster</th>
							<th class="text-center">Title</th>
							<th class="text-center">Description</th>
							<th class="text-center">Rating</th>
							<th class="text-center">Active</th>
							<th class="text-center">Target Link</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($slides as $slide)
							<tr id="genre_row_{{$slide->getKey()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($slide->banner!=null)
										<img src="{{\App\Library\Utils\Uploads::access()->url($slide->banner)}}" style="width: 100px; height: 60px" alt="{{$slide->title}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 90px"></i>
									@endif
								</td>
								<td class="text-center">{{$slide->title}}</td>
								<td class="text-center">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->description,50)}}</td>
								<td class="text-center">{{$slide->rating??\App\Library\Utils\Extensions\Str::NotAvailable}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($slide->isActive()==true)
											<label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$slide->getKey()}}" onchange="toggleStatus('{{$slide->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$slide->getKey()}}" onchange="toggleStatus('{{$slide->getKey()}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$slide->getKey()}}" onchange="toggleStatus('{{$slide->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$slide->getKey()}}" onchange="toggleStatus('{{$slide->getKey()}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center">
									<a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig" target="_blank" href="{{$slide->target}}">{{\App\Library\Utils\Extensions\Str::ellipsis($slide->target)}}</a>
								</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.shop.sliders.edit',$slide->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:void(0);" onclick="deleteSlide('{{$slide->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
		 * Returns route for Genre/Update/Status route.
		 * @param id
		 * @returns {string}
		 */
		updateStatusRoute = (id) => {
			return 'sliders/' + id + '/status';
		};

		/**
		 * Returns route for Genre/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteSlideRoute = (id) => {
			return 'sliders/' + id;
		};

		/**
		 * Callback for active status changes.
		 * @param id
		 * @param state
		 */
		toggleStatus = (id, state) => {
			axios.put(updateStatusRoute(id),
				{
					id: id,
					active: state
				})
				.then(response => {
					if (response.data.status === 200) {
						toastr.success(response.data.message);
					} else {
						toastr.error(response.data.message);
					}
				})
				.catch(reason => {
					console.log(reason);
					toastr.error('Failed to update status.');
				});
		};

		/**
		 * Callback for delete slide trigger.
		 * @param genreId
		 */
		deleteSlide = (genreId) => {
			window.genreId = genreId;
			alertify.confirm("Are you sure you want to delete this slide? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteSlideRoute(genreId))
						.then(response => {
							if (response.data.status === 200) {
								$('#genre_row_' + genreId).remove();
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