@extends('layouts.equetica2')

@section('custom-htmlheader')
   @include('layouts.partials.form-header')
@endsection

@section('main-content')
          <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = "Profile Preview";
          $added_subtitle = Breadcrumbs::render('setting-form-view');
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

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
                    </div>

            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')

                                <div class="participants-responses">
                                      {!! Form::open(['url'=>'settings/profile/submit/response/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                      <input type="hidden" name="template_id" value="{{$template_id}}"> 
                                      <input type="hidden" name="form_id" value="{{$formid}}">
                                      <input type="hidden" name="profile_response_id" value="{{$profile_response_id}}">
                                      <input type="hidden" name="accessable_to" value="{{$accessable_to}}">

                                        @include('MasterTemplate.form.template')
                                      <div class="row">
                                        <div class="col-sm-2">{!! Form::submit("Submit" , ['class' =>"btn btn-primary btn-close"]) !!}</div>    
                                        <div class="col-sm-2"><input type="button" class="btn btn-secondary btn-duplicate-form" value="Duplicate"></div>
                                      </div>
                                      {!! Form::close() !!}
                                    
                                </div>

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

    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
        <script src="{{ asset('/js/duplicate-form.js') }}"></script>
        
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
