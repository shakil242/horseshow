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
            <div class="info">
              @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
              @endif
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
                {!! Form::open(['url'=>'shows/invoice/save','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}"> 
                <input type="hidden" name="assetInvoiceID" value="{{$assetInvoiceID}}">
                <input type="hidden" name="asset_id" value="{{$asset_id}}">
                  @include('MasterTemplate.form.template')
                <div class="row">
                  <div class="col-sm-2">{!! Form::submit("Save" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}</div>    
                  <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                  <div class="col-sm-3"><a style="text-align: center" class="btn btn-lg btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                </div>
                {!! Form::close() !!}
          </div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
