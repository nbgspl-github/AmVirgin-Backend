@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Audio Sources','onClick'=>['link'=>'handleAdd()','text'=>'Add more']])
				</div>
				<div class="card-body animatable">
					<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
						<thead>
						<tr>
							<th>#</th>
							<th>Language</th>
							<th>File</th>
							<th>Action(s)</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($audios as $audio)
							<tr>
								<td>{{($audios->firstItem()+$loop->index)}}</td>
								<td>{{$audio->language->name}}</td>
								<td>
									<audio controls src="{{$audio->file}}"></audio>
								</td>
								<td>
									<div class="btn-toolbar" role="toolbar">
										<div class="btn-group" role="group">
											<a class="btn btn-outline-primary" href="javascript:deleteAudio('{{$audio->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete audio track'])><i class="mdi mdi-minus-circle-outline"></i></a>
										</div>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{ $audios->links() }}
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="audioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Create new audio source</h5>
				</div>
				<form action="{{route('admin.videos.update.audio',$video->id)}}" enctype="multipart/form-data" method="post">
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
							<label>Audio</label>
							<div class="custom-file">
								<input name="file" type="file" class="custom-file-input" id="audioFile" accept=".mp3, .aac" required>
								<label class="custom-file-label" for="audioFile">Choose audio file...</label>
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
		$(document).ready(() => {

		});

		handleAdd = () => {
			$('#audioModal').modal('show');
		};

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