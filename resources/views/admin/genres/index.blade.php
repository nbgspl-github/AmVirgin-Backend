@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Genres','action'=>['link'=>route('admin.genres.create'),'text'=>'Add Genre']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">Poster</th>
							<th class="text-center">Name</th>
							<th class="text-center">Description</th>
							<th class="text-center">Active</th>
							<th class="text-center">Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($genres as $genre)
							<tr id="genre_row_{{$genre->getKey()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($genre->getPoster()!=null)
										<img src="{{route('images.genre.poster',$genre->getKey())}}" style="width: 100px; height: 100px" alt="{{$genre->getName()}}" @include('admin.extras.tooltip.right', ['title' => $genre->getName()])/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td class="text-center">{{$genre->getName()}}</td>
								<td class="text-center">{{$genre->getDescription()}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($genre->getStatus()==true)
											<label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getKey()}}" onchange="toggleStatus('{{$genre->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getKey()}}" onchange="toggleStatus('{{$genre->getKey()}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getKey()}}" onchange="toggleStatus('{{$genre->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getKey()}}" onchange="toggleStatus('{{$genre->getKey()}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center">
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.genres.edit',$genre->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:void(0);" onclick="deleteGenre('{{$genre->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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
			return 'genres/' + id + '/status';
		};

		/**
		 * Returns route for Genre/Delete route.
		 * @param id
		 * @returns {string}
		 */
		deleteGenreRoute = (id) => {
			return 'genres/' + id;
		};

		/**
		 * Callback for active status changes.
		 * @param id
		 * @param state
		 */
		toggleStatus = (id, state) => {
			axios.put(updateStatusRoute(id),
				{id: id, status: state})
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
		 * Callback for delete genre trigger.
		 * @param genreId
		 */
		deleteGenre = (genreId) => {
			window.genreId = genreId;
			alertify.confirm("Are you sure you want to delete this genre? ",
				(ev) => {
					ev.preventDefault();
					axios.delete(deleteGenreRoute(genreId))
						.then(response => {
							if (response.data.code === 200) {
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