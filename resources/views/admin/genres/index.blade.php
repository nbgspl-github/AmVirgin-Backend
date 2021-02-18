@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Genres','action'=>['link'=>route('admin.genres.create'),'text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Poster</th>
							<th>Name</th>
							<th>Active</th>
							<th>Action(s)</th>
						</tr>
						</thead>

						<tbody>
						@foreach($genres as $genre)
							<tr id="genre_row_{{$genre->getKey()}}">
								<td>{{$loop->index+1}}</td>
								<td>
									@if($genre->poster!=null)
										<img src="{{$genre->poster}}" style="max-height: 100px" alt="{{$genre->name}}" class="img-thumbnail" @include('admin.extras.tooltip.right', ['title' => $genre->name])/>
									@else
										<i class="mdi mdi-close-box-outline text-muted shadow-sm" style="font-size: 25px"></i>
									@endif
								</td>
								<td>{{$genre->name}}</td>
								<td>
									<div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
										@if($genre->active==true)
											<label class="btn btn-outline-danger active" @include('admin.extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getKey()}}" onchange="_status('{{$genre->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary" @include('admin.extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getKey()}}" onchange="_status('{{$genre->getKey()}}',0);"/> Off
											</label>
										@else
											<label class="btn btn-outline-danger" @include('admin.extras.tooltip.left', ['title' => 'Set genre active'])>
												<input type="radio" name="options" id="optionOn_{{$genre->getKey()}}" onchange="_status('{{$genre->getKey()}}',1);"/> On
											</label>
											<label class="btn btn-outline-primary active" @include('admin.extras.tooltip.right', ['title' => 'Set genre inactive'])>
												<input type="radio" name="options" id="optionOff_{{$genre->getKey()}}" onchange="_status('{{$genre->getKey()}}',0);"/> Off
											</label>
										@endif
									</div>
								</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group mx-auto" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.genres.edit',$genre->getKey())}}" @include('admin.extras.tooltip.bottom', ['title' => 'Edit'])><i class="mdi mdi-pencil"></i></a>
											<a class="btn btn-outline-primary" href="javascript:_delete('{{$genre->getKey()}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete'])><i class="mdi mdi-delete"></i></a>
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

		_status = (key, state) => {
			axios.put(`/admin/genres/${key}/status`, {active: state})
				.then(response => {
					alertify.alert(response.data.message, () => {
						location.reload();
					});
				})
				.catch(e => {
					alertify.confirm('Something went wrong. Retry?', yes => {
						_status(key);
					});
				});
		}

		_delete = (key) => {
			alertify.confirm("This action is irreversible. Proceed?",
				(yes) => {
					axios.delete(`/admin/genres/${key}}`)
						.then(response => {
							alertify.alert(response.data.message, () => {
								location.reload();
							});
						}).catch(e => {
						alertify.confirm('Something went wrong. Retry?', yes => {
							_delete(key);
						});
					});
				}
			);
		}
	</script>
@stop