@extends('layouts.equetica')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Invoice Preview</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            {{--{!! Breadcrumbs::render('master-template-participants-all-readonly', $template_id) !!}--}}
        </div>
    </div>
    <br>
    @include('admin.layouts.errors')

    @if($FormTemplate !== null)
        <div class="participants-responses">

            {!! Form::model($invoice , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit','url'=>'master-template/invoice/save/invoice']) !!}

            <input type="hidden" name="template_id" value="{{$template_id}}">
            <input type="hidden" name="form_id" value="{{$formid}}">
            <input type="hidden" name="moduleId" value="{{nxb_encode($id)}}">
            <input type="hidden" name="assetId" value="{{nxb_encode($asset_id)}}">

            @include('MasterTemplate.form.template')


        <div class="row">
            <div class="col-sm-1"  style=" margin-right: 20px;"><input type="submit" class="btn-lg btn btn-success" value="Update"> </div>

            <div class="col-sm-2"><input type="button" onclick="goBack()" class="btn-lg btn btn-primary" value="Back"> </div>
        </div>
        {!! Form::close() !!}
        </div>

    @else
        <div class="col-xs-12">
            <div class="row">
                {{NO_FORM_MESSAGES}}
            </div>
        </div>
    @endif



@endsection

@section('footer-scripts')
    {{--<script src="{{ asset('/js/disable-form.js') }}"></script>--}}
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
