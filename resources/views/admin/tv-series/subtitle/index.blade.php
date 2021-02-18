@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Subtitle Sources','onClick'=>['link'=>'handleAdd()','text'=>'Add']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Language</th>
							<th>Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($subtitles as $subtitle)
							<tr>
								<td>{{($subtitles->firstItem()+$loop->index)}}</td>
								<td>{{$subtitle->language->name}}</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-primary" href="javascript:deleteEpisode('{{$subtitle->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete subtitle'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{ $subtitles->links() }}
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="subtitleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Create new subtitle source</h5>
				</div>
				<form action="" enctype="multipart/form-data" method="post" id="subtitleForm">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label for="video_language_id">Language</label>
							<select name="video_language_id" id="video_language_id" class="form-control selectpicker">
								@foreach($languages as $language)
									<option value="{{$language->id}}">{{$language->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group mb-0">
							<label>Subtitle</label>
							<div class="custom-file">
								<input name="file" type="file" class="custom-file-input" id="subtitleFile" accept=".srt" required>
								<label class="custom-file-label" for="audioFile">No file chosen</label>
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
		let source_id = `{{$source->id}}`;
		let video_id = `{{$video->id}}`;
		let progress = 0;
		$(document).on('change', '.custom-file-input', function (event) {
			$(this).next('.custom-file-label').html(event.target.files[0].name);
		})

		$(document).ready(() => {
			$('#subtitleForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
		});

		handleAdd = () => {
			$('#subtitleModal').modal('show');
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
				$('#subtitleModal').modal('hide');
				axios.post(`/admin/tv-series/${video_id}/update/${source_id}/subtitle`, formData, config,).then(response => {
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