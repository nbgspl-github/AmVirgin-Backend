<div class="card shadow-none mx-auto">
	@if($customer->avatar!=null)
		<img class="card-img-top" src="{{$customer->avatar}}">
	@endif
	<div class="card-body">
		<h5 class="card-title mt-0">{{$customer->name}}</h5>
	</div>
	<ul class="list-group list-group-flush">
		<li class="list-group-item">Email : {{$customer->email}}</li>
		<li class="list-group-item">Mobile : {{$customer->email}}</li>
		<li class="list-group-item">Registered : {{$customer->created_at->format('d/m/Y')}}</li>
	</ul>
</div>