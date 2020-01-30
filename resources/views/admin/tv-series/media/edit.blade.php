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
							<div class="col-4 animated zoomIn pr-0">
								<div class="card" style="border: 1px solid #aeb4ba;">
									<div class="card-header">
										<span class="header-title">Poster</span>
									</div>
									<div class="card-body rounded p-0">
										<div class="row">
											<div class="col-12 text-center">
												<img id="previewPoster" class="img-fluid" style="max-height: 650px!important; min-height: 650px; object-fit: scale-down" src="{{\App\Storage\SecuredDisk::access()->url($payload->getPoster())}}"/>
												<input type="file" class="d-none" onchange="handlePoster(event)" data-id="@{{id}}" id="input_@{{id}}" name="image[]"/>
												<button type="button" onclick="handlePosterDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 3%; right: 6%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 animated zoomIn pr-0">
								<div class="card" style="border: 1px solid #aeb4ba;">
									<div class="card-header">
										<span class="header-title">Backdrop</span>
									</div>
									<div class="card-body rounded p-0">
										<div class="row">
											<div class="col-12 text-center">
												<img id="previewPoster" class="img-fluid" style="max-height: 650px!important; min-height: 650px; object-fit: scale-down" src="{{\App\Storage\SecuredDisk::access()->url($payload->getBackdrop())}}"/>
												<input type="file" class="d-none" onchange="handlePoster(event)" data-id="@{{id}}" id="input_@{{id}}" name="image[]"/>
												<button type="button" onclick="handlePosterDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="bottom: 3%; right: 6%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 animated zoomIn my-auto">
								<div class="card" style="border: 1px solid #aeb4ba;">
									<div class="card-header">
										<span class="header-title">Trailer</span>
									</div>
									<div class="card-body rounded p-0">
										<div class="row">
											<div class="col-12 text-center">
												<input type="file" class="d-none" onchange="handleTrailer(event)" id="input_trailer" name="image[]"/>
												<div class="embed-responsive embed-responsive-16by9 rounded-lg border">
													<iframe class="embed-responsive-item" src="{{$payload->getTrailer()}}" id="trailer"></iframe>
												</div>
												<button type="button" onclick="handleTrailerDialog();" class="btn btn-danger position-absolute shadow-sm shadow-danger" style="top: 6%; right: 6%; border-radius: 40px; width: 50px; height: 50px;"><i class="fa fa-camera-retro font-20 pt-1"></i></button>
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
								<a href="{{route("admin.tv-series.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
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
			{{--const template = `{!! $template !!}`;--}}

		const videoId = '{{$payload->getKey()}}';

		let currentIndex = 0;

		let progressRing = null;

		let progressPercent = null;

		let CancelToken = axios.CancelToken;

		let source = CancelToken.source();

		let modal = null;

		let modalFinal = null;

		let manuallyFired = true;

		window.onload = () => {
			currentIndex = $('#container').children().length;
			insertConditionally();
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
		};

		handleAdd = () => {
			currentIndex++;
			const render = Mustache.render(template, {
				id: currentIndex
			});
			$('#container').append(render);
		};

		handleDelete = (id) => {
			$('#item_' + id).remove();
			insertConditionally();
		};

		handleAsyncDelete = (id, key) => {
			alertify.confirm("Are you sure you want to delete this snapshot?",
				(ev) => {
					ev.preventDefault();
					axios.delete('/admin/tv-series/' + videoId + '/snaps/' + key)
						.then(response => {
							if (response.data.status === 200) {
								handleDelete(id);
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
		};

		handleFileDialog = (id) => {
			$('#input_' + id).trigger('click');
		};

		handleEnter = (id, action) => {
			const element = $('#preview_' + id);
			if (action === 'switch') {
				element.removeClass('reset-animation');
				element.addClass('frost');
			} else {
				element.removeClass('reset-animation');
				element.addClass('greyscale');
			}
		};

		handleLeave = (id, action) => {
			const element = $('#preview_' + id);
			element.removeClass('greyscale frost');
			element.addClass('reset-animation');
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
			axios.post('/admin/tv-series/' + videoId + '/update/snaps', formData, config,).then(response => {
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
			const output = document.getElementById('preview_' + id);
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			const element = $('#blank_' + id);
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
			const output = document.getElementById('preview_' + id);
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			const element = $('#blank_' + id);
			if (poster !== undefined) {
				element.addClass('d-none');
				reader.readAsDataURL(poster);
			} else {
				element.removeClass('d-none');
				output.src = '';
			}
		};

		handleTrailer = (event) => {
			const reader = new FileReader();
			const output = document.getElementById('trailer');
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			if (poster !== undefined) {
				reader.readAsDataURL(poster);
			} else {
				output.src = '';
			}
		};

		handleTrailerDialog = () => {
			$('#input_trailer').trigger('click');
		};

		countChildren = () => {
			currentIndex = $('#container').children().length;
			return currentIndex;
		};

		insertConditionally = () => {
			const currentItemsCount = $('#container').children().length;
			if (currentItemsCount === 0)
				handleAdd();
		};
	</script>
@stop