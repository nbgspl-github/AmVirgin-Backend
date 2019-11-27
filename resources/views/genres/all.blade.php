@extends('layouts.header')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('layouts.pageHeader', ['breadcrumbs' =>['Dashboard'=>route('home'),'Genres'=>'#'],'title'=>'Genres'])
				</div>
				<div class="card-body animatable">
					<div class="row pr-3">
						<div class="col-6"><h4 class="mt-0 header-title ml-3 mb-4">All Genres</h4></div>
						<div class="col-6"><a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="{{route('genres.create')}}">Add Genre</a></div>
					</div>
					<table id="datatable" class="table table-bordered dt-responsive pr-0 pl-0" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
							<tr id="genre_row_{{$genre->getId()}}">
								<td class="text-center">{{$loop->index+1}}</td>
								<td class="text-center">
									@if($genre->getPoster()!=null)
										<img src="{{route('images.genre.poster',$genre->getId())}}" style="width: 100px; height: 100px" alt="{{$genre->getName()}}" @include('extras.tooltip.right', ['title' => $genre->getName()])/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 90px"></i>
									@endif
								</td>
								<td class="text-center">{{$genre->getName()}}</td>
								<td class="text-center">{{$genre->getDescription()}}</td>
								<td class="text-center">
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($genre->getStatus()==true)
											<label class="btn btn-outline-danger active" @include('extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getId()}}" onchange="toggleStatus('{{$genre->getId()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getId()}}" onchange="toggleStatus('{{$genre->getId()}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getId()}}" onchange="toggleStatus('{{$genre->getId()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getId()}}" onchange="toggleStatus('{{$genre->getId()}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td class="text-center">
									<div class="btn-group">
										<div class="col-sm-6 px-0">
											<a class="btn btn-outline-danger shadow-sm shadow-danger" href="{{route('genres.edit',$genre->getId())}}" @include('extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
										</div>
										<div class="col-sm-6 px-0">
											<a class="btn btn-outline-primary shadow-sm shadow-primary" href="javascript:void(0);" onclick="deleteGenre('{{$genre->getId()}}');" @include('extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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