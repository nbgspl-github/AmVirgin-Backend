@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
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
											@if($video->sources->first()!=null)
												<video src="{{$video->sources->first()->file}}" id="video" controls crossorigin playsinline></video>
											@else
												<p class="text-center">No video available</p>
											@endif
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
							{{--							<div class="custom-file">--}}
							{{--								<input name="file" type="file" class="custom-file-input" id="videoFile" accept=".mp4, .mkv" required>--}}
							{{--								<label class="custom-file-label" for="videoFile">Choose video file...</label>--}}
							{{--							</div>--}}
							<span id="videoFile" class="btn btn-primary">Browse</span>
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
	<script src="{{asset("assets/admin/js/Resumable.js")}}"></script>
	<script>
		const url = `{{route('admin.videos.update.source.chunk',$video->id)}}`;
		const token = `{{\App\Library\Utils\Extensions\Str::random(24)}}`;
		let resumable = null;
		handleAdd = () => {
			$('#videoModal').modal('show');
		};

		@if($video->sources->first()!=null)
		document.addEventListener('DOMContentLoaded', () => {
			const source = '{{$video->sources->first()->file}}';
			const video = document.querySelector('video');
			var playerOptions = {
				settings: ['quality', 'loop'],
			};
			var player = null;
			if (!Hls.isSupported()) {
				video.src = source;
			} else {
				const hls = new Hls();
				hls.loadSource(source);
				hls.attachMedia(video);
				hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
					console.log(data);
					playerOptions.quality = {
						default: hls.levels[0].height,
						options: hls.levels.map((level) => level.height),
						forced: true,
						// Manage quality changes
						onChange: (quality) => {
							hls.levels.forEach((level, levelIndex) => {
								if (level.height === quality) {
									hls.currentLevel = levelIndex;
								}
							});
						}
					}
					player = new Plyr(video, playerOptions);
				});
				window.hls = hls;
			}
			window.player = player;
		});
		@endif

		$(document).ready(() => {
			$('#videoForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
			resumable = new Resumable({
				target: url,
				simultaneousUploads: 16,
				maxFiles: 1,
				query: {
					'_token': `{{csrf_token()}}`,
					token: token
				},
				maxChunkRetries: 0,
				testChunks: true,
				chunkSize: 10 * 1024 * 1024,
			});
			resumable.assignBrowse(document.getElementById('videoFile'));
			resumable.on('fileAdded', function (file, event) {
				$('.custom-file-label').html(file.name);
			});
			resumable.on('fileProgress', function (file, message) {
				setProgress(Number(resumable.progress() * 100).toFixed(0));
			});
			resumable.on('error', function (message, file) {
				alertify.confirm('An error occurred when uploading your file. Retry?', yes => {
					showProgressDialog(true, () => {
						resumable.upload();
					});
				}, no => {
					showProgressDialog(false);
				});
			});
			resumable.on('complete', function () {
				axios.post(url, {
					'is_last': '1',
					token: token
				}).then(response => {
					showProgressDialog(false);
					alertify.alert('Video source has been uploaded successfully.', function () {
						location.href = response.data.route;
					});
				}).catch(error => {
					showProgressDialog(false);
					alertify.alert('Video uploading failed prematurely. Please try again.', function () {
						location.reload();
					});
				})
			})
		});

		submitSource = (event) => {
			showProgressDialog(true, () => {
				$('#videoModal').modal('hide');
				resumable.upload();
			});
		}
	</script>
@stop