<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('wrappers.head')
<body id="page-top">

<div class="container">
    @yield('content')
</div>

@include('wrappers.scripts')
</body>
</html>