@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
            $title = "sponsor Registration";
            if($type==0)
            $added_subtitle = Breadcrumbs::render('shows-register',nxb_encode($show_id));
            else
             $added_subtitle = '';
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
            <div class="white-board">

          @include('admin.layouts.errors')


          <div class="participants-responses">
                @if(count($FormTemplate)>0)
                {!! Form::open(['url'=>'shows/sposnorRequest','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}"> 
                <input type="hidden" name="form_id" value="{{$FormTemplate->id}}">
                <input type="hidden" name="show_id" value="{{$show_id}}">

              @if(count($answer_fields)>0)
                      @if($type==0)

                      <div class="row text-right">
                      <div class="col-md-12 mb-10">
                      {!! Form::submit("Next Step" , ['class' =>"btn btn-primary btn-close"]) !!}
                      <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                      <a  class="btn btn-primary ml-10" href="{{URL::to('shows') }}/dashboard">Cancel</a>
                      </div>
                      </div>
                          @endif

                          @endif
                  @include('MasterTemplate.form.template')

                  @if($type==0)
                  <div class="row mb-30 text-right">
                      <div class="col-md-12">

                 {!! Form::submit("Next Step" , ['class' =>"btn btn-primary btn-close"]) !!}
                  <!-- <div class="col-sm-2"><input type="button" class="btn btn-lg btn-primary btn-duplicate-form" value="Duplicate"></div> -->
                 <a  class="btn btn-primary ml-10" href="{{URL::to('shows') }}/dashboard">Cancel</a>
                </div>
                  </div>
                  @endif
                {!! Form::close() !!}
                @else
                  <div class="row">
                      <div class="col-sm-12"> No registration form added for this show uptill now!</div>  
                  </div>
                  
                @endif
          </div>

@endsection

@section('footer-scripts')
    @if($type==1)
    <script src="{{ asset('/js/disable-form.js') }}"></script>
@endif

    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
