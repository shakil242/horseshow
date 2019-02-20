@extends('layouts.equetica')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Invoice Preview Form</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            {{--{!! Breadcrumbs::render('master-template-form-view', $data = ['template_id' => $id,'form_id' => $formid]) !!}--}}
        </div>
    </div>
    <br>
    @include('admin.layouts.errors')

    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>


    @if($FormTemplate !== null)
        <div class="participants-responses">
            {!! Form::model($FormTemplate , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit','url'=>'master-template/invoice/save/invoice']) !!}

            <input type="hidden" name="template_id" value="{{$FormTemplate->template_id}}">
            <input type="hidden" name="form_id" value="{{$form_id}}">
            <input type="hidden" name="moduleId" value="{{$moduleId}}">
            <input type="hidden" name="assetId" value="{{$assetId}}">


            <input type="hidden" id="modId" name="modId" value="{{nxb_decode($moduleId)}}">
            {{--<input type="hidden" name="participantId" value="{{$participantId}}">--}}
            {{--<input type="hidden" name="responseId" value="{{$responseId}}">--}}

            @include('MasterTemplate.form.template')
            <div class="row">
                <div class="col-sm-2"> {!! Form::submit("Submit" , ['class' =>"btn btn-lg  btn-success btn-close"]) !!}</div>
                <div class="col-sm-2"><input type="button" onclick="goBack()" class="btn-lg btn btn-primary" value="Cancel"> </div>
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
    <script src="{{ asset('js/cookie.js') }}"></script>
<script>
function goBack() {
   var modId = $("#modId").val();
    Cookies.set('previousHistory', modId);
    window.history.back();
}
</script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection

@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection