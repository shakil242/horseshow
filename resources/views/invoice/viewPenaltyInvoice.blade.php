@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Penalty Invoice Preview Form</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            {!! Breadcrumbs::render('master-template-billing-invoice-form',$dataBreadcrum) !!}
        </div>
    </div>
    <br>
    @include('admin.layouts.errors')


    @if($FormTemplate !== null)
        <div class="participants-responses">
            {!! Form::model($FormTemplate , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit','url'=>'master-template/invoice/submit/billing']) !!}

            <input type="hidden" name="template_id" value="{{$data->template_id}}">
            <input type="hidden" name="asset_id" value="{{$data->asset_id}}">
            @include('MasterTemplate.form.template')
             <div class="row">
                    <div class="col-sm-2"><a href="/user/dashboard" class="btn-lg btn btn-primary" > Back </a></div>
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
    @if(isset($permission))
        @if(getModulePermission($moduleid,$permission) == READ_ONLY)
            <script src="{{ asset('/js/disable-form.js') }}"></script>
        @endif
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
