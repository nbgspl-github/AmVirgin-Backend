@extends('admin.app.app')
@section('styles')

@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit tv series media'])
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<form id="mediaForm" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label for="poster">Poster</label>
									<input type="file" name="poster" id="poster" data-default-file="{{$video->poster}}" class="form-control" data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"/>
								</div>
								<div class="form-group">
									<label for="backdrop">Backdrop</label>
									<input type="file" name="backdrop" id="backdrop" data-default-file="{{$video->backdrop}}" class="form-control" data-max-file-size="2M" data-allowed-file-extensions="jpg png jpeg"/>
								</div>
								<div class="form-group">
									<label>Trailer</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="trailer" name="trailer" accept=".mp4, .mkv">
										<label class="custom-file-label" for="trailer">{{$video->trailer}}</label>
										<small class="text-muted">Choose new to replace previous</small>
									</div>
								</div>
								<div class="form-row">
									<div class="col-6">
										<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
											Update
										</button>
									</div>
									<div class="col-6">
										<a href="{{route("admin.tv-series.edit.action",$video->id)}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
											Cancel
										</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		const video_id = `{{$video->id}}`;
		$(document).on('change', '.custom-file-input', function (event) {
			$(this).next('.custom-file-label').html(event.target.files[0].name);
		})
		$(document).ready(() => {
			$('#poster').dropify();
			$('#backdrop').dropify();
			$('#mediaForm').submit(function (event) {
				event.preventDefault();
				submitSource(this);
			});
		});

		function submitSource(event) {
			const config = {
				onUploadProgress: uploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(event);
			showProgressDialog(true, () => {
				axios.post(`/admin/tv-series/${video_id}/update/media`, formData, config,).then(response => {
					showProgressDialog(false);
					alertify.alert(response.data.message, () => {
						location.href = response.data.payload.route;
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