@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Tv series - Edit or update snapshots','onClick'=>['link'=>'handleAdd()','text'=>'Add more']])
				</div>
				<form id="uploadForm" action="{{route('admin.tv-series.update.attributes',$payload->getKey())}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="form-row" id="container">
							@foreach($snaps as $snap)
								@include('admin.tv-series.snaps.bladeImageBox',['id'=>$loop->index,'key'=>$snap['id']])
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
		const template = `{!! $template !!}`;

		const videoId = '{{$payload->getKey()}}';

		let currentIndex = -1;

		let progressRing = null;

		let progressPercent = null;

		let CancelToken = axios.CancelToken;

		let source = CancelToken.source();

		let modal = null;

		let modalFinal = null;

		let manuallyFired = true;

		window.onload = () => {
			countChildren();
			if (currentIndex < 0) {
				handleAdd();
			}
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
			const render = Mustache.render(template, {
				id: currentIndex
			});
			currentIndex++;
			$('#container').append(render);
		};

		handleDelete = (id) => {
			$('#item_' + id).remove();
			currentIndex--;
			if (currentIndex < 0)
				handleAdd();
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
			console.log(id);
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
			console.log('Percentage is ' + percentCompletedValue);
			progressPercent.text(percentCompleted + ' %');
			progressRing.circleProgress({
				value: percentCompletedValue
			});
		};

		handleImage = (event, id) => {
			const reader = new FileReader();
			const output = document.getElementById('preview_' + id);
			reader.onload = function () {
				output.src = reader.result;
			};
			const poster = event.target.files[0];
			const element = $('#blank_' + id);
			if (poster !== undefined) {
				console.log('Not undefined');
				element.addClass('d-none');
				reader.readAsDataURL(poster);
			} else {
				console.log('Undefined');
				element.removeClass('d-none');
				output.src = '';
			}
		};

		countChildren = () => {
			currentIndex = $('#container').children().length - 1;
			return currentIndex;
		};
	</script>
@stop