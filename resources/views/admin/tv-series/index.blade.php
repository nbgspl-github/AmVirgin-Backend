@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'TV Series','action'=>['link'=>route('admin.tv-series.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Poster</th>
							<th>Title</th>
							<th>Description</th>
							<th>Rating</th>
							<th>Seasons</th>
							<th>Episodes</th>
							<th>Pending</th>
							<th>Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($series as $s)
							<tr id="content_row_{{$s->getKey()}}">
								<td>{{$loop->index+1}}</td>
								<td>
									@if($s->poster!=null)
										<img src="{{$s->poster}}" style="width: 100px; height: 150px; filter: drop-shadow(2px 2px 8px black)" alt="{{$s->title}}"/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td>{{$s->title}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::ellipsis($s->description,100)}}</td>
								<td>{{$s->rating}}</td>
								<td>{{$s->seasons}}</td>
								<td>{{$s->sources->count()}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::stringifyBoolean($s->pending)}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger shadow-sm" href="{{route('admin.tv-series.edit.action',$s->getKey())}}" @include('admin.extras.tooltip.left', ['title' => 'Edit this item'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary shadow-sm" href="javascript:void(0);" onclick="deleteMovie('{{$s->getKey()}}');" @include('admin.extras.tooltip.right', ['title' => 'Delete this series'])><i class="mdi mdi-delete"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{$series->links()}}
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let dataTable = null;

		$(document).ready(() => {
			// dataTable = $('#datatable').DataTable({
			// 	initComplete: function () {
			// 		$('#datatable_wrapper').addClass('px-0 mx-0');
			// 	}
			// });
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
		deleteMovieRoute = (id) => {
			return 'tv-series/' + id;
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
		 * @param id
		 */
		deleteMovie = (id) => {
			window.genreId = id;
			alertify.confirm("Are you sure you want to delete this tv series? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteMovieRoute(id))
						.then(response => {
							if (response.status === 200) {
								$('#content_row_' + id).remove();
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