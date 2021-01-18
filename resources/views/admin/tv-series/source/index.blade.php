@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Tv Series Episodes','onClick'=>['link'=>'handleAdd()','text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Audio</th>
							<th>Subtitle</th>
							<th>Duration</th>
							<th>Season</th>
							<th>Episode</th>
							<th>Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($sources as $source)
							<tr>
								<td>{{($sources->firstItem()+$loop->index)}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::ellipsis($source->title)}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::join(', ',$source->audioLanguages->pluck('name')->toArray())}}</td>
								<td>{{\App\Library\Utils\Extensions\Str::join(', ',$source->subtitleLanguages->pluck('name')->toArray())}}</td>
								<td>{{$source->duration}}</td>
								<td>{{$source->season}}</td>
								<td>{{$source->episode}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-danger" href="{{route('admin.tv-series.edit.audio',[$video->id,$source->id])}}" @include('admin.extras.tooltip.bottom', ['title' => 'Audio sources'])><i class="mdi mdi-file-music"></i></a>
											<a class="btn btn-outline-danger" href="{{route('admin.tv-series.edit.subtitle',[$video->id,$source->id])}}" @include('admin.extras.tooltip.bottom', ['title' => 'Subtitle sources'])><i class="mdi mdi-file-document"></i></a>
											<a class="btn btn-outline-primary" href="javascript:deleteEpisode('{{$source->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete episode'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{ $sources->links() }}
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="episodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Create new episode</h5>
				</div>
				<form action="{{route('admin.tv-series.update.source',$video->id)}}" enctype="multipart/form-data" method="post" id="sourceForm">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label for="sourceTitle">Title</label>
							<input type="text" name="title" class="form-control" id="sourceTitle" minlength="2" maxlength="255" required>
						</div>
						<div class="form-group">
							<label for="sourceDescription">Description</label>
							<textarea class="form-control" name="description" id="sourceDescription" minlength="2" maxlength="500" required></textarea>
						</div>
						<div class="form-group">
							<label for="sourceSeason">Season</label>
							<select name="season" id="sourceSeason" class="form-control selectpicker" required>
								@for($i=1;$i<=10;$i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
						<div class="form-group">
							<label for="sourceEpisode">Episode</label>
							<select name="episode" id="sourceEpisode" class="form-control selectpicker" required>
								@for($i=1;$i<=50;$i++)
									<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</div>
						<div class="form-group mb-0">
							<label>Video</label>
							<div class="custom-file">
								<input name="file" type="file" class="custom-file-input" id="videoFile" accept=".mp4" required>
								<label class="custom-file-label" for="videoFile">No file chosen</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let source_id = null;
		let video_id = `{{$video->id}}`;
		let progress = 0;
		$(document).on('change', '.custom-file-input', function (event) {
			$(this).next('.custom-file-label').html(event.target.files[0].name);
		})

		$(document).ready(() => {
			$('#sourceForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
		});

		handleAdd = () => {
			$('#episodeModal').modal('show');
		};

		submitSource = (event) => {
			const config = {
				onUploadProgress: uploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(event);
			showProgressDialog(true, () => {
				$('#episodeModal').modal('hide');
				axios.post(`/admin/tv-series/${video_id}/update/source`, formData, config,).then(response => {
					showProgressDialog(false);
					alertify.alert(response.data.message);
				}).catch(error => {
					showProgressDialog(false);
					alertify.alert('Something went wrong. Please try again.');
				});
			});
		}

		uploadProgress = (event) => {
			let percentCompleted = Math.round((event.loaded * 100) / event.total);
			let percentCompletedValue = (percentCompleted / 100.0);
			setProgress(percentCompleted);
		}

		handleAddAudio = sourceId => {
			source_id = sourceId;
			$('#audioModal').modal('show');
		}

		handleAddSubtitle = sourceId => {
			source_id = sourceId;
			$('#subtitleModal').modal('show');
		}

		deleteAudio = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/videos/${key}`).then(response => {
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