@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Video Source(s)','onClick'=>['link'=>'handleAdd()','text'=>'Choose']])
				</div>
				<form id="uploadForm" action="{{route('admin.videos.update.attributes',$video->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										Editing video sources for - {{$video->title}}
									</div>
									<div class="card-body">
										<div class="form-row" id="container">
											<div class="embed-responsive embed-responsive-16by9">
												@if($video->sources->first()!=null)
													<iframe class="embed-responsive-item" src="{{$video->sources->first()->file}}" allowfullscreen></iframe>
												@else
													<p class="text-center">No video available</p>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="row">
									<div class="col-6 pr-1">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-primary">
											Update
										</button>
									</div>
									<div class="col-6 pl-1">
										<a href="{{route("admin.videos.edit.action",$video->getKey())}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
											Cancel
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Create new video source</h5>
				</div>
				<form action="{{route('admin.videos.update.source',$video->id)}}" enctype="multipart/form-data" method="post" id="videoForm">
					@csrf
					<div class="modal-body">
						<div class="form-group mb-0">
							<label>Video</label>
							<div class="custom-file">
								<input name="file" type="file" class="custom-file-input" id="videoFile" accept=".mp4" required>
								<label class="custom-file-label" for="videoFile">Choose video file...</label>
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
@endsection

@section('javascript')
	<script>
		let video_id = `{{$video->id}}`;
		$(document).on('change', '.custom-file-input', function (event) {
			$(this).next('.custom-file-label').html(event.target.files[0].name);
		})
		$(document).ready(() => {
			$('#videoForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
		});

		handleAdd = () => {
			$('#videoModal').modal('show');
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
				$('#videoModal').modal('hide');
				axios.post(`/admin/videos/${video_id}/update/source`, formData, config,).then(response => {
					showProgressDialog(false);
					alertify.alert(response.data.message, () => {
						location.reload();
					});
				}).catch(error => {
					showProgressDialog(false);
					alertify.alert('Something went wrong. Please try again.');
				});
			});
		}

		uploadProgress = (event) => {
			let percentCompleted = Math.round((event.loaded * 100) / event.total);
			setProgress(percentCompleted);
		}

	</script>
@stop