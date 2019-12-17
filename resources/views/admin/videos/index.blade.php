@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Videos'])
				</div>
				<div class="card-body animatable">
					<div class="row pr-3">
						<div class="col-6"><h4 class="mt-0 header-title ml-3 mb-4">All Sliders</h4></div>
						<div class="col-6"><a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="{{route('admin.videos.create')}}">Add a video</a></div>
					</div>
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
						@foreach($movies as $movie)
							<tr id="genre_row_{{$movie->getKey()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($movie->getPoster()!=null)
										<img src="{{route('images.slider.poster',$movie->getKey())}}" style="width: 100px; height: 100px" alt="{{$movie->getTitle()}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 90px"></i>
									@endif
								</td>
								<td class="text-center">{{$movie->getTitle()}}</td>
								<td class="text-center">{{$movie->getDescription()}}</td>
								<td class="text-center">{{__rating($movie->getStars())}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($movie->isActive()==true)
											<label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$movie->getKey()}}" onchange="toggleStatus('{{$movie->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$movie->getKey()}}" onchange="toggleStatus('{{$movie->getKey()}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set slider active'])>
												<input type="radio" name="options" id="optionOn_{{$movie->getKey()}}" onchange="toggleStatus('{{$movie->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set slider inactive'])>
												<input type="radio" name="options" id="optionOff_{{$movie->getKey()}}" onchange="toggleStatus('{{$movie->getKey()}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center"><a class="btn btn-outline-secondary waves-effect waves-light shadow-sm fadeInRightBig" target="_blank" href="{{$movie->getTarget()}}">{{__ellipsis($movie->getTarget())}}</a></td>
								<td class="text-center">
									<div class="btn-group">
										<div class="col-sm-6 px-0">
											<a class="btn btn-outline-danger shadow-sm shadow-danger" href="{{route('admin.sliders.edit',$movie->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
										</div>
										<div class="col-sm-6 px-0">
											<a class="btn btn-outline-primary shadow-sm shadow-primary" href="javascript:void(0);" onclick="deleteSlide('{{$movie->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
		$(document).ready(() => {
			$('#datatable').DataTable();
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
				{id: id, active: state})
				.then(response => {
					if (response.status === 200) {
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
							if (response.status === 200) {
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