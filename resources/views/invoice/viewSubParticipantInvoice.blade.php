@extends('layouts.equetica2')

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
            {!! Breadcrumbs::render('master-template-billing-invoice-form',$dataBreadcrum) !!}
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
            {!! Form::model($FormTemplate , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit','url'=>'master-template/invoice/submit/billing']) !!}

            <input type="hidden" name="template_id" value="{{$template_id}}">
            <input type="hidden" name="inVoiceformId" value="{{$id}}">
            <input type="hidden" name="form_id" value="{{$form_id}}">
            <input type="hidden" name="asset_id" value="{{$asset_id}}">
            <input type="hidden" name="invoiceId" value="{{$invoiceId}}">
            <input type="hidden" name="participantId" value="{{$participantId}}">
            <input type="hidden" name="subId" value="{{$subId}}">
            @include('MasterTemplate.form.template')

            @if(isset($answer_fields))
                <div class="row">
                    <div class="col-sm-2"><a href="/user/dashboard" class="btn-lg btn btn-primary" > Back </a></div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-2"> {!! Form::submit("Submit" , ['class' =>"btn btn-lg  btn-success btn-close"]) !!}</div>
                    <div class="col-sm-2"><input type="submit" formnovalidate="formnovalidate" name="Draft" class="btn btn-lg  btn-success btn-close" value="Draft Invoice"></div>
                    <div class="col-sm-2"><input type="submit" formnovalidate="formnovalidate" name="Discard" onclick="clicked(event)" class="btn btn-lg btn-primary" value="Discard Invoice"></div>

                </div>
            @endif
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
    @if(isset($answer_fields))
        <script src="{{ asset('/js/disable-form.js') }}"></script>
    @endif
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>

    <script>
        function clicked(e)
        {

            if(!confirm('Are you sure to discard the invoice?'))e.preventDefault();
        }
    </script>


@endsection
