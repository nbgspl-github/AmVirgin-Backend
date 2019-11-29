<!DOCTYPE html>
<html lang="en">
<head>
	<title>Amvirgin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{asset('customer/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('customer/bootstrap/bootstrap.min.css')}}">
	<script src="{{asset('customer/js/jquery.min.js')}}"></script>
	<script src="{{asset('seller/bootstrap/bootstrap.min.js')}}"></script>

	<link rel="stylesheet" href="{{asset('customer/slider/slick.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
	<script type="text/javascript" src="{{asset('customer/slider/slick.js')}}"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
</head>


<body>

<a href="#" id="scroll" style="display: none;"><span></span></a>

<div id="myNavmob" class="overlay">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<div class="overlay-content">
		<a href="index.html">Home</a>
		<a href="shop.html">Shop</a>
		<a href="#">News</a>
		<a href="#">Chatmate</a>
	</div>
</div>


<nav class="navbar navbar-expand-lg navclass specific" id="navbar">

	<a class="navbar-brand" href="index.html">
		<img class="logoheader" src="{{asset('customer/img/logo.png')}}" alt="amvirgin" id="logo"/>
	</a>
	<button class="mobbtn">
		<span class="mobicon" onclick="openNav()"><img class="mobmenuicon" src="{{asset('customer/img/mobmenu.png')}}" alt="menu"/> </span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="shop.html">Shop</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">News</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Chatmate</a>
			</li>


		</ul>

		<ul class="nav navbar-nav navbar-right">
			<!--   <li class="nav-item dropdown">
				  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Sign in
				  </a>
				  <div class="dropdown-menu dropmenu3" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="#">Sign in</a>
					<a class="dropdown-item" href="#">Subscription</a>
					<a class="dropdown-item" href="#">Help</a>
				  </div>
				</li> -->
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

			<li class="nav-item lessmargin ">
				<a class="nav-link" href="#"><img class="cart upp2" src="{{asset('customer/img/cart.png')}}"/></a>
			</li>
			<li class="nav-item lessmargin">
				<a class="nav-link" href="#"><img class="cart upp2" src="{{asset('customer/img/notification.png')}}"/></a>
			</li>
			<li class="nav-item lessmargin">
				<a class="nav-link" href="#"><img class="userimage upp2" src="{{asset('customer/img/user.png')}}"/>Sign in</a>
			</li>

		</ul>

	</div>

</nav>

<div class="bodysection">
	<div class="section1 specific">
		<div class="slick-carousel slider21">
			<!-- Inside the containing div, add one div for each slide -->
			<div class="containerimage">
				<!-- You can put an image or text inside each slide div -->
				<img src="{{asset('customer/img/main11.jpg')}}"/>
				<div class="top-left">
					<h2 class="titlemovie" style="-webkit-box-orient: vertical;"> Judgementall Hai Kya </h2>

					<h3 class="description">Judgementall Hai Kya is a 2019 comedy thriller starring Kangana Ranaut, Rajkummar Rao, Jimmy Shergill, Amyra Dastur, Hussain Dalal, and Satish Kaushik. Bobby suffers from acute psychosis and lives in .. </h3>
					<div class="">
						<button class="seeallbtn1">Subscribe</button>
					</div>
				</div>

			</div>
			<div class="containerimage">
				<img src="{{asset('customer/img/main12.jpg')}}"/>
				<div class="top-left">
					<h2 class="titlemovie" style="-webkit-box-orient: vertical;">Uri: The Surgical Strike</h2>

					<h3 class="description">URI: The Surgical Strike is a 2019 Hindi drama starring Vicky Kaushal, Paresh Rawal, Yami Gautam and Mohit Raina. The movie revolves around the events of the surgical strike conducted by the Indian ar.. </h3>
					<div class="">
						<button class="seeallbtn1">Subscribe</button>
					</div>
				</div>
			</div>
			<div class="containerimage">
				<img src="{{asset('customer/img/main13.jpg')}}"/>
				<div class="top-left">
					<h2 class="titlemovie" style="-webkit-box-orient: vertical;">Jabariya Jodi</h2>

					<h3 class="description"> Jabariya Jodi is a 2019 movie starring Sidharth Malhotra and Parineeti Chopra. Based on the concept of Pakadwa Shaadi or forced marriage, the film revolves around two gutsy lovers Abhay, a thug who ki.. </h3>
					<div class="">
						<button class="seeallbtn1">Subscribe</button>
					</div>
				</div>
			</div>
			<div class="containerimage">
				<img src="{{asset('customer/img/main14.jpg')}}"/>
				<div class="top-left">
					<h2 class="titlemovie" style="-webkit-box-orient: vertical;">Bhram</h2>

					<h3 class="description">Bhram is a ZEE5 Original psychological thriller series starring Kalki Koechlin, Eijaz Khan, Sanjay Suri and Bhumika Chawla. The story revolves around a female novelist who suffers from post-traumatic .. </h3>
					<div class="">
						<button class="seeallbtn1">Subscribe</button>
					</div>
				</div>
			</div>

			<div class="containerimage tint">
				<img class="btm" src="{{asset('customer/img/main1.jpg')}}"/>
				<div class="top-left">
					<h2 class="titlemovie" style="-webkit-box-orient: vertical;">The Verdict</h2>

					<h3 class="description">The series revolves around 1959 Indian judiciary case, K. M. Nanavati v. State of Maharashtra where an Indian Naval Command Officer, Kawas Nanavati is accused of murder of Prem Ahuja.. </h3>
					<div class="">
						<button class="seeallbtn1">Subscribe</button>
					</div>
				</div>
			</div>


		</div>

	</div>

	<div class="container section2">
		<div class="row titlesec">
			<div class="col-md-10 col6"><h4 class="underline">Top Picks</h4></div>
			<div class="col-md-2 col6"><a class="seeallbtn" href="http://zobofy.com/amvirgin/collection.html">See All</a></div>
		</div>
		<!-- Create your own class for the containing div -->
		<div class="slick-carousel slider22">
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster1.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Thor</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster13.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Terminator</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster3.jpg')}}" class="image"/>
					<div class="rentmovie">
						<h5>Price ₹10 </h5>
					</div>
					<div class="middle">
						<div class="imgslider">
							<img src="{{asset('customer/img/play.png')}}" alt="play"/>
						</div>
					</div>

				</div>

				<div class="infomovie">
					<h3>Alita</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster4.jpg')}}" class="image"/>
					<div class="rentmovie">
						<h5>Price ₹10 </h5>
					</div>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Antman</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster5.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Justice League</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster6.jpg')}}" class="image"/>
					<div class="rentmovie">
						<h5>Price ₹10 </h5>
					</div>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>World War Z</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster7.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Black Panther</h3>
					<h5>Movies</h5>
				</div>
			</div>
		</div>

	</div>

	<div class="container section2">
		<div class="row titlesec">
			<div class="col-md-10 col6"><h4 class="underline">Shop</h4></div>
			<div class="col-md-2 col6"><a class="seeallbtn" href="http://zobofy.com/amvirgin/collection.html">See All</a></div>
		</div>
		<!-- Create your own class for the containing div -->
		<div class="slick-carousel slider23">
			<div class="container1">
				<img src="{{asset('customer/img/shop1.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop2.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop3.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop4.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop5.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop6.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop7.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
			<div class="container1">
				<img src="{{asset('customer/img/shop8.jpg')}}" class="image"/>
				<div class="middle2">
					<div class="imgslider">
						<button class="shopnowbtn">Shop Now</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container section2">
		<div class="row titlesec">
			<div class="col-md-10 col6"><h4 class="underline">Just Added</h4></div>
			<div class="col-md-2 col6"><a class="seeallbtn" href="http://zobofy.com/amvirgin/collection.html">See All</a></div>
		</div>
		<!-- Create your own class for the containing div -->
		<div class="slick-carousel slider22">
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster8.jpg')}}" class="image"/>
					<div class="rentmovie">
						<h5>Price ₹10 </h5>
					</div>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Captain America: The Winter Soldier</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster9.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Ironman 3</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster10.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Satyamev Jayate</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster11.jpg')}}" class="image"/>
					<div class="rentmovie">
						<h5>Price ₹10 </h5>
					</div>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Blade Runner</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster12.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>NGK</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster13.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Terminator</h3>
					<h5>Movies</h5>
				</div>
			</div>
			<div>
				<div class="container1">
					<img src="{{asset('customer/img/poster1.jpg')}}" class="image"/>
					<div class="middle">
						<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
					</div>
				</div>
				<div class="infomovie">
					<h3>Thor</h3>
					<h5>Movies</h5>
				</div>
			</div>
		</div>
		<hr>
	</div>


	<div class="container section2">

		<div class="row">
			<div class="col-md-3"><h1 class="trendhead"><span class="trd">Trending</span><span> Now</span></h1></div>
			<div class="slick-carousel slidertrending col-md-9">
				<div>
					<div class="container1">
						<img src="{{asset('customer/img/main1.jpg')}}" class="image"/>
						<div class="middle">
							<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
						</div>
					</div>
					<div class="infomovie trend">
						<h3>The Verdict</h3>
						<h5>Movies</h5>
					</div>
				</div>
				<div>
					<div class="container1">
						<img src="{{asset('customer/img/main2.jpg')}}" class="image"/>
						<div class="middle">
							<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
						</div>
					</div>
					<div class="infomovie trend">
						<h3>Fixer</h3>
						<h5>Series</h5>
					</div>
				</div>
				<div>
					<div class="container1">
						<img src="{{asset('customer/img/main3.jpg')}}" class="image"/>
						<div class="middle">
							<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
						</div>
					</div>
					<div class="infomovie trend">
						<h3>Mom</h3>
						<h5>Movies</h5>
					</div>
				</div>
				<div>
					<div class="container1">
						<img src="{{asset('customer/img/main4.jpg')}}" class="image"/>
						<div class="middle">
							<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
						</div>
					</div>
					<div class="infomovie trend">
						<h3>Shor in the city</h3>
						<h5>Movies</h5>
					</div>
				</div>
				<div>
					<div class="container1">
						<img src="{{asset('customer/img/main5.jpg')}}" class="image"/>
						<div class="middle">
							<div class="imgslider"><img src="{{asset('customer/img/play.png')}}" alt="play"/></div>
						</div>
					</div>
					<div class="infomovie trend">
						<h3>Once upon a time in Mumbai</h3>
						<h5>Movies</h5>
					</div>
				</div>


			</div>
		</div>
		<hr>
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

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- slick Carousel CDN -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.5.7/slick.min.js"></script>

<script src="{{asset('customer/js/sli.js')}}"></script>
</body>

</html>