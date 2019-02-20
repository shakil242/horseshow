@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
<div class="row">
    <div class="info">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
    </div>
</div>
@include('admin.layouts.errors')

<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Invoice Preview Form";
          $added_subtitle = Breadcrumbs::render('master-template-billing-invoice-form',$dataBreadcrum);
          $remove_search = 1;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                             @if($FormTemplate !== null)
                                <div class="participants-responses">
                                    {!! Form::model($FormTemplate , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit','url'=>'master-template/invoice/submit/billing']) !!}

                                    <input type="hidden" name="template_id" value="{{$template_id}}">
                                    <input type="hidden" name="inVoiceformId" value="{{$id}}">
                                    <input type="hidden" name="form_id" value="{{$form_id}}">
                                    <input type="hidden" name="asset_id" value="{{$asset_id}}">
                                    <input type="hidden" name="invoiceId" value="{{$invoiceId}}">
                                    <input type="hidden" name="participantId" value="{{$participantId}}">
                                    <input type="hidden" name="responseId" value="{{$responseId}}">
                                    <input type="hidden" name="appOwnerRequest" value="{{$appOwnerRequest}}">
                                    @include('MasterTemplate.form.template')

                                    @if(isset($answer_fields))
                                        <div class="row">
                                            <div class="col-sm-2"><a href="/user/dashboard" class="btn btn-primary" > Back </a></div>
                                         </div>
                                    @else
                                    <div class="row">
                                    <div class="col-sm-2"> {!! Form::submit("Submit" , ['class' =>"btn btn-primary btn-close"]) !!}</div>
                                    <div class="col-sm-2"><input type="submit" formnovalidate="formnovalidate" name="Draft" class="btn btn-secondary btn-close" value="Draft Invoice"></div>
                                    <div class="col-sm-2"><input type="submit" formnovalidate="formnovalidate" name="Discard" onclick="clicked(event)" class="btn btn-primary" value="Discard Invoice"></div>

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
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->

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
