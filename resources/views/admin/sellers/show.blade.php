<div class="card shadow-none mx-auto">
	@if($seller->avatar!=null)
		<img class="card-img-top" src="{{$seller->avatar}}">
	@endif
	<div class="card-body">
		<h5 class="card-title mt-0">{{$seller->name}}</h5>
	</div>
	<ul class="list-group list-group-flush">
		<li class="list-group-item">Email : {{$seller->email}}</li>
		<li class="list-group-item">Mobile : {{$seller->email}}</li>
		<li class="list-group-item">Registered : {{$seller->created_at->format('d/m/Y')}}</li>
	</ul>
</div>