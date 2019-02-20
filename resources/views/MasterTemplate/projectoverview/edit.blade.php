@extends('layouts.equetica2')
@section('custom-htmlheader')
   @include('layouts.partials.form-header')
@endsection

@section('main-content')


<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Porject Overview Form Edit";
          $added_subtitle =  Breadcrumbs::render('project-template-assets-form', $data = ['template_id' => $template_id,'form_id' => $formid]);
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
                             <div class="row col-md-12"><span style="font-size:12px;padding:3px 0px 15px 10px;color: #651e1c; float: left;">Note: Please Enter N/A if you don't have detail for below fields</span>
                                          </div>
                              <div class="participants-responses">
                                  {!! Form::model($Asset , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['ProjectController@update', $Asset->id]]) !!}
                                    <input type="hidden" name="template_id" value="{{$template_id}}">
                                    <input type="hidden" name="form_id" value="{{$formid}}">
                                    @include('MasterTemplate.form.template')
                                    {!! Form::submit("Save" , ['class' =>"btn btn-primary btn-close"]) !!}
                                    <a href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/manage/project-overview" class="btn btn-secondary btn-close">Close</a>
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
    <script src="{{ asset('/js/shows/create-combined-class.js')}}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
