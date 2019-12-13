<!-- Left Sidebar Start -->
<div class="left side-menu" style="box-shadow: 3px 0 10px rgba(0,0,0,0.25)">
	<button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
		<i class="ion-close"></i>
	</button>

	<div class="left-side-logo d-block d-lg-none">
		<div class="text-center">

			<a href="/" class="logo"><img src="{{asset("admin/images/logo.png")}}" height="50" alt="logo"></a>
		</div>
	</div>

	<div class="sidebar-inner slimscrollleft">

		<div id="sidebar-menu">
			<ul>
				<li class="menu-title text-primary">Main</li>

				<li>
					<a href="{{route('admin.home')}}" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
				</li>

				<li>
					<a href="{{route("admin.customers.index")}}" class="waves-effect"><i class="mdi mdi-account-circle mt-1"></i> <span>Customers</span></a>
				</li>

				<li>
					<a href="{{route("admin.sliders.index")}}" class="waves-effect"><i class="mdi mdi-skip-next-circle mt-1"></i> <span>Sliders</span></a>
				</li>

				<li class="menu-title text-primary">Shopping</li>

				<li>
					<a href="{{route("admin.categories.index")}}" class="waves-effect"><i class="mdi mdi-cards mt-1"></i> <span>Categories </span></a>
				</li>

				<li class="menu-title text-primary">Entertainment</li>

				<li>
					<a href="{{route("admin.movies.index")}}" class="waves-effect"><i class="mdi mdi-movie" style="margin-top: 2px"></i> <span>Videos </span></a>
				</li>

				<li>
					<a href="{{route("admin.categories.index")}}" class="waves-effect"><i class="mdi mdi-step-forward mt-1"></i> <span>Series </span></a>
				</li>

				<li>
					<a href="{{route("admin.categories.index")}}" class="waves-effect"><i class="mdi mdi-television mt-1"></i> <span>Live TV </span></a>
				</li>

				<li>
					<a href="{{route("admin.categories.index")}}" class="waves-effect"><i class="mdi mdi-server mt-1"></i> <span>Servers </span></a>
				</li>

				<li>
					<a href="{{route("admin.genres.index")}}" class="waves-effect"><i class="mdi mdi-view-headline mt-1"></i> <span>Genres </span></a>
				</li>

				<li>
					<a href="{{route("admin.notifications.create")}}" class="waves-effect"><i class="mdi mdi-near-me mt-1"></i> <span>Notifications </span></a>
				</li>

				<li>
					<a href="{{route("admin.categories.index")}}" class="waves-effect"><i class="mdi mdi-settings mt-1"></i> <span>Settings </span></a>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
</div>