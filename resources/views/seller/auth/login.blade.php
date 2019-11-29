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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
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
				<a class="nav-link" href="#"><img class="userimage upp2" src="{{asset('seller/img/cart.png')}}"/>Sign in</a>
			</li>

		</ul>

	</div>

</nav>
<div class="loginform specific">
	<div class="bodysecform">
		<h2>Login Form</h2>

		<form action="{{route('seller.login.submit')}}" method="post" class="login">
			@csrf
			<div class="container">
				<label for="uname" class="headlabel"><b>Email</b></label>
				<input type="text" placeholder="Enter email" name="email" required>

				<label for="psw" class="headlabel"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="password" required>

				<button type="submit">Login</button>
				<label class="rem">
					<input type="checkbox" checked="checked" name="remember"> Remember me
				</label>
				<span class="psw rem">Forgot <a href="#">password?</a></span>
			</div>

		</form>
	</div>
</div>
<footer class="specific">
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
				<div class="footerlogo"><img src="{{asset('seller/img/logo2.png')}}" alt="amvirgin"/></div>
				<img class="appstore" src="{{asset('seller/img/appstore.png')}}" alt="amvirgin"/>
				<img class="googleplay" src="{{asset('seller/img/googleplay.png')}}" alt="amvirgin"/>
			</div>
		</div>
	</div>


</footer>
</div>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- slick Carousel CDN -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.5.7/slick.min.js"></script>

<script src="{{asset('seller/js/shop.js')}}"></script>

</body>
</html>
