@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')

    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "Profile Preview";
        $added_subtitle = Breadcrumbs::render('setting-form-view');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

    <!-- Content Panel -->
        <div class="white-board">

          @include('admin.layouts.errors')

          <div class="participants-responses">
                {!! Form::open(['url'=>'master-template/course-content/submit/response/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                <input type="hidden" name="template_id" value="{{$template_id}}"> 
                <input type="hidden" name="form_id" value="{{$formid}}">
                <input type="hidden" name="coursecontent_id" value="{{$coursecontent_id}}">

                  @include('MasterTemplate.form.template')
                <div class="row">
                  <div class="col-sm-2">{!! Form::submit("Submit" , ['class' =>"btn  btn-primary btn-close"]) !!}</div>    
                <input type="button" class="btn btn-primary btn-duplicate-form" value="Duplicate">
                <br>
                </div>
                {!! Form::close() !!}
              
          </div>
        </div>
    </div>


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/duplicate-form.js') }}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
