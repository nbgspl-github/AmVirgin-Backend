<!-- jQuery  -->
<script src="{{asset("assets/admin/js/jquery.min.js")}}"></script>
<script src="{{asset("assets/admin/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("assets/admin/js/modernizr.min.js")}}"></script>
<script src="{{asset("assets/admin/js/detect.js")}}"></script>
<script src="{{asset("assets/admin/js/fastclick.js")}}"></script>
<script src="{{asset("assets/admin/js/jquery.slimscroll.js")}}"></script>
<script src="{{asset("assets/admin/js/jquery.blockUI.js")}}"></script>
<script src="{{asset("assets/admin/js/waves.js")}}"></script>
<script src="{{asset("assets/admin/js/jquery.nicescroll.js")}}"></script>
<script src="{{asset("assets/admin/js/jquery.scrollTo.min.js")}}"></script>

<!-- Parsley js -->
<script src="{{asset("assets/admin/plugins/parsleyjs/parsley.min.js")}}"></script>

<!-- Required datatable js -->
<script src="{{asset("assets/admin/plugins/datatables/jquery.dataTables.min.js")}}"></script>
<script src="{{asset("assets/admin/plugins/datatables/dataTables.bootstrap4.min.js")}}"></script>
<!-- Buttons examples -->

<!-- Responsive examples -->
<script src="{{asset("assets/admin/plugins/datatables/dataTables.responsive.min.js")}}"></script>
<script src="{{asset("assets/admin/plugins/datatables/responsive.bootstrap4.min.js")}}"></script>

<script src="{{asset('assets/admin/js/toastr.min.js')}}"></script>

<script src="{{asset("assets/admin/js/axios.js")}}"></script>

<!-- Alertify js -->
<script src="{{asset('assets/admin/plugins/alertify/js/alertify.js')}}"></script>

<script src="{{asset('assets/admin/js/Notyf.js')}}"></script>

<!-- jQuery Peity -->
<script src="{{asset('assets/admin/js/circle-progress.js')}}"></script>

<!--Mustache Js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.1.0/mustache.js"></script>

<script src="{{asset('assets/admin/js/duration-picker.js')}}"></script>
<script src="{{asset('assets/admin/js/Selectize.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

<script src="{{asset("assets/admin/plugins/bootstrap-select/js/bootstrap-select.min.js")}}"></script>
<script src="{{asset("assets/admin/plugins/filepond/filepond.min.js")}}"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
<script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
<script src="{{asset("assets/admin/plugins/dropify/dist/js/dropify.min.js")}}"></script>
{{--<!-- App js -->--}}
<script src="{{asset("assets/admin/js/app.js")}}"></script>
<script src="{{asset("js/app.js")}}"></script>

<script>
	dialog = bootbox.dialog({
		size: 'small',
		title: '<span class="text-center mb-0 font-weight-bolder">Please wait!</span>',
		message: (
			`<div class="rounded progress-bar progress-bar-striped progress-bar-animated shadow-primary" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height: 8px;"></div>`
		),
		closeButton: false,
		show: false,
		// animate: false,
		centerVertical: true,
		className: 'zoomIn animated'
	});
	dialog.find('.modal-dialog').css({'max-width': '180px'});
	dialog.find('.modal-header').addClass('mx-auto');
	dialog.find('.modal-content').addClass('shadow-lg');

	dialogProgress = bootbox.dialog({
		size: 'small',
		title: '<span id="my_custom_progress_bar_label" class="text-center mb-0 font-weight-bolder">Please wait!</span>',
		message: (
			`<div id="my_custom_progress_bar_xyz" class="rounded progress-bar progress-bar-striped progress-bar-animated shadow-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0; height: 8px;"></div>`
		),
		closeButton: false,
		show: false,
		// animate: false,
		centerVertical: true,
		className: 'zoomIn animated'
	});
	dialogProgress.find('.modal-dialog').css({'max-width': '250px'});
	dialogProgress.find('.modal-header').addClass('mx-auto');
	dialogProgress.find('.modal-content').addClass('shadow-lg');

	setLoading = (loading, ready = null) => {
		dialog.modal(loading ? 'show' : 'hide');
		if (ready !== null && typeof ready === "function") {
			dialog.one('shown.bs.modal', (e) => {
				console.log('OnShown called');
				ready();
			});
		}
		dialog.shown = loading;
	};

	setProgress = (value) => {
		if (value > 100) {
			$('#my_custom_progress_bar_label').html('Done');
			return;
		}
		$('#my_custom_progress_bar_xyz').css('width', (value) + '%');
		$('#my_custom_progress_bar_label').html('Uploaded ' + (value) + '%');
	}

	showProgressDialog = (show, callback = null) => {
		dialogProgress.modal(show ? 'show' : 'hide');
		if (show)
			setProgress(0);
		if (callback !== null && typeof callback === "function") {
			dialogProgress.one('shown.bs.modal', (e) => {
				console.log('OnShown called');
				callback();
			});
		}
		dialogProgress.shown = show;
	};

	isLoading = () => {
		return dialog.shown;
	};

	window.onload = () => {
		axios.interceptors.response.use(function (response) {
			// Any status code that lie within the range of 2xx cause this function to trigger
			// Do something with response data
			return response;
		}, function (error) {
			// Any status codes that falls outside the range of 2xx cause this function to trigger
			// Do something with response error
			return Promise.reject(error);
		});
	};
	@if($message=Session::get('success'))
	alertify.alert("{{$message}}");
	{{--toastr.success('{{$message}}');--}}
	@endif

	@if($message=Session::get('error'))
	alertify.alert("{{$message}}");
	{{--toastr.error('{{$message}}');--}}
	@endif
</script>