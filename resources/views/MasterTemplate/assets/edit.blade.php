@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Auto populate select multiple-->
  <link href="{{ asset('/css/vender/fileinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('/css/vender/star-rating.min.css') }}" rel="stylesheet" />
  <script type="text/javascript" src="{{ asset('/js/vender/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/star-rating.min.js') }}"></script>
  <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload.
     This must be loaded before fileinput.min.js -->
  <script src="{{ asset('/js/vender/plugins/purify.min.js') }}" type="text/javascript"></script>
  <!-- the main fileinput plugin file -->
  <script src="{{ asset('/js/vender/fileinput.min.js') }}"></script>

  <!-- Auto populate select multiple-->
@endsection

@section('main-content')

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Form";
          $added_subtitle = Breadcrumbs::render('master-template-assets-form', $data = ['template_id' => $template_id,'form_id' => $formid]);
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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                 
                                @if($FormTemplate !== null)
                                <div class="participants-responses">
                                    {!! Form::model($Asset , ['method'=>'PATCH','enctype'=>'multipart/form-data', 'class'=>'formfields-submit', 'action'=>['AssetController@update', $Asset->id]]) !!}

                                      @if($Asset->is_split != 1)
                                        @if($templateCategory == SHOW)
                                            @if($FormTemplate->form_type != 7)
                                              <div class="row form-group is-required-point-selection {{($Asset->is_combined== 1)? 'hidden': ''}}" style="line-height: 20px;">
                                              <div class="col-sm-2" style="padding-right: 0px;padding-top:30px"> 
                                                <label>Hunter Derby/Classic Section Declarations</label>
                                              </div>

                                              <div class="col-sm-6">
                                                <label>
                                                  <input type="checkbox" {{($Asset->is_required_point_selection== 1)? 'checked="checked"': ''}} name="is_required_point_selection" value="1" class="form-control is-req-check">
                                                  <span>Yes</span>
                                                </label>
                                              </div>
                                              </div>

                                              @if($Asset->is_combined== 1)
                                              <div class="row form-group combined-class-holder{{($Asset->is_required_point_selection== 1)? 'hidden': ''}}" style="line-height: 30px;">
                                              <div class="col-sm-2 pr-0" style="padding-top: 30px;">  <label>Combined Class</label></div>
                                              <div class="col-sm-7">
                                              <label>
                                                  <input type="checkbox" class="combined-class" name="is_combined" {{($Asset->is_combined== 1)? 'checked="checked"': ''}}  @if($Asset->is_combined== 1)  onclick = "return false" @endif  value="1">
                                                  <span>Combined classes will not display under show registration classes. Exhibitors will be charged for the class in which they registered.</span>
                                              </label>

                                              </div>
                                              </div>
                                              @endif
                                              <div class="combining-classes-div">
                                              <div class="row form-group combined-input-class  {{($Asset->is_combined== 1)? '': 'hidden'}}" style="line-height: 30px;">
                                              <div class="col-sm-2" style="padding-right: 0px"></div>
                                              <div class="col-sm-6">

                                                <select multiple name="combinedClasses[]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                                    @foreach($showClasses as $option)
                                                        <option value="{{$option->id}}" @if(!empty($combined_class)) {{ (in_array($option->id, $combined_class) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                                                    @endforeach
                                                </select>
                                              </div>
                                              <div class="col-sm-2">
                                                <select multiple name="cc_heights[]" class="selectpicker form-control heightsSelection" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                                                    @foreach(HEIGHTS as $height)
                                                        <?php

                                                        if(!empty($heights) && in_array($height,$heights))
                                                        $select= 'selected';
                                                        else
                                                        $select= '';
                                                        ?>
                                                            <option {{$select}} value="{{$height}}">{{$height}}</option>
                                                    @endforeach
                                                </select>
                                              </div>
                                              </div>
                                              </div>
                                              <div class="feedback-form-div">
                                              <div class="row form-group" style="line-height: 30px;">
                                              <div class="col-sm-2" style="padding-right: 0px"><label>Compulsory Feedback</label></div>
                                              <div class="col-sm-6">
                                                <select multiple name="feedback_compulsary[]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                                  @foreach($cmpFeed as $option)
                                                    <option value="{{$option->id}}" {{ (collect(old('feedback_compulsary',$compulsoryfeedback))->contains($option->id)) ? 'selected':'' }}> {{ $option->name }}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                              </div>
                                              </div>
                                              <div class="row form-group division-div" style="line-height: 30px; {{($Asset->is_combined== 1)? 'display:none': ''}}">
                                              <div class="col-sm-2" style="padding-right: 0px"> <label>Select Division : </label></div>
                                              <div class="col-sm-6">
                                              <select multiple name="asset[]" class="selectpicker form-control" multiple data-size="8" autocomplete="off" data-selected-text-format="count>6" data-max-options="1" id="allAssets" data-live-search="true">
                                              @foreach($assets as $option)
                                                <option value="{{$option->id}}" {{ (collect(old('asset',$parentAsset))->contains($option->id)) ? 'selected':'' }}
                                                       > {{ GetAssetName($option) }}</option>
                                              @endforeach
                                              </select>
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
                                                <div class="row form-group" style="line-height: 30px;">
                                                    <div class="col-sm-2" style="padding-right: 0px"> <label>Class Type</label></div>
                                                    <div class="col-sm-6">
                                                        <select  name="class_type" class="selectpicker form-control"  data-live-search="true">
                                                            <option value="">Select Class Type</option>
                                                            @foreach($classTypes as $cls)
                                                                <option {{ ($Asset->class_type==$cls->id) ? 'selected':'' }} value="{{$cls->id}}"> {{ $cls->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row form-group" style="line-height: 30px;">
                                                    <div class="col-sm-5" style="padding-right: 0px"> <label>
                                                      <input type="checkbox" name="primary_required" @if($Asset->primary_required == 1) checked="checked" @endif value="1" class="form-control"> <span>Check box if the exhibitor is required to participate in all classes of the division.</span></label>
                                                      
                                                    </div>
                                                </div>

                                            @endif

                                        @elseif(($templateCategory == FACILTY || $templateCategory == TRAINER) && $FormTemplate->form_type != 7)
                                        <div class="row form-group" style="line-height: 30px;">
                                            <div class="col-sm-2" style="padding-right: 0px">


                                              @if($templateCategory == TRAINER)
                                                <label>Select Trainer</label>
                                               @else
                                                <label>Select Primary Asset</label>
                                               @endif
                                            </div>
                                            <div class="col-sm-6">
                                                <select multiple name="asset[]" class="selectpicker form-control" multiple data-size="8" autocomplete="off" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                    @foreach($assets as $option)
                                                        <option value="{{$option->id}}" {{ (collect(old('asset',$parentAsset))->contains($option->id)) ? 'selected':'' }}
                                                               > {{ GetAssetName($option) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @elseif($templateCategory == HORSE && $FormTemplate->form_type==2)
                                            <div class="row form-group" style="line-height: 30px;">
                                                <div class="col-sm-2" style="padding-right: 0px"> <label>Select Owner</label></div>
                                                <div class="col-sm-6">
                                                    @if(count($ownerForHorse) > 0)
                                                        <select multiple name="owner_id[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                            @foreach($ownerForHorse as $ownerForHorse)
                                                                <option value="{{$ownerForHorse['id']}}" {{ (collect(old('owner_id',$ownerSelected))->contains($ownerForHorse['id'])) ? 'selected':'' }}> {{$ownerForHorse['owner'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                      @else
                                      <div class="row form-group" style="line-height: 30px;">
                                            <div class="col-sm-10">
                                              <p>After creating split class, go to the Manage Show section and add California split class in your show.</p>
                                            </div>
                                        </div>
                                        @if(isset($Asset->splitOrignalClass))
                                        <div class="row form-group" style="line-height: 30px;">
                                            <div class="col-sm-2" style="padding-right: 0px"> <label>Original Class</label></div>
                                            <div class="col-sm-6">
                                                {{getAssetNameFromId($Asset->splitOrignalClass->orignal_class_id)}}
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
                                        @endif
                                      @endif

                                       <div class="row col-md-12">
                                        @if($templateCategory == TRAINER && $FormTemplate->form_type==2)
                                        <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Below create a service type such as a group lesson, private lesson, pro-ride that you want associated with the specific trainer you selected</span>
                                        @elseif($templateCategory == TRAINER && $FormTemplate->form_type==7)
                                        <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Please Enter N/A if you don't have detail for below fields</span>
                                       @else
                                           <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Please Enter N/A if you don't have detail for below fields</span>
                                       @endif
                                       </div>

                                      <input type="hidden" name="template_id" value="{{$template_id}}">
                                      <input type="hidden" name="form_id" value="{{$formid}}">

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
