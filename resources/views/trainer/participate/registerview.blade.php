@extends('layouts.equetica')

@section('custom-htmlheader')
   @include('layouts.partials.form-header')
@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-8">
              <h1>Preview</h1>
            </div>
          </div>
          <div class="row">
                <div class="col-sm-8">
                  {!! Breadcrumbs::render('shows-register-history',nxb_encode($template_id)) !!}
                </div>
              </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="back"><a href="#" class="back-to-all-modules" onclick="history.go(-1);"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
              </div>
          </div>
          <br>
          @include('admin.layouts.errors')


          <div class="participants-responses">
                 @include('MasterTemplate.form.template')
          </div>

@endsection

@section('footer-scripts')
<script src="{{ asset('/js/disable-form.js') }}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
