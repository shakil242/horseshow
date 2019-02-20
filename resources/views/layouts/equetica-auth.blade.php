<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<head>

    <link href="{{ asset('/adminstyle/css/vender/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/bootstrap-custom-form.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/bootstrap-colorpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/vender/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('/adminstyle/css/developercustom.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    @show
@section('custom-htmlheader')
@show
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
-->
 <body>
     <div class="row">
        <div class="info">
            @if(Session::has('csrf_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('csrf_message') }}</p>
            @endif
        </div>
    </div>

    @include('layouts.partials.auth-header')

    <div class="vertical-panel">
        @yield('main-content')
    </div>

    @include('layouts.partials.auth-footer')

@section('scripts')
    @include('layouts.partials.scripts')
@show
@section('footer-bootstrap-Overridescripts')
   <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form.js') }}"></script>    
@show
</body>
</html>
