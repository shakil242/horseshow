@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
       <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = "Invoice Preview";
          $added_subtitle = Breadcrumbs::render('master-owner-invoice-view',$dataBreadCrumb);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
        <div class="white-board">            
            <div class="row">
                <div class="col">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->

                        </ul>
                        <a class="btn-sm btn-default viewInvoiceBtn" style="background: green none repeat scroll 0% 0%; float: right ! important; margin-top: 40px;"  href="{{URL::to('master-template') }}/exportinvoiceCsv/{{$dataBreadCrumb['id']}}" class="ic_bd_export">Export CSV</a>
                    </div>

            </div>
            @include('admin.layouts.errors')

            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                         @if($FormTemplate !== null)
                            <div class="participants-responses">
                                {!! Form::model($invoice , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['AssetController@update', $invoice->id]]) !!}

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
                                <a class="btn btn-primary" onclick="history.go(-1);" href="#">Back</a>
                            </div>
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
    <script src="{{ asset('/js/disable-form.js') }}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
