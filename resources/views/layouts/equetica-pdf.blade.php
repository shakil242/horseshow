<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<head>
@section('htmlheader')
 
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



    <main id="main">
      <div class="container">
            <div class="white-box">
                 @yield('main-content')
            </div>
        </div>
    </main>


</div><!-- ./wrapper -->
</div>
@section('scripts')
    
@show
@section('footer-bootstrap-Overridescripts')
   
@show
@section('footer-scripts')
   
@show

</body>
</html>
