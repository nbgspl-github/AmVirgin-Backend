<!-- Left Sidebar Start -->
<div class="left side-menu">
	<button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
		<i class="ion-close"></i>
	</button>

	<div class="left-side-logo d-block d-lg-none">
		<div class="text-center">

			<a href="/" class="logo"><img src="{{asset("assets/admin/images/logo.png")}}" height="50" alt="logo"></a>
		</div>
	</div>

	<div class="sidebar-inner slimscrollleft">

		<div id="sidebar-menu">
			<ul>
				<!--Main Section-->
				<li class="menu-title text-primary animatableX">Main</li>
				<li>
					<a href="{{route('admin.home')}}" class="waves-effect animatableX"><i class="mdi mdi-view-dashboard"></i><span> Dashboard </span></a>
				</li>
				<li>
					<a href="{{route("admin.customers.index")}}" class="waves-effect animatableX"><i class="mdi mdi-account-circle"></i>
						<span>Customers</span></a></li>
				<li>
					<a href="{{route("admin.sellers.index")}}" class="waves-effect animatableX"><i class="mdi mdi-account-circle"></i>
						<span>Sellers</span></a></li>

				<li class="menu-title text-primary animatableX">Orders</li>

				<li>
					<a href="{{route("admin.orders.index")}}" class="waves-effect animatableX"><i class="mdi mdi-briefcase"></i>
						<span>List</span>
					</a>
				</li>

				<!--Catalog Section-->
				<li class="menu-title text-primary animatableX">Catalog</li>
				<li>
					<a href="{{route('admin.products.attributes.index')}}" class="waves-effect animatableX"><i class="mdi mdi-view-dashboard"></i><span> Attributes </span></a>
				</li>
				<li>
					<a href="{{route('admin.attributes.sets.index')}}" class="waves-effect animatableX"><i class="mdi mdi-view-dashboard"></i><span> Attribute Sets </span></a>
				</li>
				<li>
					<a href="{{route('admin.brands.index')}}" class="waves-effect animatableX"><i class="mdi mdi-tag"></i>
						<span>Brands </span></a></li>
				<li>
					<a href="{{route('admin.categories.index')}}" class="waves-effect animatableX"><i class="mdi mdi-view-dashboard"></i><span> Categories </span></a>
				</li>

				<li>
					<a href="{{route('admin.filters.catalog.index')}}" class="waves-effect animatableX d-none"><i class="mdi mdi-cards"></i>
						<span> Filters </span></a></li>
				<li class="has_sub animatableX {{request()->routeIs('admin.products.*')?'active nav-active':""}}">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cube animatableX"></i><span> Products </span>
						<span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li {!! __active('admin.products.pending.*') !!}>
							<a href="{{route('admin.products.pending')}}">Pending</a>
						</li>
						<li><a href="{{route('admin.products.approved')}}">Approved</a></li>
						<li><a href="{{route('admin.products.rejected')}}">Rejected</a></li>
						<li><a href="{{route('admin.products.deleted')}}">Deleted</a></li>
					</ul>
				</li>
				<li class="has_sub animatableX">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cube animatableX"></i><span> Shop </span>
						<span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="{{route('admin.shop.choices')}}">Homepage</a></li>
					</ul>
				</li>
				<li class="has_sub animatableX">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cube animatableX"></i><span> Entertainment </span>
						<span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="{{route('admin.shop.choices')}}">Homepage</a></li>
					</ul>
				</li>

				<li class="menu-title text-primary animatableX">Entertainment</li>

				<li>
					<a href="{{route("admin.sliders.index")}}" class="waves-effect animatableX"><i class="mdi mdi-skip-next-circle"></i>
						<span>Sliders</span></a>
				</li>

				<li>
					<a href="{{route('admin.videos.index')}}" class="waves-effect animatableX"><i class="mdi mdi-movie"></i><span> Videos </span></a>
				</li>

				<li>
					<a href="{{route('admin.tv-series.index')}}" class="waves-effect animatableX"><i class="mdi mdi-movie"></i><span> TV series </span></a>
				</li>

				<li>
					<a href="{{route("admin.subscription-plans.index")}}" class="waves-effect animatableX"><i class="mdi mdi-shopping"></i>
						<span>Subscription Plans </span></a>
				</li>

				<li>
					<a href="{{route("admin.genres.index")}}" class="waves-effect animatableX"><i class="mdi mdi-disk"></i>
						<span>Genres </span></a>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
</div>