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

{{--Anime JS--}}
<script src="{{asset('assets/admin/js/anime.js')}}"></script>

<!-- Dropzone js -->
<script src="{{asset('assets/admin/plugins/dropzone/dist/dropzone.js')}}"></script>

<script src="https://vjs.zencdn.net/7.6.6/video.js"></script>

<!--Bootstrap Duration Picker-->
<script src="{{asset('assets/admin/js/bootstrap-duration-picker.js')}}"></script>

<script src="{{asset('assets/admin/js/jquery.caret.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.tag-editor.min.js')}}"></script>

<!--Mustache Js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.1.0/mustache.js"></script>

<script src="{{asset('assets/admin/js/duration-picker.js')}}"></script>
<script src="{{asset('assets/admin/js/Selectize.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>

<script src="{{asset("assets/admin/plugins/dropify/dist/js/dropify.min.js")}}"></script>
<script src="{{asset("assets/admin/plugins/Notiflix/notiflix-aio-2.7.0.min.js")}}"></script>
{{--<!-- App js -->--}}
<script src="{{asset("assets/admin/js/app.js")}}"></script>
<script src="{{asset("js/app.js")}}"></script>

<script>
	Notiflix.Loading.Init({svgColor: "#c63232",});
	dialog = bootbox.dialog({
		size: 'small',
		title: '<span class="text-center mb-0 font-weight-bolder">Please wait!</span>',
		message: (
			`<div class="rounded progress-bar progress-bar-striped progress-bar-animated shadow-primary" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height: 8px;"></div>`
		),
		closeButton: false,
		show: false,
		animate: false,
		centerVertical: true,
		className: 'zoomIn animated'
	});
	dialog.find('.modal-dialog').css({'max-width': '180px'});
	dialog.find('.modal-header').addClass('mx-auto');
	dialog.find('.modal-content').addClass('shadow-lg');

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

	isLoading = () => {
		return dialog.shown;
	};

	window.onload = () => {
		// window.onInitialize();
	};
	@if($message=Session::get('success'))
	{{--alertify.alert("{{$message}}");--}}
	Notiflix.Notify.Success('{{$message}}');
	@endif

	@if($message=Session::get('error'))
	{{--alertify.alert("{{$message}}");--}}
	Notiflix.Notify.Failure('{{$message}}');
	@endif
</script>