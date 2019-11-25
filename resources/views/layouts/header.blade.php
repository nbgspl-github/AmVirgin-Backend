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
		<div class="spinner"></div>
	</div>
</div>
<div class="modal shadow-sm fade" tabindex="-1" role="dialog" id="loader-modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-sm mx-auto" role="document">
		<div class="modal-content mx-auto" style="max-width: 150px">
			<div class="modal-body text-center" style="box-shadow: 0 2px 30px rgba(0,13,28,0.46)">
				<div class="row">
					<div class="loader mx-auto mb-4"></div>
				</div>
				<span class="mt-4">Please wait!</span>
			</div>
		</div>
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
@yield('javascript')
@yield('scripts')
</body>
@notify_render
</html>