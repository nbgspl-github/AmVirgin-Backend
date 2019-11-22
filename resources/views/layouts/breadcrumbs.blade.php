<div class="row mx-1">
	<div class="col-sm-12">
		<div class="float-right page-breadcrumb">
			<ol class="breadcrumb p-0 px-2">
				@foreach($data as $key=>$value)
					@php $count=count($data);@endphp
					@if($loop->index+1==$count)
						<li class="breadcrumb-item" style="text-decoration: underline"><a href="{{$value}}">{{$key}}</a></li>
					@else
						<li class="breadcrumb-item"><a href="{{$value}}">{{$key}}</a></li>
					@endif
				@endforeach
			</ol>
		</div>
	</div>
</div>