<div class="topbar" style="box-shadow: 0 3px 10px rgba(0,0,0,0.31); background-color: rgba(207,63,67,0.9) !important">

	<div class="topbar-left d-none d-lg-block bg-dark">
		<div class="text-center">

			<a href="{{route('admin.home')}}" class="logo"><img src="{{asset("admin/images/logo.png")}}" height="50" alt="logo" class="customLogo"></a>
		</div>
	</div>

	<nav class="navbar-custom bg-transparent">
		<ul class="list-inline float-right mb-0">
			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
				   aria-haspopup="false" aria-expanded="false">
					<i class="mdi mdi-bell-outline noti-icon"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg">
					<!-- item-->
					<div class="dropdown-item noti-title">
						<h5>Notifications</h5>
					</div>

					<div class="slimscroll" style="max-height: 230px;">

						<!-- item-->
						<span href="javascript:void(0);" class="dropdown-item notify-item">
							<div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
							<p class="notify-details">Your order is placed<span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>
						</span>

						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item notify-item">
							<div class="notify-icon bg-success"><i class="mdi mdi-message"></i></div>
							<p class="notify-details">New Message received<span class="text-muted">You have 87 unread messages</span></p>
						</a>

						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item notify-item">
							<div class="notify-icon bg-warning"><i class="mdi mdi-martini"></i></div>
							<p class="notify-details">Your item is shipped<span class="text-muted">It is a long established fact that a reader will</span></p>
						</a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item notify-item">
							<div class="notify-icon bg-danger"><i class="mdi mdi-message"></i></div>
							<p class="notify-details">New Message received<span class="text-muted">You have 87 unread messages</span></p>
						</a>

						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item notify-item">
							<div class="notify-icon bg-info"><i class="mdi mdi-martini"></i></div>
							<p class="notify-details">Your item is shipped<span class="text-muted">It is a long established fact that a reader will</span></p>
						</a>
					</div>

				</div>
			</li>
			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
				   aria-haspopup="false" aria-expanded="false">
					<span class="text-white text-decoration-none"><img src="{{asset("admin/img/user.png")}}" alt="user" class="rounded-circle">&nbsp;&nbsp;Super Admin</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
					<a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
						Profile</a>
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