@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Form Preview</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            {!! Breadcrumbs::render('master-Event-invoice-view-form', $dataBreadCrumb) !!}
        </div>
    </div>
    <br>
    @include('admin.layouts.errors')

    @if($FormTemplate !== null)
        <div class="participants-responses">
            {!! Form::model($ParticipantResponse , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['AssetController@update', $ParticipantResponse->id]]) !!}

            <input type="hidden" name="template_id" value="{{$template_id}}">
            <input type="hidden" name="form_id" value="{{$formid}}">
            @include('MasterTemplate.form.template')
            {!! Form::close() !!}
        </div>
    @else
        <div class="col-xs-12">
            <div class="row">
                {{NO_FORM_MESSAGES}}
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <a class="btn-lg btn-primary" onclick="history.go(-1);" href="#">Back</a>
        </div>
    </div>

@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/disable-form.js') }}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
