<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{config("app.name","AmVirgin Dashboard")}}</title>
    <meta content="Admin Dashboard" name="description"/>
    <meta content="ThemeDesign" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <link rel="shortcut icon" href="{{asset("images/logo.png")}}">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{asset("plugins/morris/morris.css")}}">

    <link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("css/icons.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("css/style.css")}}" rel="stylesheet" type="text/css">

</head>


<body class="fixed-left">

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner"></div>
    </div>
</div>

<!-- Begin page -->
<div id="wrapper">
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.sidebar')
<!-- Left Sidebar End -->

    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <!-- Top Bar Start -->
        @include('layouts.navbar')
        <!-- Top Bar End -->
            @yield('content')

        </div> <!-- Page content Wrapper -->

    </div> <!-- content -->

    <footer class="footer">
        <span class="d-none d-sm-inline-block">Copyright © 2019 AmVirgin Entertainment Pvt. Ltd.</span>
    </footer>

</div>
@include('layouts.scripts')
</body>
</html>