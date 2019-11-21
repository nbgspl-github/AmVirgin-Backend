<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<title>{{config("app.name","AmVirgin Dashboard")}}</title>
	<meta content="{{config("app.name","AmVirgin Dashboard")}}" name="AmVirgin Admin Dashboard"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<link rel="shortcut icon" href="{{asset("images/logo.png")}}">
	<link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/icons.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/style.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/custom.css")}}" rel="stylesheet" type="text/css">
</head>

<body class="fixed-left">

<div id="preloader">
	<div id="status">
		<div class="spinner"></div>
	</div>
</div>

<div id="wrapper">
	@include('layouts.sidebar')
	<div class="content-page">
		<div class="content">
			@include('layouts.navbar')
			@yield('content')
		</div>
	</div>

	<footer class="footer">
		<span class="d-none d-sm-inline-block">Copyright © 2019 AmVirgin Entertainment Pvt. Ltd.</span>
	</footer>
</div>
@include('layouts.scripts')

</body>
</html>