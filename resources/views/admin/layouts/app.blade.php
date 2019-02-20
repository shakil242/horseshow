<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
@section('htmlheader')
    @include('admin.layouts.partials.htmlheader')
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

    @include('admin.layouts.partials.mainheader')

    <main id="main">
      <div class="container">
            <div class="white-box">
                 @yield('main-content')
            </div>
        </div>
    </main>
    @include('admin.layouts.partials.footer')

</div><!-- ./wrapper -->
</div>
@section('scripts')
    @include('admin.layouts.partials.scripts')
@show
@section('footer-scripts')
   
@show
@section('footer-bootstrap-Overridescripts')
   <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form.js') }}"></script>    
@show
</body>
</html>
