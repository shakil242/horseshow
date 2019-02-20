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
 <div id="ajax-loading" class="loading-ajax" style="display: none;"></div>
 <div class="row">
        <div class="info">
            @if(Session::has('csrf_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('csrf_message') }}</p>
            @endif
        </div>
    </div>
    @include('layouts.partials.mainheader2')    

        <div class="main-contents">
         @yield('main-content')
        </div>
    @include('layouts.partials.footer')

</div><!-- ./wrapper -->
</div>
{{--@section('scripts')--}}
    {{--@include('layouts.partials.scripts')--}}
{{--@show--}}

@section('footer-scripts')

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
                $('.sticky-right-panel').removeClass ('active')
                $('.overlay-full').removeClass('active');
            })
        });

        $(window).on("resize",function(){
            setRightPanel();
        })
    </script>


@show

</body>
</html>
