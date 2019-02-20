@extends('layouts.equetica2')

@section('custom-htmlheader')
 @include('layouts.partials.form-header')
@endsection

@section('main-content')




@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Split Class";
          $added_subtitle = Breadcrumbs::render('master-template-assets-form', $data = ['template_id' => $template_id,'form_id' => $formid]);
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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                              @if($FormTemplate !== null)
                                <div class="participants-responses">
                                    {!! Form::model($Asset , ['method'=>'POST','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'url'=>'form/userinput/split-class/' ]) !!}

                                         <div class="row form-group" style="line-height: 30px;">
                                            <div class="col-sm-10">
                                              <p>After creating split class, go to the Manage Show section and add California split class in your show.</p>
                                            </div>
                                        </div>
                                        <div class="row form-group" style="line-height: 30px;">
                                            <div class="col-sm-2" style="padding-right: 0px"> <label>Original Class</label></div>
                                            <div class="col-sm-6">
                                                {{$orignal_name}}
                                            </div>
                                        </div>
                                        <div class="row form-group" style="line-height: 30px;">
                                                    <div class="col-sm-2" style="padding-right: 0px"> <label>Non Scoring Class</label></div>
                                                    <div class="col-sm-6">
                                                    <label style="margin-left:-30px">
                                                    <input type="checkbox" {{($Asset->horse_rating_type== 1)? 'checked="checked"': ''}}  name="horse_rating_type" value="1" >
                                                    <span>Yes</span>
                                                    </label>
                                                      
                                                    </div>
                                                </div>


                                      <input type="hidden" name="template_id" value="{{$template_id}}"> 
                                      <input type="hidden" name="form_id" value="{{$formid}}">
                                      <input type="hidden" name="is_split" value="1">
                                      <input type="hidden" name="orignal_id" value="{{$Asset->id}}">
                                      <hr class="hr-dark hr-thik">
                                      
                                      @include('MasterTemplate.form.template') 
                                      {!! Form::submit("Save" , ['class' =>"btn btn-primary btn-close"]) !!}
                                    <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary">Cancel</button>

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
