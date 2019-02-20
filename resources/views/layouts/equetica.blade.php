<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->


<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<head>
@section('htmlheader')
    @include('layouts.partials.htmlheader')
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
    @include('layouts.partials.mainheader')

    <main id="main">
      <div class="container">
            <div class="white-box">
                 @yield('main-content')
            </div>
        </div>
    </main>
    @include('layouts.partials.footer')

</div><!-- ./wrapper -->
</div>
@section('scripts')
    @include('layouts.partials.scripts')
@show
@section('footer-scripts')
   
@show
@section('footer-bootstrap-Overridescripts')
   <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form.js') }}"></script>    
@show
</body>
</html>
