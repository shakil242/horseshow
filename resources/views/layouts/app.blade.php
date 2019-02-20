<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>

    <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('/adminstyle/css/developercustom.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap-select.css') }}" />

    {{--<link href="{{ asset('/adminstyle/css/vender/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('/css/vender/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/adminstyle/css/vender/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/bootstrap-colorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/main.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('/adminstyle/css/developercustom.css') }}" rel="stylesheet">--}}
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


@section('custom-htmlheader')
@show
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
-->
 <body class="home-paage" data-spy="scroll" data-target=".navbar" data-offset="50">
    @include('layouts.partials.home-header')
    @section('home-banner')
    @show
    <div class="main-contents">
        <div class="container-fluid">
        <div>
        @yield('main-content')
    </div>
        </div></div>
    @include('layouts.partials.auth-footer')
<div class="modals">
    @section('modals')
    @show
</div>
<div>
    @section('scripts')
        @include('layouts.partials.home-scripts')
    @show
    @section('footer-scripts')

    @show
</div>

</body>
</html>
