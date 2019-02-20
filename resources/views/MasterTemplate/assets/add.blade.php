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
          $title = "Asset Form Preview";
          $added_subtitle = Breadcrumbs::render('master-template-assets-form', $data = ['template_id' => $template_id,'form_id' => $formid]);
           $remove_search = 1;


        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,''])

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
                                            {!! Form::open(['url'=>'form/userinput/save/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                            {{--@if(count($assets) > 0)--}}



                                              @if($templateCategory == SHOW)
                                                  @if($FormTemplate->form_type != 7)
                                                  <div class="row form-group is-required-point-selection" style="line-height: 20px;">
                                                    <div class="col-sm-2" style="padding-right: 0px;padding-top:30px"><label>Hunter Derby/Classic Section Declarations</label></div>
                                                    <div class="col-sm-7">
                                                      <label class="ml-10">
                                                        <input type="checkbox" name="is_required_point_selection" value="1" class="form-control is-req-check">
                                                        <span>Yes</span>
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="row form-group combined-class-holder" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"> <label>Combined Class</label></div>
                                                  <div class="col-sm-8">
                                                  <label class="label-margin-left">
                                                    
                                                    <input type="checkbox" class="combined-class" name="is_combined" value="1">
                                                    <span>Combined classes will not display under show registration classes. Exhibitors will be charged for the class in which they registered.</span>
                                                  </label>

                                                  </div>
                                                  </div>
                                                  <div class="combining-classes-div">
                                                  <div class="row form-group combined-input-class hidden" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"></div>
                                                  <div class="col-sm-7">
                                                  <select multiple name="combinedClasses[]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                                  @foreach($showClasses as $option)
                                                  <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                                                  @endforeach
                                                  </select>
                                                  </div>
                                                  <div class="col-sm-2">
                                                  <select multiple name="cc_heights[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                                    @foreach(HEIGHTS as $height)
                                                    <option value="{{$height}}">{{$height}}</option>
                                                    @endforeach
                                                  </select>
                                                  </div>
                                                  </div>
                                                  </div>
                                                  <div class="feedback-form-div">
                                                  <div class="row form-group" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"><label>Compulsory Feedback</label></div>
                                                  <div class="col-sm-7">
                                                  <select multiple name="feedback_compulsary[]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                                  <!-- <option value="1"> Jumper faults</option>
                                                  <option value="2"> dressage</option>
                                                  <option value="3"> hunter champions</option> -->
                                                  @foreach($cmpFeed as $option)
                                                  <option value="{{$option->id}}" @if(old("compulsoryfeedback") != null) {{ (in_array($option->id, old("compulsoryfeedback")) ? "selected":"") }} @endif> {{ $option->name }}</option>
                                                  @endforeach
                                                  </select>
                                                  </div>
                                                  </div>
                                                  </div>
                                                  <div class="row form-group division-div" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"> <label>Select Division</label></div>
                                                  <div class="col-sm-7">
                                                  <select multiple name="asset[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6" data-max-options="1" id="allAssets" data-live-search="true">
                                                  @foreach($assets as $option)
                                                  <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                                                  @endforeach
                                                  </select>
                                                  </div>
                                                  </div>
                                                  <div class="row form-group" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"> <label>Non Scoring Class</label></div>
                                                  <div class="col-sm-7">
                                                  <label style="margin-left:-20px">
                                                    
                                                    <input type="checkbox" name="horse_rating_type" value="1" >
                                                    <span>Yes</span>
                                                  </label>

                                                  </div>
                                                  </div>
                                                  <div class="row form-group" style="line-height: 30px;">
                                                  <div class="col-sm-2" style="padding-right: 0px"> <label>Class Type</label></div>
                                                  <div class="col-sm-7">
                                                  <select  name="class_type" class="form-control">
                                                  <option value="">Select Class Type</option>
                                                  @foreach($classTypes as $cls)
                                                    <option value="{{$cls->id}}"> {{ $cls->name }}</option>
                                                  @endforeach
                                                  </select>
                                                  </div>
                                                  </div>
                                                 @else
                                                    <div class="row form-group" style="line-height: 30px;">
                                                        <div class="col-sm-5" style="padding-right: 0px"> <label><input type="checkbox" name="primary_required" value="1" class="form-control"> <span>Check box if the exhibitor is required to participate in all classes of the division.<span></label></div>
                                                    </div>
                                                  @endif
                                              @elseif(($templateCategory == FACILTY || $templateCategory == TRAINER) && $FormTemplate->form_type != 7 )
                                                 <div class="row form-group" style="line-height: 30px;">
                                                    <div class="col-sm-2" style="padding-right: 0px"> <label>Select Primary Asset</label></div>
                                                    <div class="col-sm-7">
                                                        @if(count($assets) > 0)
                                                        <select multiple name="asset[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                            @foreach($assets as $option)
                                                                <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetName($option) }}</option>
                                                            @endforeach
                                                        </select>
                                                        @endif
                                                    </div>
                                                </div>
                                              @elseif($templateCategory == HORSE && $FormTemplate->form_type==2)
                                                <div class="row form-group" style="line-height: 30px;">
                                                    <div class="col-sm-2" style="padding-right: 0px"> <label>Select Owner</label></div>
                                                    <div class="col-sm-7">
                                                        @if(count($ownerForHorse) > 0)
                                                            <select multiple name="owner_id[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                                @foreach($ownerForHorse as $ownerForHorse)
                                                                    <option value="{{$ownerForHorse['id']}}" @if(old("owners") != null) {{ (in_array($ownerForHorse['id'], old("owners")) ? "selected":"") }} @endif> {{$ownerForHorse['owner'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                          <input type="hidden" name="template_id" value="{{$template_id}}">
                                          <input type="hidden" name="form_id" value="{{$formid}}">
                                            <div class="row col-md-12">
                                                @if($templateCategory == TRAINER && $FormTemplate->form_type==2)
                                                    <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Below create a service type such as a group lesson, private lesson, pro-ride that you want associated with the specific trainer you selected</span>
                                                @elseif($templateCategory == TRAINER && $FormTemplate->form_type==7)
                                                    <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Please Enter N/A if you don't have detail for below fields</span>
                                                @else
                                                    <span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Please Enter N/A if you don't have detail for below fields</span>
                                                @endif
                                            </div>
                                         
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

    <style>

        .bootstrap-select .btn.dropdown-toggle.btn-default
        {

            background:#acacac !important;
            color: #FFF;
            padding: 6px 8px;

        }

    </style>
    <script src="{{ asset('/js/shows/create-combined-class.js')}}"></script>
    <script src="{{ asset('/js/preview-form-jquery.js') }}"></script>
@endsection
@section('footer-bootstrap-Overridescripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-custom-form2.js') }}"></script>
@endsection
