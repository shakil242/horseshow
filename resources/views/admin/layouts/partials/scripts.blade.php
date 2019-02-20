<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->
<div id="ajax-loading" class="loading-ajax"></div>
    <script src="{{ asset('/adminstyle/js/vender/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-select.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/jquery.mCustomScrollbar.min.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/custom.js') }}"></script>
    

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
