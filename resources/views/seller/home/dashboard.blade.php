<!DOCTYPE html>
<html lang="en">
<head>
	<title>Amvirgin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{asset('seller/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('seller/bootstrap/bootstrap.min.css')}}">
	<script src="{{asset('seller/js/jquery.min.js')}}"></script>
	<script src="{{asset('seller/bootstrap/bootstrap.min.js')}}"></script>

	<link rel="stylesheet" href="{{asset('seller/slider/slick.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('seller/dash/style.css')}}">
</head>


<body style="background-color: #fff!important;">


<div id="myNavmob" class="overlay">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<div class="overlay-content">
		<a href="#">Home</a>
		<a href="#">Shop</a>
		<a href="#">News</a>
		<a href="#">Chatmate</a>
	</div>
</div>


<nav class="navbar navbar-expand-lg navclass specific videohead" id="navbar">

	<a class="navbar-brand" href="index.html">
		<img class="logoheader" src="{{asset('seller/img/logo.png')}}" alt="amvirgin" id="logo"/>
	</a>
	<button class="mobbtn">

		<span class="mobicon" onclick="openNav()"><img class="mobmenuicon" src="{{asset('seller/img/mobmenu.png')}}" alt="menu"/> </span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Shop</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">News</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Chatmate</a>
			</li>


		</ul>

		<ul class="nav navbar-nav navbar-right">

			<li class="nav-item upp2">
				<div class="nav-item searchbtn">
					<div class="container">
						<form class="searchbox" method="post">
							<input type="search" placeholder="Search......" name="search" class="searchbox-input" onkeyup="buttonUp();">

							<span class="searchbox-icon"><i class="fa fa-search"></i></span>
						</form>
					</div>

				</div>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="#"><img class="cart upp2" src="{{asset('seller/img/cart.png')}}"/></a>
			</li>
			<li class="nav-item">
				@if(auth()->guard('seller')->check()==false)
					<a class="nav-link" href="#"><img class="userimage upp2" src="{{asset('seller/img/user.png')}}"/>Sign In</a>
				@else
					<form class="d-none" id="logout_form" action="{{route('seller.logout')}}" method="post">@csrf</form>
					<a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('logout_form').submit();"><img class="userimage upp2" src="{{asset('seller/img/user.png')}}"/>Sign Out</a>
				@endif
			</li>

		</ul>

	</div>

</nav>
<div class="container-fluid specific2">


	<div class="bodysection">
		<aside class="side-nav sellernav" id="show-side-navigation1">

			<ul class="categories">
				<li class="active"><i class="fa fa-shopping-basket fa-fw"></i><a href="#">My Orders</a>
				</li>
				<li><i class="fa fa-home fa-fw"></i><a href="#">My products</a>
				</li>
				<li><i class="fa fa-user-circle fa-fw" aria-hidden="true"></i><a href="#">View Profile</a>
					<!--  <ul class="side-nav-dropdown">
					   <li><a href="#">Lorem ipsum</a></li>
					   <li><a href="#">ipsum dolor</a></li>
					   <li><a href="#">dolor ipsum</a></li>
					   <li><a href="#">amet consectetur</a></li>
					   <li><a href="#">ipsum dolor sit</a></li>
					 </ul> -->
				</li>

				<li><i class="fa fa-user fa-fw"></i><a href="#">Switch to User</a>
				</li>
				<li><i class="fa fa-star fa-fw"></i><a href="#">My Subscription</a>
				</li>
				<li><i class="fa fa-bell fa-fw"></i><a href="#">Notifications</a>
				</li>
				<li><i class="fa fa-shopping-bag fa-fw"></i><a href="#"> Sales</a>
				</li>
				<li><i class="fa fa-inr fa-fw"></i><a href="#">Earnings</a>
				</li>
				<li><i class="fa fa-download fa-fw"></i><a href="#">Downloads</a>
				</li>
				<li><i class="fa fa-comments-o fa-fw"></i><a href="#">Messages</a>
				</li>
				<li><i class="fa fa-cog fa-fw"></i><a href="#">Settings</a>
				</li>
				<li><i class="fa fa-sign-out fa-fw"></i><a href="#">Logout</a>
				</li>


			</ul>
		</aside>
		<h3 class="title"> My orders</h3>
		<table class="table table-hover">
			<thead>
			<tr>
				<th scope="col">Order</th>
				<th scope="col">Total</th>
				<th scope="col">Payment</th>
				<th scope="col">Status</th>
				<th scope="col">Date</th>
				<th scope="col">Option</th>
			</tr>
			</thead>
			<tbody>
			<tr>

				<td>#1256</td>
				<td>0.56</td>
				<td>Received</td>
				<td>Completed</td>
				<td>11/10/2019</td>
				<td>
					<button class="detailbtn">Details</button>
				</td>
			</tr>
			<tr>

				<td>#1257</td>
				<td>1.90</td>
				<td>Received</td>
				<td>Completed</td>
				<td>12/11/2019</td>
				<td>
					<button class="detailbtn">Details</button>
				</td>
			</tr>
			<tr>

				<td>#1258</td>
				<td>7.1</td>
				<td>Received</td>
				<td>Completed</td>
				<td>21/11/2019</td>
				<td>
					<button class="detailbtn">Details</button>
				</td>
			</tr>
			</tbody>
		</table>


	</div>

</div>
<!--

<footer class="specific" >
<div class="footer">
	<div class="row">
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-4 footerline">

				<ul><h4>Legal</h4>
				<li>Privacy Policy</li>
				<li>Refund Policy</li>
				<li>Terms & Conditions</li>
				</ul>
				</div>
				<div class="col-md-4 footerline">
				<ul><h4>Customer</h4>
				<li>My Account</li>
				<li>Login</li>
				<li>Register</li>
				</ul>
				</div>
				<div class="col-md-4 footerline">
				<ul><h4>Connect</h4>
				<li>Contact</li>

				</ul>
				</div>
			</div>
		</div>
		<div class="col-md-3">
		<div class="footerlogo"><img src="img/logo2.png" alt="amvirgin"/></div>
		<img class="appstore" src="img/appstore.png" alt="amvirgin"/>
		<img class="googleplay" src="img/googleplay.png" alt="amvirgin"/>
		</div>
	</div>
</div>


</footer> -->
<script type="text/javascript" src="{{asset('seller/slider/slick.js')}}"></script>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- slick Carousel CDN -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.5.7/slick.min.js"></script>

<script src="{{asset('seller/js/shop.js')}}"></script>
<script src="{{asset('seller/dash/style.js')}}"></script>
</body>

</html>