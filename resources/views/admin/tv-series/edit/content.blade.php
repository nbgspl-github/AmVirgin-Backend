@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Edit series'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-6"><h4 class="header-title">Add videos & seasons</h4></div>
						<div class="col-6">
							<button class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" onclick="addRow();">Add video</button>
						</div>
					</div>
					<form id="uploadForm" action="{{route('admin.tv-series.update.content',$key)}}" data-parsley-validate="true" method="POST" enctype="multipart/form-data">
						@csrf
						<div id="form">

						</div>
						<div class="form-row mt-4 p-0">
							<div class="col-6 m-0">
								<button type="submit" class="btn btn-primary waves-effect waves-light btn-block shadow-sm">
									Save
								</button>
							</div>
							<div class="col-6 m-0">
								<a href="{{route("admin.videos.index")}}" class="btn btn-secondary waves-effect btn-block shadow-sm">
									Cancel
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		let progressRing = null;
		let progressPercent = null;
		let CancelToken = axios.CancelToken;
		let source = CancelToken.source();
		let modal = null;
		let modalFinal = null;
		const template = `{!! $data !!}`;

		$(document).ready(function () {
			addRow();
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
		});

		function uploadProgress(progressEvent) {
			let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
			let percentCompletedValue = (percentCompleted / 100.0);
			console.log('Percentage is ' + percentCompletedValue);
			progressPercent.text(percentCompleted + ' %');
			progressRing.circleProgress({
				value: percentCompletedValue
			});
		}

		function finishUpload() {
			window.location.href = '/admin/tv-series';
		}

		function addRow() {
			$('#form').append(template);
		}

		function deleteRow(target) {
			target.parentElement.parentElement.parentElement.remove();
		}

		$('#uploadForm').submit(function (event) {
			event.preventDefault();
			const validator = $('#uploadForm').parsley();
			if (!validator.isValid()) {
				alertify.alert('Fix the errors in the form and retry.');
				return;
			}

			const config = {
				onUploadProgress: uploadProgress,
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			};
			const formData = new FormData(this);
			modal.modal({
				keyboard: false,
				show: true,
				backdrop: 'static'
			});
			axios.post('/admin/tv-series/{{$key}}/content', formData, config,).then(response => {
				const status = response.data.status;
				modal.modal('hide');
				if (status !== 200) {
					alertify.alert(response.data.message);
				} else {
					modalFinal.modal({
						show: true,
						keyboard: false,
						backdrop: 'static'
					});
				}
			}).catch(error => {
				modal.modal('hide');
				console.log(error);
				toastr.error('Something went wrong. Please try again.');
			});
		});
	</script>
@stop