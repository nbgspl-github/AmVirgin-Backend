<div class="topbar bg-secondary" style="box-shadow: 0 4px 7px #c9c9c9">

	<div class="topbar-left d-none d-lg-block bg-dark">
		<div class="text-center">

			<a href="{{route('home')}}" class="logo"><img src="{{asset("images/logo.png")}}" height="50" alt="logo"></a>
		</div>
	</div>

	<nav class="navbar-custom">

		<ul class="list-inline float-right mb-0">

			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
				   aria-haspopup="false" aria-expanded="false">
					<img src="{{asset("images/users/user-1.jpg")}}" alt="user" class="rounded-circle">
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
					<a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
						Profile</a>
					<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item">
                                    <span class="nav-icon">
                                        <i class="mdi mdi-logout m-r-5 text-muted"></i>
                                    </span>
						<span class="nav-text">Logout</span>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
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