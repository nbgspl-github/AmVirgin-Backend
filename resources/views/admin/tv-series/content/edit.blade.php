@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Tv series - Edit or update content','onClick'=>['link'=>'handleAdd()','text'=>'Add more']])
				</div>
				<form id="uploadForm" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body pb-0">
						<div class="form-row" id="container">
							@foreach($videos as $video)
								@include('admin.tv-series.content.bladeVideoBox',['id'=>$loop->index,'key'=>$snap['id']])
							@endforeach
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
		const template = `{!! $template !!}`;

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
			$('html, body').animate({
				scrollTop: $("#item_" + currentIndex).offset().top
			}, 1000, 'swing');
		};

		handleDelete = (id) => {
			$('#item_' + id).remove();
			insertConditionally();
		};

		handleAsyncDelete = (id, key) => {
			alertify.confirm("Are you sure you want to delete this video?",
				(ev) => {
					ev.preventDefault();
					axios.delete('/admin/tv-series/' + videoId + '/content/' + key)
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

		handleVideo = (event, id) => {
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