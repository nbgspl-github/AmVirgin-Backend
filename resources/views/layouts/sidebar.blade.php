<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <div class="left-side-logo d-block d-lg-none">
        <div class="text-center">

            <a href="/" class="logo"><img src="{{asset("images/logo.png")}}" height="50" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">

        <div id="sidebar-menu">
            <ul>
                <li class="menu-title"></li>

                <li>
                    <a href="{{route("home")}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{route("users.all")}}" class="waves-effect"><i class="dripicons-briefcase"></i> <span> User Management </span></a>
                </li>

            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>