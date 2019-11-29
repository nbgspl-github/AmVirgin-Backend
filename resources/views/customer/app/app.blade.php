<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{config("app.name","AmVirgin Dashboard")}}</title>
	<meta content="{{config("app.name","AmVirgin Dashboard")}}" name="AmVirgin Admin Dashboard"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<link rel="shortcut icon" href="{{asset("images/logo.png")}}">
	<!-- DataTables -->
	<link href="{{asset("plugins/datatables/dataTables.bootstrap4.min.css")}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset("plugins/datatables/buttons.bootstrap4.min.css")}}" rel="stylesheet" type="text/css"/>
	<!-- Responsive datatable examples -->
	<link href="{{asset("plugins/datatables/responsive.bootstrap4.min.css")}}" rel="stylesheet" type="text/css"/>
	<!-- Alertify css -->
	<link href="{{asset('plugins/alertify/css/alertify.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/icons.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/style.css")}}" rel="stylesheet" type="text/css">
	<link href="{{asset("css/custom.css")}}" rel="stylesheet" type="text/css">
	@notify_css
	<style>
	</style>
	@yield('style')
</head>

<body class="fixed-left">


<div id="preloader">
	<div id="status">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</div>
</div>
<div id="wrapper">
	@include('admin.layouts.sidebar')
	<div class="content-page">
		<div class="content">
			@include('admin.layouts.navbar')
			<div class="page-content-wrapper">
				<div class="container-fluid" style="padding-top: 16px;">
					@yield('content')
				</div>
			</div>
		</div>
	</div>

	<footer class="footer">
		<span class="d-none d-sm-inline-block">Copyright © 2019 AmVirgin Entertainment Pvt. Ltd.</span>
	</footer>
</div>
@include('admin.layouts.scripts')
@yield('javascript')
@yield('scripts')
</body>
@notify_render
</html>