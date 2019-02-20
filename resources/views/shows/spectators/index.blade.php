@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">

        @php
            $title = "Spectator Registration";
            $added_subtitle = Breadcrumbs::render('Show-trainer-view-Scheduler-Form',nxb_encode($template_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">

                @include('admin.layouts.errors')


          {!! Form::open(['url'=>'shows/submitSpectatorForm','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}

        <input type="hidden" name="template_id" value="{{$template_id}}">
          <input type="hidden" name="show_id" value="{{$show_id}}">
          <input type="hidden" name="app_id" value="{{$app_id}}">
          <input type="hidden" name="form_id" value="{{$form_id}}">

          <div class="participants-responses">
                 @include('MasterTemplate.form.template')
          </div>

    <div class="row">
        <div class="col-md-9">&nbsp</div>
        <div class="col-md-1">
        <input type="submit" class="btn-primary pull-right btn" value="Next">
        </div>
    </div>
          {!! Form::close() !!}
            </div>
        </div>
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
