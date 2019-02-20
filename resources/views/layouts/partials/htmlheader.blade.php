<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>:: Equetica ::</title>

  <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="{{ asset('/css/main.css') }}" />
  <link rel="stylesheet" href="{{ asset('/adminstyle/css/developercustom.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/custom.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap-select.css') }}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

  {{--<script src="{{ asset('/js/vendor/jquery.min.js') }}"></script>--}}
  <script src="{{ asset('/js/vendor/popper.min.js') }}"></script>
  {{--<script src="{{ asset('/js/vendor/bootstrap.min.js') }}"></script>--}}
  <script src="{{ asset('/js/vendor/bootstrap.bundle.min.js') }}"></script>

  <script src="{{asset('/js/vendor/bootstrap-select.js') }}"></script>

  <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  {{--<script type="text/javascript" src="{{ asset('/js/vender/select2.min.js') }}"></script>--}}
  <link href="{{ asset('/css/vender/fileinput.min.css') }}" rel="stylesheet" />
  {{--<link href="{{ asset('/css/vender/select2.min.css') }}" rel="stylesheet" />--}}

  <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/star-rating.min.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap-datetimepicker.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap-datetimepicker-standalone.css') }}" />

  <script src="{{ asset('/adminstyle/js/vender/bootstrap.min.js') }}"></script>


  <script src="{{ asset('/js/vendor/moment.js') }}"></script>


  {{--<script src="{{ asset('/adminstyle/js/vender/bootstrap-colorpicker.js') }}"></script>--}}
  {{--<script src="{{ asset('/adminstyle/js/vender/jquery.mCustomScrollbar.min.js') }}"></script>--}}

  {{--<!-- <link href="{{ asset('/css/vender/bootstrap-dialog.min.css') }}" rel="stylesheet" /> -->--}}
  <script src="{{ asset('/js/vender/bootstrap-dialog.min.js') }}"></script>


  <script src="{{ asset('/js/vender/plugins/purify.min.js') }}" type="text/javascript"></script>
  <!-- the main fileinput plugin file -->
  <script src="{{ asset('/js/vender/fileinput.min.js') }}"></script>
  <!-- Signature Plugin-->
  <script type="text/javascript" src="{{ asset('js/signature/numeric-1.2.6.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/bezier.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/jquery.signaturepad.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/json2.min.js') }}"></script>

<link href="{{ asset('/css/customDateIcon.css') }}" rel="stylesheet" />


    <script>
        window.Laravel = '{!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!}';


    </script>
<script src="{{ asset('/js/custom.js') }}"></script>
<script src="{{asset('/js/main.js') }}"></script>

<script>
    // Assuming that the div or any other HTML element has the ID = loading and it contains the necessary loading image.
  //  $('#ajax-loading').hide(); //initially hide the loading icon
    $(document)
        .ajaxStart(function(){
            $("#ajax-loading").show();
        })
        .ajaxStop(function(){
            $("#ajax-loading").hide();
        });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

  <script>
      setRightPanel();
      $('[data-toggle="tooltip"]').tooltip();

      function setRightPanel(){
          var headerHeight = 0;
          headerHeight = $(".main-header").height();
          if($(window).width()<=991){
              headerHeight = $(".header-responsive").height();
          }
          $(".sticky-right-content-panel").css("top",headerHeight+'px');
      }
      $(document).ready(function(){

          $('.btn-feedback').on('click',function (){
              $('.sticky-right-panel').addClass ('active');
              $('.overlay-full').addClass('active');
          })

          $('.sticky-right-content-panel .close, .overlay-full').on('click',function (){
              $('.sticky-right-panel').removeClass ('active');
              $('.overlay-full').removeClass('active');
          })
      });

      $(window).on("resize",function(){
          setRightPanel();
      });

      $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // added this in order to fix dropdown issue for daterange picker in firefox

  </script>



