@extends('admin.layouts.app')

@section('custom-htmlheader')
 <!-- Auto populate select multiple-->
  <link href="{{ asset('/css/vender/fileinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/star-rating.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/jquery.signaturepad.css') }}" rel="stylesheet" />

  <script type="text/javascript" src="{{ asset('/js/vender/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/star-rating.min.js') }}"></script>
  <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload.
     This must be loaded before fileinput.min.js -->
  <script src="{{ asset('/js/vender/plugins/purify.min.js') }}" type="text/javascript"></script>
  <!-- the main fileinput plugin file -->
  <script src="{{ asset('/js/vender/fileinput.min.js') }}"></script>
  <!-- Signature Plugin-->
  <script type="text/javascript" src="{{ asset('js/signature/numeric-1.2.6.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/bezier.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/jquery.signaturepad.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/signature/json2.min.js') }}"></script>

@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-8">
              <h1>Preview</h1>
            </div>
          </div>
          <br>
          @include('admin.layouts.errors')

          <div class="participants-responses">
              @include('MasterTemplate.form.template')
                <a href="{{URL::to('admin/forms-managment') }}/{{ $formid }}/edit" class="btn btn-lg btn-primary btn-close">ClOSE</a>
              
          </div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
