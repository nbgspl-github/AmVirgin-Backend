@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Video','onClick'=>['link'=>'handleAdd()','text'=>'Add more']])
				</div>
				<form id="uploadForm" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-8 mx-auto">
								<div class="card shadow-none" style="border: 1px solid rgba(180,185,191,0.4);">
									<div class="card-header text-white bg-secondary">
										Editing sources for - {{$payload->getTitle()}}
									</div>
									<div class="card-body pb-0">
										<div class="form-row" id="container">
											@foreach($videos as $video)
												@include('admin.videos.content.bladeVideoBox',['qualities'=>$qualities,'languages'=>$languages,'payload'=>$video,'id'=>$loop->index])
											@endforeach
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
										<a href="{{route("admin.videos.edit.action",$payload->getKey())}}" class="btn btn-secondary waves-effect btn-block shadow-secondary">
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
			console.log(id);
			$('#item_' + id).remove();
			insertConditionally();
		};

		handleAsyncDelete = (id, key) => {
			alertify.confirm("Are you sure you want to delete this episode?",
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

		handleSubmit = (context) => {
			const validator = $('#uploadForm').parsley();
			if (!validator.isValid()) {
				alertify.alert('Fix the errors in the form and retry.');
				return;
			}

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
			axios.post('/admin/videos/' + videoId + '/update/content', formData, config,).then(response => {
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
			const output = document.getElementById('preview_' + id);
			output.src = window.URL.createObjectURL(event.target.files[0]);
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