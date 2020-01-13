<div class="row">
	<div class="col-6">
		<h5 class="page-title animatable">{{$title}}</h5>
	</div>
	<div class="col-6 my-auto">
		@if (isset($action)&&$action!=null)
			<a class="float-right btn btn-outline-primary waves-effect waves-light shadow-sm fadeInRightBig" href="{{$action['link']}}">{{$action['text']}}</a>
		@endif
	</div>
</div>