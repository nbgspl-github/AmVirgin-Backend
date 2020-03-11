<!-- Left Sidebar Start -->
<div class="left side-menu" style="box-shadow: 3px 0 10px rgba(0,0,0,0.25)">
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
				<li class="menu-title text-primary animatableX">Main</li>

				<li>
					<a href="{{route('admin.home')}}" class="waves-effect animatableX"><i class="mdi mdi-view-dashboard"></i><span> Dashboard </span></a>
				</li>

				<li>
					<a href="{{route("admin.customers.index")}}" class="waves-effect animatableX"><i class="mdi mdi-account-circle mt-1"></i> <span>Customers</span></a>
				</li>

				<li>
					<a href="{{route("admin.sellers.index")}}" class="waves-effect animatableX"><i class="mdi mdi-account-circle mt-1"></i> <span>Sellers</span></a>
				</li>

				<li class="menu-title text-primary animatableX">Shopping</li>
				<li>
					<a href="{{route('admin.categories-banner.index')}}" class="waves-effect animatableX">
						<i class="mdi mdi-cards mt-1"></i> <span>Categories Banner</span></a>
				</li>
				<li>
					<a href="{{route('admin.categories.index')}}" class="waves-effect animatableX"><i class="mdi mdi-cards mt-1"></i> <span>Categories</span></a>
				</li>
				<li>
					<a href="{{route("admin.shop.choices")}}" class="waves-effect animatableX"><i class="mdi mdi-skip-next-circle mt-1"></i> <span>Shop Appearance</span></a>
				</li>

				<li class="has_sub animatableX">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cube mt-1 animatableX"></i><span> Products </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="{{route('admin.products.index')}}">List all</a></li>
						<li><a href="{{route('admin.products.deleted.index')}}">Deleted by sellers</a></li>
					</ul>
				</li>

				<li class="menu-title text-primary animatableX">Entertainment</li>

				<li>
					<a href="{{route("admin.sliders.index")}}" class="waves-effect animatableX"><i class="mdi mdi-skip-next-circle mt-1"></i> <span>Sliders</span></a>
				</li>

				<li>
					<a href="{{route('admin.videos.index')}}" class="waves-effect animatableX"><i class="mdi mdi-movie"></i><span> Videos </span></a>
				</li>

				<li>
					<a href="{{route('admin.tv-series.index')}}" class="waves-effect animatableX"><i class="mdi mdi-movie"></i><span> TV series </span></a>
				</li>

				<li>
					<a href="{{route("admin.subscription-plans.index")}}" class="waves-effect animatableX"><i class="mdi mdi-shopping mt-1"></i> <span>Subscription Plans </span></a>
				</li>

				<li>
					<a href="{{route("admin.genres.index")}}" class="waves-effect animatableX"><i class="mdi mdi-disk mt-1"></i> <span>Genres </span></a>
				</li>

				<li>
					<a href="{{route("admin.notifications.create")}}" class="waves-effect animatableX"><i class="mdi mdi-near-me mt-1"></i> <span>Notifications </span></a>
				</li>

				<li>
					<a href="{{route("admin.settings.index")}}" class="waves-effect animatableX"><i class="mdi mdi-settings mt-1"></i> <span>Settings </span></a>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
</div>