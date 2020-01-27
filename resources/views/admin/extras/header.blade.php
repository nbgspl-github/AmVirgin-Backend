<div class="row">
	<div class="col-8">
		<h5 class="page-title animatable">{{$title}}</h5>
	</div>
	<div class="col-4 my-auto">
		@if (isset($action)&&$action!=null)
			<a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="{{$action['link']}}">{{$action['text']}}</a>
		@endif
		@if (isset($onClick)&&$onClick!=null)
			<a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="javascript:void(0);" onclick="{{$onClick['link']}}">{{$onClick['text']}}</a>
		@endif
	</div>
</div>