<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form.js') }}"></script>
    <script src="{{ asset('/adminstyle/js/vender/jquery.mCustomScrollbar.min.js') }}"></script>
    <script src="{{ asset('/js/vender/slideshow.js') }}"></script>
    <script src="{{ asset('/js/vender/wow.js') }}"></script>


    <script src="{{ asset('/js/vender/testimonial.js') }}"></script>
    <script src="{{ asset('/js/custom.js') }}"></script>
    <script src="{{ asset('/js/custom-wow.js') }}"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
