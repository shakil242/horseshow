
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>:: Equetica ::</title>

  <!-- Bootstrap -->
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
  <script src="{{ asset('/js/jquery.validate.js') }}"></script>
  <script src="{{ asset('/js/additional-methods.js') }}"></script>

  
<!-- <script src="{{ asset('/adminstyle/js/jquery.multiselect.js') }}"></script>
 -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

