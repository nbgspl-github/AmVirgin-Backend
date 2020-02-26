@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm custom-card">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Customize Shop Appearance'])
				</div>
				<div class="card-body animatable">
					<div class="row">
						<div class="col-sm-6 pr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Sliders</h5>
									<p class="card-text">Choose this to update sliders which show up in the header section of shop homepage.</p>
									<a href="" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
						<div class="col-sm-6 mr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Sale Offer Timer</h5>
									<p class="card-text">Choose this to offer timer details such as displayed text, remaining time, etc.</p>
									<a href="{{route('admin.shop.sale-offer-timer.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-sm-6 pr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Brands in Focus</h5>
									<p class="card-text">Choose this to modify categories which will show up with a banner on the homepage.</p>
									<a href="" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
						<div class="col-sm-6 mr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Today's Deals</h5>
									<p class="card-text">Choose this to marks products which will show up in today's hot deals.</p>
									<a href="" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-sm-6 pr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Popular Stuff</h5>
									<p class="card-text">Choose this to update which categories will shop up in popular stuff section.</p>
									<a href="" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
						<div class="col-sm-6 mr-0">
							<div class="card shadow-sm border animated slideInDown">
								<div class="card-body">
									<h5 class="card-title">Trending Now</h5>
									<p class="card-text">Choose this to update what categories will show up in trending now section.</p>
									<a href="" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
