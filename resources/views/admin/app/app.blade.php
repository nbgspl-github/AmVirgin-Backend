<!DOCTYPE html>
<html lang="{{config('app.locale','en')}}">
<head>
	<title>{{config("app.name","AmVirgin Dashboard")}}</title>
	@include('admin.app.partials.head')
	@include('admin.app.partials.styles')
	@notify_css
</head>

<body class="fixed-left">
@include('admin.app.partials.body')
@include('admin.app.partials.scripts')
@yield('javascript')
</body>
@notify_render
</html>