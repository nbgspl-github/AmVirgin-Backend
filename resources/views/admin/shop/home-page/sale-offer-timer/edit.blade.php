@extends('admin.app.app')
@section('content')
	@include('admin.modals.uploadProgressBox')
	@include('admin.modals.singleActionBox')
	@include('admin.modals.durationPickerDays')
	<div id="react">

	</div>
@endsection

@section('javascript')
	<script src="{{asset('js/app.js')}}"></script>
	<script src="{{asset('assets/admin/utils/DurationPickerDays.js')}}"></script>
	<script>
		let lastPoster = null;
		let lastBackdrop = null;
		let progressRing = null;
		let progressPercent = null;
		let CancelToken = axios.CancelToken;
		let source = CancelToken.source();
		let modal = null;
		let modalFinal = null;
		let manuallyFired = true;

		previewPoster = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('posterPreview');
				output.src = reader.result;
			};
			lastPoster = event.target.files[0];
			reader.readAsDataURL(lastPoster);
		};

		previewBackdrop = (event) => {
			const reader = new FileReader();
			reader.onload = function () {
				const output = document.getElementById('backdropPreview');
				output.src = reader.result;
			};
			lastBackdrop = event.target.files[0];
			reader.readAsDataURL(lastBackdrop);
		};

		openImagePicker = () => {
			$('#pickImage').trigger('click');
		};

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

		$(document).ready(function () {
			setupDurationPicker({
				modalId: 'durationPicker',
				durationId: 'duration'
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
			$('#trending').change(function () {
				if (this.checked) {
					$('#trendingRank').prop("required", true);
				} else {
					$('#trendingRank').prop("required", false);
				}
			});
		});

		{{--$('#uploadForm').submit(function (event) {--}}
		{{--	if (manuallyFired)--}}
		{{--		return;--}}

		{{--	event.preventDefault();--}}
		{{--	const validator = $('#uploadForm').parsley();--}}
		{{--	if (!validator.isValid()) {--}}
		{{--		alertify.alert('Fix the errors in the form and retry.');--}}
		{{--		return;--}}
		{{--	}--}}
		{{--	const config = {--}}
		{{--		onUploadProgress: uploadProgress,--}}
		{{--		headers: {--}}
		{{--			'Content-Type': 'multipart/form-data'--}}
		{{--		}--}}
		{{--	};--}}
		{{--	const formData = new FormData(this);--}}
		{{--	modal.modal({--}}
		{{--		keyboard: false,--}}
		{{--		show: true,--}}
		{{--		backdrop: 'static'--}}
		{{--	});--}}
		{{--	axios.post('/admin/tv-series/{{$payload->getKey()}}/attributes', formData, config,).then(response => {--}}
		{{--		const status = response.data.status;--}}
		{{--		modal.modal('hide');--}}
		{{--		if (status !== 200) {--}}
		{{--			alertify.alert(response.data.message);--}}
		{{--		} else {--}}
		{{--			modalFinal.modal({--}}
		{{--				show: true,--}}
		{{--				keyboard: false,--}}
		{{--				backdrop: 'static'--}}
		{{--			});--}}
		{{--		}--}}
		{{--	}).catch(error => {--}}
		{{--		modal.modal('hide');--}}
		{{--		console.log(error);--}}
		{{--		toastr.error('Something went wrong. Please try again.');--}}
		{{--	});--}}
		{{--});--}}

		function subscriptionTypeChanged(type) {
			const elem = $('#price');
			if (type === 'free' || type === 'subscription') {
				elem.attr('readonly', true);
				elem.prop('required', false);
			} else {
				elem.attr('readonly', false);
				elem.prop('required', true);
			}
		}
	</script>
@stop