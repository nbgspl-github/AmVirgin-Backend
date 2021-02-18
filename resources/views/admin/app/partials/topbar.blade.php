<div class="topbar" style="box-shadow: 0 0 5px #858585;">

	<div class="topbar-left d-none d-lg-block bg-dark">
		<div class="text-center">

			<a href="{{route('admin.home')}}" class="logo"><img src="{{asset("assets/admin/images/logo.png")}}" height="50" alt="logo" class="customLogo"></a>
		</div>
	</div>

	<nav class="navbar-custom">
		<ul class="list-inline float-right mb-0">
			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
						aria-haspopup="false" aria-expanded="false">
					<span class="text-white text-decoration-none"><img src="{{asset("assets/admin/img/user.png")}}" alt="user" class="rounded-circle">&nbsp;&nbsp;{{auth()->guard('admin')->user()->name}}</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
					<a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item">
                                    <span class="nav-icon">
                                        <i class="mdi mdi-logout m-r-5 text-muted"></i>
                                    </span>
						<span class="nav-text">Logout</span>
						<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
					</a>

				</div>
			</li>
		</ul>

		<ul class="list-inline menu-left mb-0">
			<li class="list-inline-item">
				<button type="button" class="button-menu-mobile open-left waves-effect">
					<i class="ion-navicon"></i>
				</button>
			</li>
		</ul>

		<div class="clearfix"></div>

	</nav>

</div>