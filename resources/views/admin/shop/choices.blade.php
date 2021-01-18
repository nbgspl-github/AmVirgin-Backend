@extends('admin.app.app')
@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card shadow-sm">
				<div class="card-header py-0">
					@include('admin.extras.header', ['title'=>'Customize Shop Homepage Appearance'])
				</div>
				<div class="card-body animatable">
					<div class="w-100">
						<div class="row">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Sliders</h5>
										<p class="card-text">Choose this to update which sliders show up in the header section.</p>
										<a href="{{route('admin.shop.sliders.index')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Sale Offer Timer</h5>
										<p class="card-text">Choose this to update details such as displayed text, remaining time, etc.</p>
										<a href="{{route('admin.shop.sale-offer-timer.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Brands in Focus</h5>
										<p class="card-text">Choose this to modify categories which will show up with a banner.</p>
										<a href="{{route('admin.shop.brands-in-focus.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Today's Deals</h5>
										<p class="card-text">Choose this to marks products which will show up in today's hot deals.</p>
										<a href="{{route('admin.shop.hot-deals.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm-6 pr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Popular Stuff</h5>
										<p class="card-text">Choose this to update which categories will shop up in popular stuff section.</p>
										<a href="{{route('admin.shop.popular-category.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 mr-0">
								<div class="card shadow-sm border animated slideInLeft">
									<div class="card-body">
										<h5 class="card-title">Trending Now</h5>
										<p class="card-text">Choose this to update what categories will show up in trending now.</p>
										<a href="{{route('admin.shop.trending-now.edit')}}" class="btn btn-primary shadow-primary">Edit&nbsp;&nbsp;<i class="mdi mdi-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
@section('javascript')
	<script>
		let chosen = [{
			key: 1,
			values: [1]
		}];
		let optionA = {
			key: 1,
			value: 1
		};
		let optionB = {
			key: 1,
			value: 2
		};
		let optionC = {
			key: 2,
			value: 2
		};
		let optionD = {
			key: 1,
			value: 3
		};
		let optionE = {
			key: 2,
			value: 3
		};

		window.onload = () => {
			handleAddItem(optionA);
			handleAddItem(optionB);
			handleAddItem(optionC);
			handleAddItem(optionD);
			handleAddItem(optionE);
			console.log(chosen);
		};

		handleAddItem = (option) => {
			let foundKey = false;
			let foundValue = false;
			chosen.map((item) => {
				if (item.key === option.key) {
					foundKey = true;
					item.values.forEach((v) => {
						if (v === option.value) {
							foundValue = true;
						}
					});
					if (!foundValue)
						item.values.push(option.value);
				}
			});
			if (!foundKey)
				chosen.push({
					key: option.key,
					values: [option.value]
				});
		};
	</script>
@endsection