@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Tv series - Edit or update media'])
				</div>
				<form id="uploadForm" action="{{route('admin.tv-series.update.attributes',$payload->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row" id="container">
							<div class="col-6 animated zoomIn my-auto pr-1">
								<div class="card" style="border: 1px solid #aeb4ba;">
									<div class="card-header">
										<span class="header-title">Poster</span>
									</div>
									<div class="card-body rounded p-0">
										<div class="row">
											<div class="col-12 text-center">
												<span class="text-center my-auto" id="blankPoster"><i class="ion ion-image text-muted" style="font-size: 80px"></i></span>
												<img id="previewPoster" class="img-fluid" style="max-height: 710px!important; min-height: 710px; object-fit: contain" src="{{\App\Storage\SecuredDisk::access()->url($payload->getPoster())}}"/>
												<input type="file" class="d-none" onchange="handlePoster(event)" name="poster" id="posterInput" accept=".jpg, .png, .jpeg"/>
												<button type="button" onclick="handlePosterDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 3%; right: 5%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-6 pl-1">
								<div class="row mb-2">
									<div class="col-12 animated zoomIn">
										<div class="card" style="border: 1px solid #aeb4ba;">
											<div class="card-header">
												<span class="header-title">Backdrop</span>
											</div>
											<div class="card-body rounded p-0">
												<div class="row">
													<div class="col-12 text-center">
														<span class="text-center my-auto" id="blankBackdrop"><i class="ion ion-image text-muted" style="font-size: 80px"></i></span>
														<img id="previewBackdrop" class="img-fluid" style="max-height: 325px!important; min-height: 325px; object-fit: scale-down" src="{{\App\Storage\SecuredDisk::access()->url($payload->getBackdrop())}}"/>
														<input type="file" class="d-none" onchange="handleBackdrop(event)" name="backdrop" id="backdropInput" accept=".jpg, .png, .jpeg"/>
														<button type="button" onclick="handleBackdropDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 3%; right:3%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 animated zoomIn">
										<div class="card" style="border: 1px solid #aeb4ba;">
											<div class="card-header">
												<span class="header-title">Trailer</span>
											</div>
											<div class="card-body rounded p-0">
												<div class="row">
													<div class="col-12 text-center">
														<input type="file" class="d-none" onchange="handleTrailer(event)" id="trailerInput" name="trailer" accept=".mp4, .avi"/>
														<div class="embed-responsive embed-responsive-16by9 rounded-lg border" style=" max-height: 325px!important; min-height: 325px;">
															<iframe class="embed-responsive-item" src="{{\App\Storage\SecuredDisk::access()->url($payload->getTrailer())}}" id="trailer" style=" max-height: 325px!important; min-height: 325px;">
																<span class="text-center my-auto" id="blankVideo"><i class="ion ion-videocamera text-muted" style="font-size: 80px;"></i></span>
															</iframe>
														</div>
														<button type="button" onclick="handleTrailerDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="top: 3%; right: 3%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="form-row">
							<div class="col-6">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Update
								</button>
							</div>
							<div class="col-6">
								<a href="{{route("admin.tv-series.edit.action",$payload->getKey())}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
									Cancel
								</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	<script>
		const videoId = '{{$payload->getKey()}}';

		let progressRing = null;

		let progressPercent = null;

		let CancelToken = axios.CancelToken;

		let source = CancelToken.source();

		let modal = null;

		let modalFinal = null;

		let manuallyFired = true;

		window.onload = () => {
			$('#uploadForm').submit(function (event) {
				event.preventDefault();
				handleSubmit(this);
			});
			progressRing = $('#progressCircle');
			progressPercent = $('#progressPercent');
			progressRing.circleProgress({
				value: 0.0,
				animation: false,
				fill: {
					color: '#cf3f43'
				}
			});
			modal = $('#progressModal');
			modalFinal = $('#okayBox');

			const poster = document.getElementById('previewPoster');
			const backdrop = document.getElementById('previewBackdrop');
			const trailer = document.getElementById('trailer');
			if (poster.src.length > 1) {
				$('#blankPoster').addClass('d-none');
			}
			if (backdrop.src.length > 1) {
				$('#blankBackdrop').addClass('d-none');
			}
			if (trailer.src.length > 1) {
				$('#blankVideo').addClass('d-none');
			}
		};

		handleSubmit = (context) => {
			const config = {
				onUploadProgress: handleUploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(context);
			modal.modal({
				keyboard: false,
				show: true,
				backdrop: 'static'
			});
			axios.post('/admin/tv-series/' + videoId + '/update/media', formData, config,).then(response => {
				const status = response.data.status;
				modal.modal('hide');
				if (status !== 200) {
					alertify.alert(response.data.message);
				} else {
					location.reload();
					toastr.success(response.data.message);
				}
			}).catch(error => {
				modal.modal('hide');
				console.log(error);
				toastr.error('Something went wrong. Please try again.');
			});
		};

		handleUploadProgress = (progressEvent) => {
			let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
			let percentCompletedValue = (percentCompleted / 100.0);
			progressPercent.text(percentCompleted + ' %');
			progressRing.circleProgress({
				value: percentCompletedValue
			});
		};

		handlePoster = (event) => {
			const reader = new FileReader();
			const output = document.getElementById('previewPoster');
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			const element = $('#blankPoster');
			if (poster !== undefined) {
				element.addClass('d-none');
				reader.readAsDataURL(poster);
			} else {
				element.removeClass('d-none');
				output.src = '';
			}
		};

		handleBackdrop = (event) => {
			const reader = new FileReader();
			const output = document.getElementById('previewBackdrop');
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			const element = $('#blankBackdrop');
			if (poster !== undefined) {
				element.addClass('d-none');
				reader.readAsDataURL(poster);
			} else {
				element.removeClass('d-none');
				output.src = '';
			}
		};

		handleTrailer = (event) => {
			const output = document.getElementById('trailer');
			output.src = window.URL.createObjectURL(event.target.files[0]);
		};

		handlePosterDialog = () => {
			$('#posterInput').trigger('click');
		};

		handleBackdropDialog = () => {
			$('#backdropInput').trigger('click');
		};

		handleTrailerDialog = () => {
			$('#trailerInput').trigger('click');
		};
	</script>
@stop