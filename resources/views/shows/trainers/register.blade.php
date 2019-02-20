@extends('layouts.equetica2')

@section('custom-htmlheader')
   @include('layouts.partials.form-header')
@endsection

@section('main-content')

    <div class="main-contents">
        <div class="container-fluid">

            @php
                $title = "Register As Trainer";
                $added_subtitle = Breadcrumbs::render('shows-register-trainer',nxb_encode($show_id));
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])
            <div class="white-board">

          @include('admin.layouts.errors')


          <div class="participants-responses">
                @if($FormTemplate!= null)
                {!! Form::open(['url'=>'shows/trainer/store','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}"> 
                <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                <input type="hidden" name="show_id" value="{{$show_id}}">
                <input type="hidden" name="edit_id" value="{{$edit_id}}">
                  @include('MasterTemplate.form.template')
                <div class="row">
                  <div class="col-sm-1">{!! Form::submit("Register" , ['class' =>"btn chekRestriction btn-primary btn-close"]) !!}</div>
                  <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                  <div class="col-sm-1"><a style="text-align: center" class="btn btn-primary" href="{{URL::to('shows') }}/dashboard">Cancel</a></div>
                </div>
                {!! Form::close() !!}
                @else
                <div class="row">
                  <div class="col-sm-12">
                      No form added
                  </div>
                </div>
                @endif
          </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
