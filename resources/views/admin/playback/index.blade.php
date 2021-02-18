@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Playback','action'=>null])
				</div>
				<div class="card-body animatable">
					<video id="video"></video>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
	<script type="application/javascript">
		var video = document.getElementById('video');
		var videoSrc = '{{\App\Library\Utils\Uploads::access()->url('extra/dbz/dbz_adaptive.m3u8')}}';
		if (Hls.isSupported()) {
			var hls = new Hls();
			hls.loadSource(videoSrc);
			hls.attachMedia(video);
			hls.on(Hls.Events.MEDIA_ATTACHED, function () {
				console.log('video and hls.js are now bound together !');
				hls.loadSource('{{\App\Library\Utils\Uploads::access()->url('extra/dbz/dbz_adaptive.m3u8')}}');
				hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
					console.log(
						'manifest loaded, found ' + JSON.stringify(data.levels)
					);
				});
			});
		}
			// hls.js is not supported on platforms that do not have Media Source
			// Extensions (MSE) enabled.
			//
			// When the browser has built-in HLS support (check using `canPlayType`),
			// we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video
			// element through the `src` property. This is using the built-in support
			// of the plain video element, without using hls.js.
			//
			// Note: it would be more normal to wait on the 'canplay' event below however
			// on Safari (where you are most likely to find built-in HLS support) the
			// video.src URL must be on the user-driven white-list before a 'canplay'
			// event will be emitted; the last video event that can be reliably
		// listened-for when the URL is not on the white-list is 'loadedmetadata'.
		else if (video.canPlayType('application/vnd.apple.mpegurl')) {
			video.src = videoSrc;
		}
	</script>
@stop