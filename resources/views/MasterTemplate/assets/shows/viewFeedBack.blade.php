@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

            @php
                $title = "Feedback Preview";
                $added_subtitle = Breadcrumbs::render('view-FeedBack-details',["asset_id"=>$Asset->horse_id,"templateID"=>$template_id]);
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])


    @include('admin.layouts.errors')
            <div class="white-board">

            @if($FormTemplate !== null)
        <div class="participants-responses">
           @include('MasterTemplate.form.template')
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
                 <a class="btn mb-20 btn-primary" onclick="history.go(-1);" href="#">Back</a>
            </div>
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
