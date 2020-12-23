<div id="preloader">
	<div id="status">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</div>
</div>
<div id="wrapper">
	@include('admin.app.partials.sidebar')
	<div class="content-page">
		<div class="content">
			@include('admin.app.partials.topbar')
			<div class="page-content-wrapper">
				<div class="container-fluid" style="padding-top: 16px;padding-bottom: 16px;">
					@yield('content')
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<span class="d-none d-sm-inline-block">Copyright © {{date('Y')}}  AmVirgin Entertainment Pvt. Ltd.</span>
	</footer>
</div>