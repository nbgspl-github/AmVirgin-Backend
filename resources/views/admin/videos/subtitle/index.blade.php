@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Subtitle Sources','onClick'=>['link'=>'handleAdd()','text'=>'Add more']])
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
											<a class="btn btn-outline-primary" href="javascript:deleteSubtitle('{{$subtitle->id}}');" @include('admin.extras.tooltip.bottom', ['title' => 'Delete subtitle track'])><i class="mdi mdi-minus-circle-outline"></i></a>
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
				<form action="{{route('admin.videos.update.subtitle',$video->id)}}" enctype="multipart/form-data" method="post">
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
								<label class="custom-file-label" for="subtitleFile">Choose subtitle file...</label>
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
			$('#subtitleModal').modal('show');
		};

		deleteSubtitle = key => {
			alertify.confirm("Are you sure? This action is irreversible!",
				yes => {
					axios.delete(`/admin/customers/${key}`).then(response => {
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