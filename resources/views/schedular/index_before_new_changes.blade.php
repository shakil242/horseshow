    @extends('layouts.equetica2')

    @section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />

    <!-- END:Search populate select multiple-->
    @endsection

    @section('main-content')
        @php $m_s_fields = getButtonLabelFromTemplateId($template_id,'m_s_fields');
        $templateType = GetTemplateType($template_id);

      foreach ($AllAssets as $asset)
            {
            $assets[] =  array('name'=>GetAssetName($asset),'id'=>$asset->id);
            }
       @endphp

    <div class="row">
    <div class="col-sm-7">
        @php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); @endphp
    <h1>{{post_value_or($ya_fields,'manage_scheduler','Schedular')}}</h1>
    </div>
    <div class="col-sm-4 action-holder">
    <div class="search-form">
    <input type="text" placeholder="Search By Name, Date, Location etc ..." id="mySearchTerm">
    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
    </div>
    </div>
    </div>

    <div class="row">
    <div class="info">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    </div>
    </div>
    <div class="row">
    <div class="col-sm-8">
    {!! Breadcrumbs::render('master-template-breadcrumbs-list-schedular', $template_id, nxb_encode($appId)) !!}
    </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
    <div class="col-sm-10">
    <h1>{{GetTemplateName($template_id,\Auth::user()->id)}}</h1>
    </div>

    <div class="col-sm-1" style="float: right; margin-right: 30px;">
    <button type="button" class="btn btn-success AddMoreSch">Add More</button>
    </div>

    </div>
    <div class="row" style="padding: 0px 15px;">

    <!-- Accordion START -->
    <div class="panel-group" id="accordion">
    @if($manageShows->count()>0)
    @foreach($manageShows as $show)
    <div class="panel panel-default">
        <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#{{$show->id}}">
    @if($show->title != null) <h2>{{$show->title}}</h2>@else <h2>{{post_value_or($m_s_fields,'showTitle','Show Title')}}</h2> @endif
    </div>
    <div id="{{$show->id}}" class="defaultClasses panel-collapse collapse in">
    <div class="panel-body">
    {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}

        <div class="col-sm-offset-9 col-sm-2" style="margin-bottom: 10px; float: right">
            {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
        </div>

   <input type="hidden" name="appId" value="{{$appId}}">
    <div class="col-sm-12">
    <div class="row" style="margin-top: 7px;">
    <div class="col-md-4">
    <label><span>{{post_value_or($m_s_fields,'showTitle','Show Title')}}:</span></label>
    <input @if($show->title != null) value="{{$show->title}}" @endif type="text"  class="form-control" required="required"  name="showTitle" />
    </div>
    <div class="col-md-4" >
    <label><span>{{post_value_or($m_s_fields,'DateFrom','Date From')}} :</span></label>
    <input @if($show->date_from != null) value="{{date('m/d/Y h:i a',strtotime($show->date_from))}}" @endif type="text"  name="dateFrom" required="" class="form-control datetimepicker8">
    </div>
    <div class="col-md-4" >


    <label><span>{{post_value_or($m_s_fields,'DateTo','Date To')}} :</span></label>
    <input @if($show->date_to != null) value="{{date('m/d/Y h:i a', strtotime($show->date_to))}}" @endif type="text"  name="dateTo" required="" class="form-control datetimepicker8">
    </div>
    </div>

        @if($templateType==SHOW)
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">

            <div class="col-md-4" >
                <label><span>{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}} :</span></label>
               <select required name="governing_body" class="bootstrap-select" onchange="checkUsef($(this))">
                   <option value="">Select Governing Body</option>
                   <option @if($show->governing_body=='USEF') selected="selected" @endif value="USEF">USEF</option>
                   <option @if($show->governing_body=='Local') selected="selected" @endif value="Local">Local</option>
                   <option @if($show->governing_body=='EC') selected="selected" @endif value="EC">EC</option>
               </select>
               </div>

        <div class="col-md-4 usef_number" >
            <label><span>{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}} :</span></label>
            {{ Form::text('usef_id', isset($show->usef_id)? $show->usef_id :'', ['id' => '',
            'placeholder'=>"USEF Competition ID",'required'=>'required' ,'class' => 'form-control usefNo']) }}        </div>
    <div class="col-md-4">
      <label><span>{{post_value_or($m_s_fields,'ShowRating','Show Rating')}} :</span></label>
      <select name="show_type_class" style="width: 75%" class="selectpicker show-tick form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
           @if(count($ShowType)>0)
               @foreach($ShowType as $asset)
                   <option value="{{$asset->id}}" {{($asset->id == $show->show_type_id)? 'selected':''}}> {{$asset->name}}</option>
               @endforeach
           @endif
       </select>
    </div>
        </div>
        @endif
        <div class="row" style="margin-top: 15px;">

        <div class="col-md-4">
        <label><span>{{post_value_or($m_s_fields,'statetax','State Tax % ')}} :</span></label>
         {{ Form::number('state_tax', isset($show->state_tax)? $show->state_tax :'', ['id' => '',
            'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
    </div>
    <div class="col-md-4">
        <label><span>{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}} :</span></label>
        {{ Form::number('federal_tax', isset($show->federal_tax)? $show->federal_tax :'', ['id' => '',
            'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
    </div>
    <div class="col-md-4">
        <label><span>{{post_value_or($m_s_fields,'contactinformation','Contact Information:')}} :</span></label>
        {{ Form::text('contact_information', isset($show->contact_information)? $show->contact_information :'', ['id' => '',
            'placeholder'=>"Add Contact of show",'class' => 'form-control','required'=>'required']) }}
    </div>
        </div>
        <div class="row" style="margin-top: 15px;">

        <div class="col-md-6">
            <label><span>{{post_value_or($m_s_fields,'Location','Location')}} :</span></label>
            {{ Form::text('location', isset($show->location)? $show->location :'', ['id' => '',
            'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'']) }}
        </div>

    </div>

    <div class="invite-participant-history">

    @if($schedular_forms->count())
    @foreach($schedular_forms as $forms)

    @php
     $restriction = [];

     $res =  restrictedScheduledDates($forms->id,$show->id);
    $answers = $res->first();
    if($answers!='' && $show->id!='')
    $restriction = $answers->SchedulerRestriction($answers->id,$forms->id,$show->id)->get()->toArray();
    // print_r($restriction);

    $requiredClass = '';

    if($answers != null)
    {
      if($answers->name!='')
       $requiredClass = 'required';
    }
    @endphp

    <input type="hidden" name="template_id" value="{{$template_id}}">
    <input type="hidden" name="form_id[{{$forms->id}}]" value="{{$forms->id}}">
    <input type="hidden" class="show_id" name="show_id" @if($show->id != null) value="{{$show->id}}" @endif>
    <input type="hidden" name="dateChangeCon" class="dateChangeCon">

    @if($answers!='')
    <input type="hidden" name="schedual_id[{{$forms->id}}]" value="{{$answers->id}}">
    @else
     <input type="hidden" name="schedual_id[{{$forms->id}}]" value="">
    @endif

    <div class="create-form">
    <div class="row">
    <div class="col-sm-12">
    <h3>{{$forms->name}}</h3><br>
    <div class="row fields">
    <div class="col-sm-6">
    <label><span>{{post_value_or($m_s_fields,'SchedularName','Schedular Name')}} :</span>
        <input  type="text" class="form-control checkValidation c-control"  @if($answers != null) value="{{$answers->name}}" @endif name="schedular_name[{{$forms->id}}]" /></label>
    </div>

    <div class="col-sm-12">
    <div class="schedual-restrictions">
        <div class="col-sm-12" style="padding-left: 0px;">
            <label><h3>{{post_value_or($m_s_fields,'MakeRistriction','Make Ristriction')}}</h3></label>
        </div>


        {{--If restrctition values has already added--}}

        @if($restriction!= null && isset($restriction))
            <div class="adding-restrictions-options">
         @foreach($restriction as $row)
             @php

                 $scoreFromArr = [];
                 $assetArr = [];
                 if($row['asset_id']!=0)
                 $assetArr = explode(',',$row['asset_id']);
                 if($row['score_from']!='')
                 $scoreFromArr = explode(',',$row['score_from']);


             $assetjson = json_encode($assetArr);
             @endphp

             <div class="row" style=" margin-bottom: 15px;">
                     <div class="col-sm-12 ClassAssets" data-value="{{nxb_encode($row['restriction'])}}" data-id="@if($show->id != null){{$show->id}}@endif">
                         <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Select Class')}}:</span></label>
                         <select {{$requiredClass}} multiple name="assets[{{$forms->id}}][{{$row['id']}}][]" title=" --- Select Classes --- " data-id="{{$forms->id}}" style="width: 75%" class="selectpicker show-tick form-control allAssets" multiple data-size="8" data-selected-text-format="count>6"   data-live-search="true">
                             @if($AllAssets->count()>0)
                                 @foreach($AllAssets as $asset)
                                     <option value="{{$asset->id}}" {{ (collect(old('assets['.$forms->id.']',$assetArr))->contains($asset->id)) ? 'selected':'' }} > {{GetAssetName($asset)}}</option>
                                 @endforeach
                             @endif
                         </select>

                     </div>
                 <div class="col-sm-12" data-classes="{{nxb_encode($assetjson)}}" data-value="{{nxb_encode($row['restriction'])}}" data-id="@if($show->id != null){{$show->id}}@endif">
                     <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Score From')}}:</span></label>
                     <select multiple name="score_from[{{$forms->id}}][{{$row['id']}}][]" title=" --- Select Classes --- " data-classes="{{$assetjson}}" data-id="{{$forms->id}}" style="width: 75%" class="selectpicker show-tick form-control scoreAssets" multiple data-size="8" data-selected-text-format="count>6"   data-live-search="true">
                         @if($AllAssets->count()>0)
                             @foreach($AllAssets as $asset)
                                 <option value="{{$asset->id}}" {{ (collect(old('score_from['.$forms->id.']',$scoreFromArr))->contains($asset->id)) ? 'selected':'' }} > {{GetAssetName($asset)}}</option>
                             @endforeach
                         @endif
                     </select>

                 </div>

                 <div class="col-sm-8" style="margin-top: 10px;">
                     <label><span>{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</span>

                         <input {{$requiredClass}} type="text" class="daterange form-control datetime-control"
                                placeholder="Add Date" name="datetimeschedual[{{$forms->id}}][{{$row['id']}}]"
                                value="{{$row['restriction']}}" />

                         <input  type="hidden"
                                 name="CheckDatetimeschedule[{{$forms->id}}][{{$row['id']}}]"
                                value="{{$row['restriction']}}" />

                     </label>

                     <label><span>{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</span>

                         <input  type="text" class="daterange form-control"
                                placeholder="Add Block Time" name="blockTime[{{$forms->id}}][{{$row['id']}}]"
                                value="{{$row['block_time']}}" /></label>
                     <label><span>{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Titlle')}}</span> <input type="text" class="form-control"  placeholder="Add Title Of Block Time" name="blockTimeTitle[{{$forms->id}}][{{$row['id']}}]" value="{{$row['block_time_title']}}" /></label>
                     <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span> <input @if($row['is_multiple_selection'] == 1) {{'checked'}} @endif  type="checkbox" class="form-control" name="multipleSelection[{{$forms->id}}][{{$row['id']}}]" value="" /></label>

                     <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;">
                     <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Riders to Book Rides')}}</span>
                         <input @if($row['is_rider_restricted'] == 1) {{'checked'}} @endif  type="checkbox" class="form-control" name="restrictRiders[{{$forms->id}}][{{$row['id']}}]" value="" /></label>
                     </div>

                 </div>

                 <div class="col-sm-1" style="margin-top: 20px;">
                     <a href="javascript:void(0)" class="deleterowschedual"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>

             </div>

         @endforeach
     </div>
        @else
            <div class="adding-restrictions-options">
         <div class="row">
             <div class="col-sm-12 ClassAssets">
                 <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Select Class')}}:</span></label>
                 <select {{$requiredClass}}  multiple name="assets[{{$forms->id}}][1][]" title=" --- Select Classes --- " data-id="{{$forms->id}}" style="width: 75%" class="selectpicker show-tick form-control allAssets" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                     @if($AllAssets->count()>0)
                         @foreach($AllAssets as $asset)
                             <option value="{{$asset->id}}" > {{GetAssetName($asset)}}</option>
                         @endforeach
                     @endif
                 </select>

             </div>
             <div class="col-sm-12 ClassAssets">
                 <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Score From')}}:</span></label>
                 <select   multiple name="score_from[{{$forms->id}}][1][]" title=" --- Select Classes --- " data-id="{{$forms->id}}" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                     @if($AllAssets->count()>0)
                         @foreach($AllAssets as $asset)
                             <option value="{{$asset->id}}" > {{GetAssetName($asset)}}</option>
                         @endforeach
                     @endif
                 </select>

             </div>
             <div class="col-sm-8" style="margin-top: 10px;">
                 <label><span>{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</span> <input {{$requiredClass}}  type="text" class="daterange updateTimeChange form-control datetime-control" placeholder="Add Date" name="datetimeschedual[{{$forms->id}}][1]" value="" /></label>

                 <label><span>{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</span> <input type="text"  class="daterange form-control"
                                                              placeholder="Add Date" name="blockTime[{{$forms->id}}][1]" value="" /></label>
                 <label><span>{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</span> <input type="text" class="form-control"  placeholder="Add Title Of Block Time" name="blockTimeTitle[{{$forms->id}}][1]" value="" /></label>
                 <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span> <input type="checkbox" class="form-control" name="multipleSelection[{{$forms->id}}][1]" value="" /></label>

                 <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;">
                     <label>
                         <span style="padding-top: 0px;">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Riders to Book Rides')}}</span>
                         <input type="checkbox" class="form-control" name="restrictRiders[{{$forms->id}}][1]" value="" /></label>
                 </div>

             </div>

         </div>

     </div>
        @endif

    <div class="row">
     <div class="col-sm-6" style="margin-top: 10px;">
         <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset_{{$forms->id}}">

         <input type="button" data-id="{{$forms->id}}" class="btn btn-success add-more-restriction" value="Add More">
     </div>
    </div>
    </div>
    </div>

        <div class="col-sm-12 slotsContainer">
            <div class="col-sm-12" style="padding-left: 0px;">
                    <label><h3>{{post_value_or($m_s_fields,'SlotsDurationOfClasses','Slots Duration of Classes')}}</h3></label>
                </div>
                    <div class="adding-restrictions-options">
                            @if(count($assets)>0)
                                <div class="row" style=" margin-bottom: 15px;">
                                    <div class="panel-body table-responsive" style=" max-height: 450px;overflow-y: scroll;">

                                    <table  class="table table-bordered table-striped display">
                                        <thead>
                                        <tr><th>{{post_value_or($m_s_fields,'Class','Class')}}</th><th>{{post_value_or($m_s_fields,'Slots Duration','Slots Duration')}}</th></tr></thead>
                                        <tbody>
                                       @php $serial = $loop->index + 1;
                                            $slots_duration = '';
                                            $minutes= '';
                                            $seconds= '';
                                            $selected = '';
                                            $slotsArr = [];
                                            if($answers!='')
                                           {
                                             $slotsContainer = $answers->SchedulerSlots($answers->id,$forms->id,$show->id);
                                             if($slotsContainer)
                                             {
                                                 foreach ($slotsContainer as $slots)
                                                 {
                                                 $slots_duration =  $slots->slots_duration;
                                                 $slotsArr = explode(':',$slots_duration);

                                                  if(count($slotsArr)>1)
                                                     {
                                                       $minutes = $slotsArr[0];
                                                       $seconds = $slotsArr[1];
                                                     }
                                                @endphp

                                       <tr class="asset_{{$slots->asset_id}}">
                                           <td>{{GetAssetNamefromId($slots->asset_id)}}</td>
                                           <td>
                                               <div class="col-md-6">
                                                   <label>Select Minutes</label>
                                                   <select name="slotsMinutes[{{$slots->form_id}}][{{$slots->asset_id}}]">
                                                       <option value="">Select</option>

                                                       @php
                                                       for($i=1;$i<60;$i++)
                                                       {
                                                           if($i<10)
                                                               $i="0".$i;

                                                           if($i==$minutes)
                                                               $selected= "selected=selected";
                                                           else
                                                               $selected= " ";
                                                           echo "<option ".$selected." value='".$i."'>$i</option>";
                                                       }
                                                       @endphp

                                                   </select>
                                               </div>
                                               <div class="col-md-6">

                                                   <label>Select Seconds</label>
                                                   <select name="slotsSeconds[{{$slots->form_id}}][{{$slots->asset_id}}]">
                                                       <option value="">Select</option>

                                                       @php
                                                       for($i=0;$i<60;$i+=5)
                                                       {
                                                           if($i<10)
                                                               $i="0".$i;

                                                           if($i==$seconds)
                                                           {
                                                               $selected= "selected=selected";
                                                           }else
                                                               $selected= " ";

                                                           echo "<option ".$selected." value='".$i."'>$i</option>";
                                                       }
                                                       @endphp

                                                   </select>
                                               </div>
                                               {{--<input type="text"  onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||--}}
                                               {{--event.charCode == 46 || event.charCode == 0 "  class="form-control slotsTime"   placeholder="Add Slots Time" name="slotsTime[{{$forms->id}}][{{$asset['id']}}]" value="{{$slots_duration}}" />--}}
                                           </td>
                                       </tr>

                                  @php
                                    }
                                             }
                                           }
                                  @endphp

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            @endif
                    </div>
        </div>


    </div>

    </div>
        <div class="col-sm-12">
    <div class="col-sm-2" style="margin-top: 30px;">
    <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$forms->id}}]" @if($answers != null && $answers->isReminder == 1) checked="checked" @endif value="1" /></label>
    </div>
    <div class="col-sm-10 makeRestriction">
    <div class="col-sm-4">
    <label>Select Days</label>


        <select class="form-group" name="reminderDays[{{$forms->id}}]">
    <option @if(isset($answers) && $answers->reminderDays == null) selected="selected" @endif   value="" >Select Days</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '1') selected="selected" @endif value="1">1 Day before</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '2') selected="selected" @endif value="2">2 Day before</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '3') selected="selected" @endif value="3">3 Day before</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '4') selected="selected" @endif value="4">4 Day before</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '5') selected="selected" @endif value="5">5 Day before</option>
    <option @if(isset($answers) && $answers->reminderDays != null && $answers->reminderDays == '6') selected="selected" @endif value="6">6 Day before</option>
    </select>

    </div>

    <div class="col-sm-4">
    <label>Select Hours</label>
    <select class="form-group" name="reminderHours[{{$forms->id}}]">

        <option @if(isset($answers) && $answers->reminderHours == null) selected="selected" @endif  value="" >Select Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '1') selected="selected" @endif  value="1">1 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '2') selected="selected" @endif  value="2">2 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '3') selected="selected" @endif  value="3">3 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '4') selected="selected" @endif  value="4">4 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '5') selected="selected" @endif  value="5">5 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '6') selected="selected" @endif  value="6">6 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '7') selected="selected" @endif  value="7">7 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '8') selected="selected" @endif  value="8">8 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '9') selected="selected" @endif  value="9">9 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '10') selected="selected" @endif  value="10">10 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '11') selected="selected" @endif  value="11">11 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '12') selected="selected" @endif  value="12">12 Hours</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '13') selected="selected" @endif  value="13"> 13 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '14') selected="selected" @endif  value="14"> 14 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '15') selected="selected" @endif  value="15"> 15 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '16') selected="selected" @endif  value="16"> 16 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '17') selected="selected" @endif  value="17"> 17 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '18') selected="selected" @endif  value="18"> 18 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '19') selected="selected" @endif  value="19"> 19 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '20') selected="selected" @endif  value="20"> 20 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '21') selected="selected" @endif  value="21"> 21 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '22') selected="selected" @endif  value="22"> 22 Hour</option>
    <option @if(isset($answers) && $answers->reminderHours != null && $answers->reminderHours == '23') selected="selected" @endif  value="23"> 23 Hour</option>

    </select>
    </div>
    <div class="col-sm-4">
    <label>Select Minutes</label>
        <input @if(isset($answers) && $answers->reminderMinutes != null) value="{{$answers->reminderMinutes}}" @endif type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$forms->id}}]">

    </div>
    </div>
    </div>

    </div>

    </div>
    @endforeach



    @else
    <label>No Forms Associated yet!</label>
    @endif
    </div>
    <div class="create-form">
      <div class="row">
        <div class="col-sm-12">
          <h3>{{post_value_or($m_s_fields,'selectClassTypes','Select Class Types')}}</h3>
          <div class="panel-body table-responsive" style=" max-height: 450px;overflow-y: scroll;">
              @php $showClassType = getShowClassTypes($show->id);
                    $asset_class_type_id = 0;
              @endphp

              <input type="hidden" name="asset_class_type_id" value="{{$asset_class_type_id}}">
              <table class="table table-bordered table-striped display">
                  <thead>
                  <tr><th>{{post_value_or($m_s_fields,'ClassName','Class Name')}}</th><th>{{post_value_or($m_s_fields,'Type','Type')}}</th></tr>
                  </thead>
                  <tbody>
                  @foreach($AllAssets as $asset)
                    <tr>
                      <td>
                         {{GetAssetName($asset)}}            
                      </td>
                      <td>
                      <div class="col-sm-8">
                        <select name="asset_class_type[{{$asset->id}}]" style="width: 75%" class="selectpicker show-tick form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
                             @if(count($AllClassType)>0)
                                <option value=""> Select Any </option>
                                 @foreach($AllClassType as $class)
                                    @if($showClassType && isset($showClassType[$asset->id]))
                                     <option value="{{$class->id}}" {{($class->id == $showClassType[$asset->id])? 'selected':''}}> {{$class->name}}</option>
                                    @else
                                     <option value="{{$class->id}}"> {{$class->name}}</option>
                                    @endif
                                 @endforeach
                             @endif
                         </select>
                        </div>     
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
            
          </div>
          
        </div>
      </div>
    </div>
    </div>
    <div class="col-sm-3">
    {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
    </div>
    {!! Form::close() !!}
    </div>
    </div>
    </div>
    @endforeach
    @else
    <div class="panel panel-default">
    <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#1">
    <h2>{{post_value_or($m_s_fields,'showTitle','Show Title')}}</h2>
    </div>
    <div id="1" class="defaultClasses panel-collapse collapse in">
    <div class="panel-body">
    {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
        <input type="hidden" name="appId" value="{{$appId}}">

    <div class="col-sm-12">

    <div class="row" style="margin-top: 7px;">

    <div class="col-md-4">
    <label><span>{{post_value_or($m_s_fields,'showTitle','Show Title')}} :</span></label>
    <input  type="text" class="form-control" required="required"  name="showTitle" />
    </div>

    <div class="col-md-4" >
    <label><span>{{post_value_or($m_s_fields,'DateFrom','Date From')}} :</span></label>
    <input  type="text"  name="dateFrom" required="" class="form-control datetimepicker8">
    </div>
    <div class="col-md-4" >
    <label><span>{{post_value_or($m_s_fields,'DateTo','Date To')}} :</span></label>
    <input  type="text"  name="dateTo" required="" class="form-control datetimepicker8">
    </div>
    </div>
        <div class="row" style="margin-top: 15px;">


            <div class="col-md-4" >
                <label><span>{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}} :</span></label>
                <select required name="governing_body" class="bootstrap-select" onchange="checkUsef($(this))">
                    <option value="">Select Governing Body</option>
                    <option value="1">USEF</option>
                    <option value="2">Local</option>
                    <option value="3">EC</option>
                </select>
            </div>

            <div class="col-md-4 usef_number" >
                <label><span>{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}} :</span></label>
                {{ Form::text('usef_id','', ['id' => '',
                'placeholder'=>"USEF Competition ID",'required'=>'required' ,'class' => 'form-control usefNo']) }}        </div>
            <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'ShowRating','Show Rating')}}:</span></label>
                <select name="show_type_class" class="selectpicker show-tick form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
                    @if(count($ShowType)>0)
                        @foreach($ShowType as $asset)
                            <option> {{$asset->name}}</option>

                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">

        <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'statetax','State Tax % ')}} :</span></label>
                 {{ Form::number('state_tax', isset($show->state_tax)? $show->state_tax :'', ['id' => '',
                    'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
            </div>
            <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}} :</span></label>
                {{ Form::number('federal_tax', isset($show->federal_tax)? $show->federal_tax :'', ['id' => '',
                    'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
            </div>
            <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'contactinformation','Contact Information:')}} :</span></label>
                {{ Form::text('contact_information', isset($show->contact_information)? $show->contact_information :'', ['id' => '',
                    'placeholder'=>"Add Contact of show",'class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">

        <div class="col-md-4">
    <label><span>{{post_value_or($m_s_fields,'Location','Location')}} :</span></label>

    {{ Form::text('location', isset($location)? $location :'', ['id' => '',
    'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'float: right; width: 87.5%;']) }}
    </div>

    </div>

    <div class="invite-participant-history">
    @if($schedular_forms->count())
    @foreach($schedular_forms as $forms)
    <input type="hidden" name="template_id" value="{{$template_id}}">


    @php
    $restriction = [];
    $answ =  restrictedScheduledDates($forms->id,0);
    $answers = $answ->first();
    if($answers!='')
        {
           $model = $answers->SchedulerRestriction($answers->id,$forms->id);

           if($model)
             $restriction = $model->get()->toArray();
        }

    $key = $forms->id;
    @endphp

    @if($answers!= "")
    @php $key = $answers['id']; @endphp

    <input type="hidden" name="schedual_id[{{$key}}]" value="{{$answers['id']}}">
    @else
    @php $key = $forms->id;@endphp
    @endif
                {{--$assets = checkSchedulerTime($template_id,$forms->id);--}}
    @php
                $requiredClass ='';
                if($answers != null)
                {
                    if($answers->name!='')
                    {
                        $requiredClass = 'required';
                    }
                }
                @endphp

    <input type="hidden" name="form_id[{{$key}}]" value="{{$forms->id}}">

    <div class="create-form">
    <div class="row">
    <div class="col-sm-12">
    <h3>{{$forms->name}}</h3><br>
    <div class="row fields">
    <div class="col-sm-6">
    <label><span>{{post_value_or($m_s_fields,'SchedularName','Schedular Name')}} :</span><input  type="text" class="form-control checkValidation c-control"  @if($answers != null) value="{{$answers['name']}}" @endif name="schedular_name[{{$key}}]" /></label>
    </div>


    <div class="col-sm-12">
    <div class="schedual-restrictions">
        <div class="col-sm-12" style="padding-left: 0px;">
            <label><h3>{{post_value_or($m_s_fields,'MakeRistriction','Make Ristriction')}}</h3></label>
        </div>
     @if($restriction!= null && isset($restriction))
         <div class="adding-restrictions-options">
             @foreach($restriction as $row)

                 @php

                $scoreFromArr = [];
                $assetArr = explode(',',$row['asset_id']);
                if($row['score_from']!='')
                $scoreFromArr = explode(',',$row['score_from']);


                 @endphp

                 <div class="row" style=" margin-bottom: 15px;">
                     <div class="col-sm-12 ClassAssets">
                         <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Select Class')}} :</span></label>
                         <select {{$requiredClass}} multiple name="assets[{{$key}}][{{$row->id}}][]" title=" --- Select Classes --- " data-id="{{$key}}" style="width: 75%" class="selectpicker show-tick form-control allAssets" multiple data-size="8" data-selected-text-format="count>6"  data-live-search="true">
                             @if($AllAssets->count()>0)
                                 @foreach($AllAssets as $asset)
                                     <option value="{{$asset->id}}" {{ (collect(old('assets['.$key.']['.$row->id.']',$assetArr))->contains($asset->id)) ? 'selected':'' }} > {{GetAssetName($asset)}}</option>
                                 @endforeach
                             @endif
                         </select>

                     </div>
                     <div class="col-sm-12 ClassAssets">
                         <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Score From')}} :</span></label>
                         <select multiple name="score_from[{{$key}}][{{$row->id}}][]" title=" --- Select Classes --- " data-id="{{$key}}" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  data-live-search="true">
                             @if($AllAssets->count()>0)
                                 @foreach($AllAssets as $asset)
                                     <option value="{{$asset->id}}" {{ (collect(old('score_from['.$key.']['.$row->id.']',$scoreFromArr))->contains($asset->id)) ? 'selected':'' }} > {{GetAssetName($asset)}}</option>
                                 @endforeach
                             @endif
                         </select>

                     </div>

                     <div class="col-sm-8" style="margin-top: 10px;">
                         <label><span>{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</span>
                             <input {{$requiredClass}} type="text" class="daterange form-control datetime-control"
                                    placeholder="Add Date" name="datetimeschedual[{{$key}}][{{$row->id}}]"
                                    value="{{$row['restriction']}}" /></label>
                         <label><span>{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</span>
                             <input type="text" class="daterange form-control"
                                    placeholder="Add Block Time" name="blockTime[{{$key}}][{{$row->id}}]"
                                    value="{{$row['block_time']}}" /></label>
                         <label><span>{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</span> <input type="text" class="form-control"  placeholder="Add Title Of Block Time" name="blockTimeTitle[{{$key}}][{{$row->id}}]" value="{{$row['block_time_title']}}" /></label>
                         <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span> <input @if($row['is_multiple_selection'] == 1) {{'checked'}} @endif type="checkbox" class="form-control" name="multipleSelection[{{$forms->id}}][{{$row->id}}]" value="" /></label>

                         <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;">
                             <label>
                                 <span style="padding-top: 0px;">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Rider to Book Rides')}}</span>
                                 <input @if($row['is_rider_restricted'] == 1) {{'checked'}} @endif  type="checkbox" class="form-control" name="restrictRiders[{{$forms->id}}][{{$row->id}}]" value="" /></label>
                         </div>

                     </div>

                 </div>
             @endforeach
         </div>
     @else
         <div class="adding-restrictions-options">
             <div class="row dateCon">
                 <div class="col-sm-12 ClassAssets">
                     <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Select Class')}} :</span></label>
                     <select {{$requiredClass}} multiple name="assets[{{$key}}][1][]" title=" --- Select Classes --- "  data-id="{{$key}}" style="width: 75%" class="selectpicker show-tick form-control allAssets" data-size="8" data-selected-text-format="count>6"   data-live-search="true">
                         @if($AllAssets->count()>0)
                             @foreach($AllAssets as $asset)
                                 <option value="{{$asset->id}}" @if(old("assets") != null) {{ (in_array($asset->id, old("asset")) ? "selected":"") }} @endif> {{GetAssetName($asset)}}</option>
                             @endforeach
                         @endif
                     </select>

                 </div>
                 <div class="col-sm-12 ClassAssets">
                     <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Score From')}} :</span></label>
                     <select  multiple name="score_from[{{$key}}][1][]" title=" --- Select Classes --- "  data-id="{{$key}}" style="width: 75%" class="selectpicker show-tick form-control" data-size="8" data-selected-text-format="count>6"   data-live-search="true">
                         @if($AllAssets->count()>0)
                             @foreach($AllAssets as $asset)
                                 <option value="{{$asset->id}}" @if(old("assets") != null) {{ (in_array($asset->id, old("asset")) ? "selected":"") }} @endif> {{GetAssetName($asset)}}</option>
                             @endforeach
                         @endif
                     </select>

                 </div>
                 <div class="col-sm-8" style="margin-top: 10px;">
                     <label><span>{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</span> <input {{$requiredClass}} type="text"  class="daterange form-control datetime-control"
                      placeholder="Add Date" name="datetimeschedual[{{$key}}][1]" value="" /></label>

                     <label><span>{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</span> <input type="text"  class="daterange form-control"
                                                                     placeholder="Add Date" name="blockTime[{{$key}}][1]" value="" /></label>
                     <label><span>{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</span> <input type="text" class="form-control"  placeholder="Add Title Of Block Time" name="blockTimeTitle[{{$key}}][1]" value="" /></label>
                     <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span> <input type="checkbox" class="form-control"  name="multipleSelection[{{$forms->id}}][1]" value="" /></label>


                     <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;">
                         <label>
                             <span style="padding-top: 0px;">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Rider to Book Rides')}}</span>
                             <input type="checkbox" class="form-control" name="restrictRiders[{{$forms->id}}][1]" value="" /></label>
                     </div>


                 </div>


             </div>
         </div>
     @endif

     <div class="row">
         <div class="col-sm-8">
             <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset{{$key}}">
             <input type="button" data-id="{{$key}}" class="btn btn-success add-more-restriction" value="Add More">
         </div>
     </div>
    </div>
    </div>

        <div class="col-sm-12 slotsContainer">
                <div class="col-sm-12" style="padding-left: 0px;">
                    <label><h3>{{post_value_or($m_s_fields,'Slots Duration of Classes','SlotsDurationOfClasses')}}</h3></label>
                </div>

                <div class="adding-restrictions-options">
                    @if(count($assets)>0)
                        <div class="row" style=" margin-bottom: 15px;">
                            <div class="panel-body table-responsive" style="max-height: 450px;overflow-y: scroll;">

                                <table  class="table table-bordered table-striped display">
                                    <thead>
                                    <tr><th>{{post_value_or($m_s_fields,'Class','Class')}}</th><th>{{post_value_or($m_s_fields,'SlotsDuration','Slots Duration')}}</th></tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
    </div>
    </div>
    <div class="col-sm-12">
    <div class="col-sm-2" style="margin-top: 30px;">
    <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$key}}]" @if($answers != null && $answers["isReminder"] == 1) checked="checked" @endif value="1" /></label>
    </div>
    <div class="col-sm-10 makeRestriction">
    <div class="col-sm-4">
    <label>Select Days</label>
    <select class="form-group" name="reminderDays[{{$key}}]">
     <option @if($answers == null) selected="selected" @endif   value="" >Select Days</option>
     <option @if($answers != null && $answers["reminderDays"] == '1') selected="selected" @endif value="1">1 Day before</option>
     <option @if($answers != null && $answers["reminderDays"] == '2') selected="selected" @endif value="2">2 Day before</option>
     <option @if($answers != null && $answers["reminderDays"] == '3') selected="selected" @endif value="3">3 Day before</option>
     <option @if($answers != null && $answers["reminderDays"] == '4') selected="selected" @endif value="4">4 Day before</option>
     <option @if($answers != null && $answers["reminderDays"] == '5') selected="selected" @endif value="5">5 Day before</option>
     <option @if($answers != null && $answers["reminderDays"] == '6') selected="selected" @endif value="6">6 Day before</option>
    </select>
    </div>

    <div class="col-sm-4">
    <label>Select Hours</label>
    <select class="form-group" name="reminderHours[{{$key}}]">
     <option @if($answers == null) selected="selected" @endif  value="" >Select Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '1') selected="selected" @endif  value="1">1 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '2') selected="selected" @endif  value="2">2 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '3') selected="selected" @endif  value="3">3 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '4') selected="selected" @endif  value="4">4 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '5') selected="selected" @endif  value="5">5 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '6') selected="selected" @endif  value="6">6 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '7') selected="selected" @endif  value="7">7 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '8') selected="selected" @endif  value="8">8 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '9') selected="selected" @endif  value="9">9 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '10') selected="selected" @endif  value="10">10 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '11') selected="selected" @endif  value="11">11 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '12') selected="selected" @endif  value="12">12 Hours</option>
     <option @if($answers != null && $answers["reminderHours"] == '13') selected="selected" @endif  value="13"> 13 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '14') selected="selected" @endif  value="14"> 14 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '15') selected="selected" @endif  value="15"> 15 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '16') selected="selected" @endif  value="16"> 16 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '17') selected="selected" @endif  value="17"> 17 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '18') selected="selected" @endif  value="18"> 18 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '19') selected="selected" @endif  value="19"> 19 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '20') selected="selected" @endif  value="20"> 20 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '21') selected="selected" @endif  value="21"> 21 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '22') selected="selected" @endif  value="22"> 22 Hour</option>
     <option @if($answers != null && $answers["reminderHours"] == '23') selected="selected" @endif  value="23"> 23 Hour</option>

    </select>
    </div>
    <div class="col-sm-4">
    <label>Select Minutes</label>
    <input @if($answers != null) value="{{$answers["reminderMinutes"]}}" @endif type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$key}}]">
    </div>
    </div>
    </div>
    </div>
    </div>

    @endforeach
    @else
    <label>No Forms Associated yet!</label>
    @endif
    </div>
    </div>
    <div class="col-sm-3">
    {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
    </div>
    {!! Form::close() !!}
    </div>
    </div>
    </div>
    @endif
    <div class="hide addMoreCon" style="margin-top: 10px; position: relative">

    <div class="panel panel-default addMoreScheduler">
    <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="">
    <h2>{{post_value_or($m_s_fields,'showTitle','Show Title')}}</h2>
    </div>
    <div class="panel-collapse collapse">
    <div class="panel-body">
    {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
        <input type="hidden" name="appId" value="{{$appId}}">

    <div class="col-sm-12">

    <div class="row" style="margin-top: 7px;">

    <div class="col-md-4">
    <label><span>{{post_value_or($m_s_fields,'showTitle','Show Title')}} :</span></label>
    <input  type="text" class="form-control c-control" required="required"  name="showTitle" />
    </div>

    <div class="col-md-4" >
    <label><span>{{post_value_or($m_s_fields,'DateFrom','Date From')}} :</span></label>
    <input  type="text"  name="dateFrom" required="" class="form-control datetimepicker8 c-control">
    </div>
    <div class="col-md-4" >
    <label><span>{{post_value_or($m_s_fields,'DateTo','Date To')}} :</span></label>
    <input  type="text"  name="dateTo" required="" class="form-control datetimepicker8 c-control">
    </div>
    </div>
        <div class="row" style="margin-top: 15px;">

            <div class="col-md-4" >
                <label><span>{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}} :</span></label>
                <select required name="governing_body" class="bootstrap-select" onchange="checkUsef($(this))">
                    <option value="">Select Governing Body</option>
                    <option value="1">USEF</option>
                    <option value="2">Local</option>
                    <option value="3">EC</option>
                </select>
            </div>


            <div class="col-md-4 usef_number" >
                <label><span>{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}} :</span></label>
                {{ Form::text('usef_id','', ['id' => '',
                'placeholder'=>"USEF Competition ID",'required'=>'required' ,'class' => 'form-control usefNo']) }}        </div>
    <div class="col-md-4">
      <label><span>{{post_value_or($m_s_fields,'Show Rating','Show Rating')}} :</span></label>
      <select name="show_type_class" style="width: 75%" class="selectpicker show-tick form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
           @if(count($ShowType)>0)
               @foreach($ShowType as $asset)
                   <option value="{{$asset->id}}"> {{$asset->name}}</option>
                   
               @endforeach
           @endif
       </select>
    </div>
        </div>
        <div class="row" style="margin-top: 15px;">

        <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'statetax','State Tax % ')}} :</span></label>
                 {{ Form::number('state_tax', isset($show->state_tax)? $show->state_tax :'', ['id' => '',
                    'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
            </div>
            <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}} :</span></label>
                {{ Form::number('federal_tax', isset($show->federal_tax)? $show->federal_tax :'', ['id' => '',
                    'placeholder'=>"% of Total",'step'=>'any' ,'class' => 'form-control','style'=>'']) }}
            </div>
            <div class="col-md-4">
                <label><span>{{post_value_or($m_s_fields,'contactinformation','Contact Information:')}} :</span></label>
                {{ Form::text('contact_information', isset($show->contact_information)? $show->contact_information :'', ['id' => '',
                    'placeholder'=>"Add Contact of show",'class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">

    <div class="col-md-6">
        <label><span>{{post_value_or($m_s_fields,'Location','Location')}} :</span></label>

        {{ Form::text('location', isset($location)? $location :'', ['id' => '',
        'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'float: right; width: 87.5%;']) }}
    </div>


    </div>

    <div class="invite-participant-history">
    @if($schedular_forms->count())
    @foreach($schedular_forms as $forms)
    <input type="hidden" name="template_id" value="{{$template_id}}">
    <input type="hidden" name="form_id[{{$forms->id}}]" value="{{$forms->id}}">
    @php $answers =  restrictedScheduledDates($forms->id,0);@endphp
    <div class="create-form">
    <div class="row">
    <div class="col-sm-12">
    <h3>{{$forms->name}}</h3><br>
    <div class="row fields">
    <div class="col-sm-6">
    <label><span>{{post_value_or($m_s_fields,'Schedular Name','SchedularName')}} :</span><input  type="text" class="form-control checkValidation c-control"   name="schedular_name[{{$forms->id}}]" /></label>
    </div>

    <div class="col-sm-12">
    <div class="schedual-restrictions">
        <div class="col-sm-12" style="padding-left: 0px;">
            <label><h3>{{post_value_or($m_s_fields,'MakeRistriction','Make Ristriction')}}</h3></label>
        </div>
     <div class="adding-restrictions-options">

         <div class="row" style=" margin-bottom: 15px;">
             <div class="col-sm-12 ClassAssets">
                 <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Select Class')}}:</span></label>
                 <select multiple name="assets[{{$forms->id}}][1][]" title=" --- Select Classes --- " style="width: 75%" data-id="{{$forms->id}}" class="selectpicker show-tick form-control allAssets" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                     @if($AllAssets->count()>0)
                         @foreach($AllAssets as $asset)
                             <option value="{{$asset->id}}" @if(old("asset") != null) {{ (in_array($asset->id, old("asset")) ? "selected":"") }} @endif> {{GetAssetName($asset)}}</option>
                         @endforeach
                     @endif
                 </select>

             </div>

             <div class="col-sm-12 ClassAssets">
                 <label style="padding-top:5px"><span>{{post_value_or($m_s_fields,'selectClass','Score From')}}:</span></label>
                 <select multiple name="score_from[{{$forms->id}}][1][]" title=" --- Select Classes --- " style="width: 75%" data-id="{{$forms->id}}" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                     @if($AllAssets->count()>0)
                         @foreach($AllAssets as $asset)
                             <option value="{{$asset->id}}" @if(old("asset") != null) {{ (in_array($asset->id, old("asset")) ? "selected":"") }} @endif> {{GetAssetName($asset)}}</option>
                         @endforeach
                     @endif
                 </select>

             </div>

             <div class="col-sm-8" style="margin-top: 10px;">
                 <label><span>{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</span> <input type="text" class="daterange form-control datetime-control" placeholder="Add Date" name="datetimeschedual[{{$forms->id}}][1]" value="" /></label>
                 <label><span>{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</span> <input type="text"  class="daterange form-control"
                                                              placeholder="Add Block Time" name="blockTime[{{$forms->id}}][1]" value="" /></label>
                 <label><span>{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</span> <input type="text" class="form-control"  placeholder="Add Title Of Block Time" name="blockTimeTitle[{{$forms->id}}][1]" value="" /></label>
                 <label><span style="padding-top: 0px;">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span> <input type="checkbox" class="form-control"  name="multipleSelection[{{$forms->id}}][1]" value="" /></label>

                 <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;">
                     <label>
                         <span style="padding-top: 0px;">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Rider to Book Rides')}}</span>
                         <input type="checkbox" class="form-control" name="restrictRiders[{{$forms->id}}][1]" value="" /></label>
                 </div>


             </div>

         </div>
     </div>
    <div class="row">
     <div class="col-sm-8">
         <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset{{$forms->id}}">
         <input type="button" data-id="{{$forms->id}}" class="btn btn-success add-more-restriction" value="Add More">
     </div>
    </div>

    </div>

    </div>

        <div class="col-sm-12 slotsContainer">
                <div class="col-sm-12" style="padding-left: 0px;">
                    <label><h3>{{post_value_or($m_s_fields,'SlotsDurationOfClasses','Slots Duration of Classes')}}</h3></label>
                </div>

                <div class="adding-restrictions-options">
                    @if(count($assets)>0)
                        <div class="row" style=" margin-bottom: 15px;">
                            <div class="panel-body table-responsive" style="max-height: 450px;overflow-y: scroll;">

                                <table  class="table table-bordered table-striped display">

                                    <tbody>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
        </div>

    </div>

    <div class="col-sm-12">
    <div class="col-sm-2" style="margin-top: 30px;">
    <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$forms->id}}]" value="1" /></label>
    </div>
    <div class="col-sm-10 makeRestriction">
    <div class="col-sm-4">
    <label>Select Days</label>
    <select class="form-group" name="reminderDays[{{$forms->id}}]">
    <option  value="" >Select Days</option>
    <option  value="1">1 Day before</option>
    <option   value="2">2 Day before</option>
    <option  value="3">3 Day before</option>
    <option  value="4">4 Day before</option>
    <option   value="5">5 Day before</option>
    <option   value="6">6 Day before</option>
    </select>
    </div>

    <div class="col-sm-4">
    <label>Select Hours</label>
    <select class="form-group" name="reminderHours[{{$forms->id}}]">
    <option   value="" >Select Hours</option>
    <option value="1">1 Hour</option>
    <option  value="2">2 Hours</option>
    <option  value="3">3 Hours</option>
    <option value="4">4 Hours</option>
    <option   value="5">5 Hours</option>
    <option   value="6">6 Hours</option>
    <option   value="7">7 Hours</option>
    <option  value="8">8 Hours</option>
    <option  value="9">9 Hours</option>
    <option  value="10">10 Hours</option>
    <option  value="11">11 Hours</option>
    <option   value="12">12 Hours</option>
    <option   value="13"> 13 Hour</option>
    <option  value="14"> 14 Hour</option>
    <option  value="15"> 15 Hour</option>
    <option  value="16"> 16 Hour</option>
    <option  value="17"> 17 Hour</option>
    <option  value="18"> 18 Hour</option>
    <option  value="19"> 19 Hour</option>
    <option  value="20"> 20 Hour</option>
    <option  value="21"> 21 Hour</option>
    <option  value="22"> 22 Hour</option>
    <option   value="23"> 23 Hour</option>

    </select>
    </div>
    <div class="col-sm-4">
    <label>Select Minutes</label>
    <input   type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$forms->id}}]">
    </div>
    </div>
    </div>

    </div>
    </div>
    </div>
    @endforeach
    @else
    <label>No Forms Associated yet!</label>
    @endif

    </div>
    <div class="col-sm-3">
    {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
    </div>
    {!! Form::close() !!}
    </div>
    </div>
    </div>
    <a class="removeScheduler" href="javascript:" style="position: absolute; top: 0px; right: -16px;">
    <span class="glyphicon glyphicon-remove"></span>
    </a>
    </div>
    </div>

    </div>

    @endsection
    @section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/schedular-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/schedual.js') }}"></script>

    <script src="{{ asset('/js/google-map-script-search.js') }}"></script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWp7OvMOkqzMjDTNHDstANUQatmbuWyWo&libraries=places&callback=initialize"
    async defer></script>

    <style>
    .adding-restrictions-options .bootstrap-select{ width: 90%!important;}

    /*.table-responsive {*/
        /*max-height:200px;*/
    /*}*/
    .slotsContainer{ margin-top: 20px; margin-bottom: 10px; width: 90%}

    .invite-participant-history #DataTables_Table_0_filter {
        display: block!important;
    }
    .dataTables_filter {
        float: right !important;
    }
    .dataTables_scroll
    {
        overflow:auto;
    }
    .daterange.form-control
    {
        float: right;
        width: 440px !important;

    }
    .dropdown-menu{
        overflow: initial !important;
    }

    </style>
    @endsection
