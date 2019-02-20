    @extends('layouts.equetica2')

    @section('custom-htmlheader')
    <!-- Search populate select multiple-->
    {{--<link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>
    {{--<link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />--}}

    <!-- END:Search populate select multiple-->
    @endsection

    @section('main-content')

        @php $m_s_fields = getButtonLabelFromTemplateId($template_id,'m_s_fields');
        $templateType = GetTemplateType($template_id);
        $timeZoneArr = config('timeZone.name');

      foreach ($AllAssets as $asset)
            {
            $assets[] =  array('name'=>GetAssetName($asset),'id'=>$asset->id);
            }
       @endphp
<div class="container-fluid">

        @php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); @endphp
    <div class="page-menu">

        <div class="row">
            <div class="col left-panel">
                <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg')}}" />
                    </span>
                    <h1 class="title flex-shrink-1">{{post_value_or($ya_fields,'manage_scheduler','Schedular')}}
                        <small>{!! Breadcrumbs::render('master-template-breadcrumbs-list-schedular', $template_id, nxb_encode($appId)) !!}</small>
                    </h1>
                </div>
            </div>
            <div class="right-panel">
                {{--<div class="desktop-view">--}}
                    {{--<form class="form-inline justify-content-end">--}}
                       {{--<div class="search-field mr-10">--}}
                            {{--<div class="input-group">--}}
                                {{--<input  class="form-control"  type="text" placeholder="Search By Name, Date, Location etc ..." id="mySearchTerm">--}}
                                {{--<div class="input-group-prepend">--}}
                                    {{--<span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}" /></span>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
                <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>

                    {{--<div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">--}}
                        {{--<form class="form-inline justify-content-end">--}}

                            {{--<div class="search-field">--}}
                                {{--<div class="input-group">--}}
                                    {{--<input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">--}}
                                    {{--<div class="input-group-prepend">--}}
                                        {{--<span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg')}}" /></span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}

                    {{--</div>--}}
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="info text-center col-md-12 mt-10">
        @if(Session::has('message'))
        <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
        {{ Session::get('message') }}
        </div>
        @endif
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">

    <div class="col-md-12 mt-30">
    <button type="button" style="float: right;" class="btn btn-primary mr-30 AddMoreSch">{{post_value_or($m_s_fields,'AddSchedularBTN','Add Schedular')}}</button>
    </div>

    </div>
    <div class="row" style="padding: 0px 15px;">

    <!-- Accordion START -->
<div class="col-md-12">
        <div class="accordion-light" id="accordion" role="tablist" aria-multiselectable="true">
        {{--<div class="panel-group" id="accordion">--}}
    @if($manageShows->count()>0)
    @foreach($manageShows as $show)
       @php $divisionId = [];@endphp
       @foreach($show->division as $div)
       @php $divisionId[]=$div->pivot->division_id;@endphp
       @endforeach
        <div class="slide-holder">
            <h5 class="card-header">
                <a class="d-block title collapsed" data-toggle="collapse" href="#{{$show->id}}" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                    @if($show->title != null) {{$show->title}} @else {{post_value_or($m_s_fields,'showTitle','Show Name')}} @endif
                </a>
            </h5>
            <div id="{{$show->id}}" class="collapse" aria-labelledby="heading-example">
            <div class="card-body">
            {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
           <input type="hidden" name="appId" value="{{$appId}}">
            <input type="hidden" value="{{$template_id}}" name="template_id"  id="template_id" class="template_id">
                <input type="hidden" value="{{$fromPage}}" name="fromPage"  id="fromPage" class="fromPage">

                <div class="col-sm-12">
            <div class="row">
            <div class="col-md-3">
                <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'showTitle','Show Name')}}</label>
                    <input required="required"  name="showTitle" @if($show->title != null) value="{{$show->title}}" @endif type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateFrom','Date From')}}</label>
                    <input required="required"  name="dateFrom" @if($show->date_from != null) value="{{date('m/d/Y',strtotime($show->date_from))}}" @endif type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                </fieldset>
            </div>
            <div class="col-md-3">
                    <fieldset class="form-group">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateTo','Date To')}}</label>
                        <input required="required"  name="dateTo" @if($show->date_to != null) value="{{date('m/d/Y', strtotime($show->date_to))}}" @endif type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                    </fieldset>
                </div>
            <div class="col-md-3">
                    <fieldset class="form-group">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'contactinformation','Contact Information')}}</label>
                        <input   name="contact_information" @if($show->contact_information != null) value="{{$show->contact_information}}" @endif type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                    </fieldset>
                </div>
            </div>
            <div class="row">

                @if($templateType==SHOW)

                <div class="col-md-3">
                    <fieldset class="form-group select-bottom-line-only">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}}</label>
                        <select required name="governing_body" class="form-control form-control-bb-only" onchange="checkUsef($(this))">
                            <option value="">Select Governing Body</option>
                            <option @if($show->governing_body=='USEF') selected="selected" @endif value="USEF">USEF</option>
                            <option @if($show->governing_body=='Local') selected="selected" @endif value="Local">Local</option>
                            <option @if($show->governing_body=='EC') selected="selected" @endif value="EC">EC</option>
                        </select>
                    </fieldset>
                </div>

                <div class="col-md-3">
                    <fieldset class="form-group">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}}</label>
                        <input required="required"  name="usef_id" value="{{isset($show->usef_id)? $show->usef_id :''}}"  type="text" class="form-control form-control-bb-only usefNo" id="" placeholder="USEF Competition ID">
                    </fieldset>
                </div>

                <div class="col-md-3">
                    <fieldset class="form-group select-bottom-line-only">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowRating','Show Rating')}}</label>
                        <select name="show_type_class" class="show-tick form-control form-control-bb-only" data-live-search="true">
                            @if(count($ShowType)>0)
                                @foreach($ShowType as $asset)
                                    <option value="{{$asset->id}}" {{($asset->id == $show->show_type_id)? 'selected':''}}> {{$asset->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </fieldset>
                </div>
                @endif
            </div>
            <div class="row">

                @if($templateType==SHOW || $templateType==TRAINER )
                <div class="col-md-3">

                <fieldset class="form-group select-bottom-line-only">
                <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'show_type','Show Type')}}</label>
                <select  required name="show_type" class="form-control form-control-bb-only">
                    <option value="">Select Show Type</option>
                    <option @if($show->show_type=='Dressage') selected="selected" @endif value="Dressage">Dressage</option>
                    <option @if($show->show_type=='Hunter') selected="selected" @endif value="Hunter">Hunter/Jumper</option>
                    <option @if($show->show_type=='Eventing') selected="selected" @endif value="Eventing">Eventing</option>
                    <option @if($show->show_type=='Western') selected="selected" @endif value="Western">Western</option>
                    <option @if($show->show_type=='Breeding') selected="selected" @endif value="Breeding">Breeding</option>
                </select>
                </fieldset>
                </div>
                <div class="col-md-3">
                <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'statetax','State Tax % ')}}</label>
                    <input required="required"  name="state_tax"  value="{{isset($show->state_tax)? $show->state_tax :''}}" type="text" class="form-control form-control-bb-only" id="" placeholder="% of Total">
                </fieldset>
                </div>
                <div class="col-md-3">
                <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}}</label>
                    <input required="required"  name="federal_tax" value="{{isset($show->federal_tax)? $show->federal_tax :''}}"  type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                </fieldset>
                </div>
                @endif

                    <div class="col-md-3">
                        <fieldset class="form-group select-bottom-line-only">
                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'TimeZone','Time Zone')}}</label>
                            <select name="time_zone" title=" --- Select Time Zone --- "   class="form-control-bb-only show-tick form-control" data-live-search="true">
                                @if($timeZoneArr>0)
                                    @foreach($timeZoneArr as $time=>$zone)
                                        <option  value="{{$time}}" {{($time == $show->time_zone)? 'selected':''}}>{{$zone}} </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>

                </div>
            <div class="row">
                <div class="col-md-6  map-location"  initialize="false">
                    <fieldset class="form-group">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Location','Location')}}</label>
                        <input required="required"  name="location"  value="{{isset($show->location)? $show->location :''}}" type="text" class="form-control form-control-bb-only location allow-copy" autocomplete="off" id="search_input_1" placeholder="Enter Location">
                    </fieldset>
                </div>
                @if($templateType==SHOW)
                <div class="col-md-6">
                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'division','Select Division')}}</label>
                        <select  multiple name="divisions[]" title=" --- Select Division --- "   class="selectpicker form-control-bb-only show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                            @if($divisions->count()>0)
                                @foreach($divisions as $div)
                                    <option {{ (collect(old('divisions[]',$divisionId))->contains($div->id)) ? 'selected':'' }} value="{{$div->id}}" > {{GetAssetName($div)}}</option>
                                @endforeach
                            @endif

                        </select>
                    <div class="col-md-12"><span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Add all classes of selected division(s) as part of your show by clicking on 'Add Classes' button.</span>
                    </div>
                </div>
                @endif
            </div>
                    @if($templateType==SHOW || $templateType==TRAINER )
                    <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowDescription','Show Description')}} </label>
                    <textarea maxlength="240" name="show_description" class="form-control form-control-bb-only">{{($show->show_description)?$show->show_description:''}}</textarea>
                    </fieldset>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                    <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowInfoOnInvoice','Info On Invoice')}} </label>
                    <textarea maxlength="240" name="info_on_invoice" class="form-control form-control-bb-only">{{($show->info_on_invoice)?$show->info_on_invoice:''}}</textarea>
                    </fieldset>
                    </div>
                    </div>
                    @endif

                    <div class="row">
                <div class="col-sm-12 text-right mb-20">
                    @if($templateType==SHOW || $templateType==TRAINER )
                    <div>
                    <a href="{{ url('/master-template').'/'.nxb_encode($show->id).'/class-price' }}" target="_blank" class="ml-20 btn btn-small btn-primary pull-right"> {{post_value_or($m_s_fields,'AddPricesClasses','Add Prices For Classes/Divisions')}}</a>
                </div>
                    @endif



                    <input type="hidden" name="template_id" value="{{$template_id}}">
                    <input type="hidden" class="show_id" name="show_id" @if($show->id != null) value="{{$show->id}}" @endif>
                    <div>
                    <input type="submit" value="{{post_value_or($m_s_fields,'SaveSchedularBTN','Save Schedular')}}" class="btn btn-small btn-secondary">
                </div>
                </div>
            </div>

                @if(isset($show))

            @if($templateType!=TRAINER)
                <div class="invite-participant-history">
    @if($schedular_forms->count())
    @foreach($schedular_forms as $forms)
    @php
     $restriction = [];

     $res =  restrictedScheduledDates($forms->id,$show->id);
    $answers = $res->first();
    if($answers!='' && $show->id!='')
    $restriction = $answers->SchedulerRestriction($answers->id,$forms->id,$show->id)->get()->toArray();

    $requiredClass = '';

    if($answers != null)
    {
      if($answers->name!='')
       $requiredClass = 'required';
    }
    @endphp

    <input type="hidden" name="form_id[{{$forms->id}}]" value="{{$forms->id}}">
    <input type="hidden" name="dateChangeCon" class="dateChangeCon">

    @if($answers!='')
    <input type="hidden" name="schedual_id[{{$forms->id}}]" value="{{$answers->id}}">
    @else
     <input type="hidden" name="schedual_id[{{$forms->id}}]" value="">
    @endif
    <div class="row">
       <div class="col-md-12">
           <div class="box-gradient-top p-3">
         <h3 class="text-secondary">{{$forms->name}}{{$templateType}}</h3>
    <div class="row">
    <div class="col-sm-4">
        <fieldset class="form-inline border-bottom">
            <label class="pr-10">{{post_value_or($m_s_fields,'SchedularName','Schedular Name')}}</label>
            <input  name="schedular_name[{{$forms->id}}]" @if($answers != null) value="{{$answers->name}}" @endif type="text" class="form-control bg-transparent border-0 checkValidation" id="" placeholder="Enter Value">
        </fieldset>
    </div>
        <div class="col-sm-offset-4 col-sm-2 text-center">
        @if($answers != null)
        <a class="btn btn-primary call-to-add-class" href="javascript:" data-show-type="{{$show->show_type}}" onclick="addRestrictions('{{$answers->id}}','{{$show->id}}','{{$forms->id}}')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
        @else
                <a class="btn btn-primary" href="javascript:" style="float: right" onclick="alertBox('Please enter scheduler name and then save show before adding classes.')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
        @endif
        </div>
    </div>

    <div class="row">
       <div class="schedual-restrictions col-md-12 pb-50 scheduler_con_{{$show->id}}_{{$forms->id}}">
             @php $count = 0; @endphp
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

                    <div class="row scheduler-{{$row['scheduler_key']}} schedulerCon {{(++$count%2 ? "schOdd" : "schEeven") }} ">

                        <div class="col-md-12 pull-right mt-20" >
                            <a class="pull-right ml-20 edit-show-popup" href="javascript:void(0)" data-show-type="{{$show->show_type}}" onclick="editScheduler('{{$row['scheduler_key']}}','{{$show->id}}','{{$forms->id}}')" ><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a class="pull-right" href="javascript:void(0)" onclick="deleteScheduler($(this),'{{$row['scheduler_key']}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-line-braker mt-10 custom-responsive-md">
                                <tbody>
                                <tr>
                                    <td width="180" scope="row">
                                        {{post_value_or($m_s_fields,'selectClass','Classes')}}
                                    </td>
                                    <td class="pl-0" width="400">
                                        {!! getClassNames($row['asset_id']) !!}
                                    </td>

                                    @if($templateType==SHOW)
                                    <td  class="pl-0" width="180">
                                        {{post_value_or($m_s_fields,'selectScoreClass','Score From')}}
                                    </td>
                                    <td  class="pl-0" width="400">
                                        {!!($row['score_from'] ? getClassNames($row['score_from']) : "N/A")  !!}
                                    </td>
                                     @endif
                                </tr>

                                <tr>

                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectDateAndTime','Scheduler Time')}}</td>
                                    <td class="pl-0" width="400">{{($row['restriction'] ? $row['restriction'] : "N/A")}}</td>
                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectBlockTime','Block Time')}}</td>
                                    <td class="pl-0" width="400">{{($row['block_time'] ? $row['block_time'] : "N/A")}}</td>
                                </tr>
                                <tr>

                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</td>
                                    <td class="pl-0" width="400">{{($row['block_time_title'] ? $row['block_time_title'] : "N/A")}}</td>
                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Rider restrcited to Book Rides?')}}</td>
                                    <td class="pl-0" width="400">{{ ($row['is_rider_restricted']==1) ? 'Yes' : "N/A" }}</td>


                                </tr>
                                @if($templateType==TRAINER || $templateType==SHOW)

                                    <tr>

                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</td>
                                    <td class="pl-0" width="400">{{ ($row['is_multiple_selection']==1) ? 'Yes' : "N/A" }}</td>
                                    <td class="pl-0" width="180">Qualifying</td>
                                    <td class="pl-0" width="400">{{ ($row['qualifing_check']==1) ? "($)".$row['qualifing_price'] : "N/A" }}</td>


                                </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                <hr class="hr-dark hr-thik">
        @endforeach

    </div>
        <div class="col-sm-12 slotsContainer  slots_con_{{$show->id}}_{{$forms->id}}">
                   <h3>{{post_value_or($m_s_fields,'SlotsDurationOfClasses','Slots Duration of Classes')}}</h3>
          <div class="adding-restrictions-options">
              @if(isset($assets))
                @if(count($assets)>0)
                 <div class="row" style=" margin-bottom: 15px;">
                                        <div class="table-responsive" style=" max-height: 450px;overflow-y: scroll;">

                                       <table class="table table-line-braker mt-10 custom-responsive-md slot_con_{{$show->id}}_{{$forms->id}}">
                                        <thead>
                                        <tr><th>{{post_value_or($m_s_fields,'Class','Class')}}</th><th>{{post_value_or($m_s_fields,'Slots Duration','Slots Duration')}}</th></tr></thead>
                                        <tbody>
                                       @php $serial = $loop->index + 1;
                                            $slots_duration = '';
                                            $minutes= '';
                                            $seconds= '';
                                            $selected = '';
                                            $slotsArr = [];
                                            @endphp
                                        @if($answers!='')
                                           @php
                                             $slotsContainer = $answers->SchedulerSlots($answers->id,$forms->id,$show->id);
                                           @endphp
                                           @if($slotsContainer)
                                               @foreach ($slotsContainer as $slots)
                                                @php
                                                    $minutes ='';
                                                    $seconds ='';
                                                    $selected='';
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
                                                       <div class="col-md-3 pull-left">
                                                           <fieldset class="form-group select-bottom-line-only">
                                                           <select autocomplete="off" class="form-inline form-control-bb-only" name="slotsMinutes[{{$slots->form_id}}][{{$slots->asset_id}}]">
                                                               <option value="">Select Minutes</option>
                                                               @php
                                                               for($i=1;$i<=60;$i++)
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
                                                           </fieldset>
                                                       </div>
                                                       <div class="col-md-3 pull-left">

                                                           <fieldset class="form-group select-bottom-line-only">
                                                           <select autocomplete="off" class="form-inline form-control-bb-only" name="slotsSeconds[{{$slots->form_id}}][{{$slots->asset_id}}]">
                                                               <option value="">Select Seconds</option>

                                                               @php
                                                               for($i=0;$i<=60;$i+=5)
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
                                                           </fieldset>
                                                       </div>
                                                       {{--<input type="text"  onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ||--}}
                                                       {{--event.charCode == 46 || event.charCode == 0 "  class="form-control slotsTime"   placeholder="Add Slots Time" name="slotsTime[{{$forms->id}}][{{$asset['id']}}]" value="{{$slots_duration}}" />--}}
                                                   </td>
                                               </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                 @endif
              @endif
                    </div>
        </div>


        <div class=" col-md-12 bg-muted-shade3 p-4">
            <div class="row">

            <div class="col-md-3">
         <h3>{{post_value_or($m_s_fields,'makeReminder','Make Reminder')}}</h3>
            </div>
        @if(isset($answers))
            @if($answers->isReminder==1)
            <div class="col-md-6 reminder_{{$show->id}}_{{$forms->id}}">
                {{--<div class="col-md-12">--}}
                {{--<div class="col-sm-offset-10 col-sm-1" style="margin-top: 20px;">--}}
                    {{--<a style="float: right" href="javascript:void(0)" onclick="editReminder('{{$show->id}}','{{$forms->id}}','{{$answers->reminderDays}}','{{$answers->reminderHours}}','{{$answers->reminderMinutes}}')" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="row">

                <div class="col-md-12">
                    <div class="row">

                    <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $answers->reminderDays !!} <small>Days</small></h4></div>
                <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $answers->reminderHours !!} <small>Hours</small></h4></div>
                <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $answers->reminderMinutes !!} <small>Minutes</small></h4></div>
                    </div>
                </div>
                </div>


                    <div class="col-md-12 text-center mt-15">
                        <a class="btn btn-primary text-right" href="javascript:void(0)" onclick="editReminder('{{$answers->id}}','{{$show->id}}','{{$forms->id}}','{{$answers->reminderDays}}','{{$answers->reminderHours}}','{{$answers->reminderMinutes}}')"> Edit Reminder</a>

            </div>
            </div>
            @else
            <div class="col-md-6 reminder_{{$show->id}}_{{$forms->id}}">
            <a class="btn btn-primary" href="javascript:"  onclick="addReminder('{{$answers->id}}','{{$show->id}}','{{$forms->id}}')">Add Reminder</a>
            </div>
            @endif
        @endif
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
                <div class="create-form">

                    @if($templateType==SHOW )
                    <div class="row">
                            <div class="col-sm-12">
                              <h3>{{post_value_or($m_s_fields,'selectClassTypes','Select Class Rating')}}</h3>
                              <div class="panel-body" style=" max-height: 450px;overflow-y: scroll;">
                                  @php $showClassType = getShowClassTypes($show->id);
                                        $asset_class_type_id = 0;
                                  @endphp

                                  <input type="hidden" name="asset_class_type_id" value="{{$asset_class_type_id}}">

                                      <table class="table table-line-braker mt-10 custom-responsive-md">
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
                                              <fieldset class="form-group select-bottom-line-only">
                                                  <select  name="asset_class_type[{{$asset->id}}]"  class="form-control-bb-only form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
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
                                              </fieldset>
                                            </div>
                                          </td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                  </table>

                            </div>
                          </div>
                        </div>
                    @endif
                        <div class="col-sm-12 text-center">
                        {!! Form::submit("Save Show" , ['class' =>"btn btn-lg btn-secondary"]) !!}
                        </div>
                </div>
            @endif
            @endif
            {!! Form::close() !!}
            </div>
            </div>
        </div>
        </div>
       @endforeach
        @else
        <div class="slide-holder">
                    <h5 class="card-header">
                        <a class="d-block title collapsed" data-toggle="collapse" href="#100" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                             {{post_value_or($m_s_fields,'showTitle','Show Name')}}
                        </a>
                    </h5>
                    <div id="100" class="collapse" aria-labelledby="heading-example">
                        <div class="card-body">
                            {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
                            <input type="hidden" name="appId" value="{{$appId}}">
                            <input type="hidden" value="{{$template_id}}" name="template_id"  id="template_id" class="template_id">

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'showTitle','Show Name')}}</label>
                                            <input required="required"  name="showTitle"   type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateFrom','Date From')}}</label>
                                            <input @if($templateType==SHOW) required="required" @endif  name="dateFrom"  type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateTo','Date To')}}</label>
                                            <input @if($templateType==SHOW) required="required" @endif  name="dateTo"  type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'contactinformation','Contact Information')}}</label>
                                            <input   name="contact_information"  type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">

                                    @if($templateType==SHOW)
                                    <div class="col-md-3">
                                            <fieldset class="form-group select-bottom-line-only">
                                                <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}}</label>
                                                <select required name="governing_body" class="form-control form-control-bb-only" onchange="checkUsef($(this))">
                                                    <option value="">Select Governing Body</option>
                                                    <option value="USEF">USEF</option>
                                                    <option value="Local">Local</option>
                                                    <option value="EC">EC</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}}</label>
                                            <input required="required"  name="usef_id" value=""  type="text" class="form-control form-control-bb-only usefNo" id="" placeholder="USEF Competition ID">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowRating','Show Rating')}}</label>
                                            <select name="show_type_class" class="show-tick form-control form-control-bb-only" data-live-search="true">
                                                @if(count($ShowType)>0)
                                                    @foreach($ShowType as $asset)
                                                        <option value="{{$asset->id}}"> {{$asset->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @if($templateType==SHOW || $templateType==TRAINER )
                                    <div class="col-md-3">

                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'show_type','Show Type')}}</label>
                                            <select  required name="show_type" class="form-control form-control-bb-only">
                                                <option value="">Select Show Type</option>
                                                <option value="Dressage">Dressage</option>
                                                <option value="Hunter">Hunter/Jumper</option>
                                                <option value="Eventing">Eventing</option>
                                                <option value="Western">Western</option>
                                                <option value="Breeding">Breeding</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'statetax','State Tax % ')}}</label>
                                            <input required="required"  name="state_tax"  value="" type="text" class="form-control form-control-bb-only" id="" placeholder="% of Total">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}}</label>
                                            <input required="required"  name="federal_tax" value=""  type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    @endif
                                    <div class="col-md-3">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'TimeZone','Time Zone')}}</label>
                                            <select name="time_zone" title=" --- Select Time Zone --- "   class="form-control-bb-only show-tick form-control" data-live-search="true">
                                                @if($timeZoneArr>0)
                                                    @foreach($timeZoneArr as $time=>$zone)
                                                        <option  value="{{$time}}">{{$zone}} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6  map-location"  initialize="false">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Location','Location')}}</label>
                                            <input required="required"  name="location"  value="{{isset($show->location)? $show->location :''}}" type="text" class="form-control form-control-bb-only location allow-copy" autocomplete="off" id="search_input_1" placeholder="Enter Location">
                                        </fieldset>
                                    </div>


                                    <div class="col-md-6 map-location"  initialize="false">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Location','Location')}}</label>
                                            <input required="required"  name="location"  value="" type="text" class="form-control form-control-bb-only location allow-copy" autocomplete="off" id="search_input_1" placeholder="Enter Location">
                                        </fieldset>
                                    </div>
                                    @if($templateType==SHOW)
                                    <div class="col-md-6">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'division','Select Division')}}</label>
                                            <select  multiple name="divisions[]" title=" --- Select Division --- "   class="selectpicker form-control-bb-only show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                                                @if($divisions->count()>0)
                                                    @foreach($divisions as $div)
                                                        <option value="{{$div->id}}" > {{GetAssetName($div)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                        <div class="col-md-12"><span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Add all classes of selected division(s) as part of your show by clicking on 'Add Classes' button.</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @if($templateType==SHOW || $templateType==TRAINER)
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowDescription','Show Description')}} </label>
                                            <textarea  maxlength="240" name="show_description" class="form-control form-control-bb-only"></textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowInfoOnInvoice','Info On Invoice')}} </label>
                                        <textarea maxlength="240" name="info_on_invoice" class="form-control form-control-bb-only"></textarea>
                                    </fieldset>
                                    </div>
                                </div>
                                @endif

                                <div class="row text-right">

                                    <div class="form-group col-md-12 pull-right ">

                                        {!! Form::submit("Save Show" , ['class' =>"btn btn-small btn-secondary"]) !!}
                                    </div>

                                </div>

                                @if(isset($show))
                                <div class="invite-participant-history">
                                    @if($schedular_forms->count())
                                        @foreach($schedular_forms as $forms)
                                            @php
                                                $restriction = [];

                                                $res =  restrictedScheduledDates($forms->id,0);
                                               $answers = $res->first();
                                               if($answers!=''){
                                                    $model = $answers->SchedulerRestriction($answers->id,$forms->id);

                                                    if($model)
                                                    $restriction = $model->get()->toArray();

                                                    $requiredClass = '';
                                                    }


                                              $key = $forms->id;

                                               if($answers != null)
                                               {
                                                 if($answers->name!='')
                                                  $requiredClass = 'required';
                                               }

                                            @endphp

                                            @if($answers!= "")
                                                @php $key = $answers['id']; @endphp
                                                <input type="hidden" name="schedual_id[{{$key}}]" value="{{$answers['id']}}">
                                            @else
                                                @php $key = $forms->id;@endphp
                                            @endif
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

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-gradient-top p-3">
                                                        <h3 class="text-secondary">{{$forms->name}}</h3>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <fieldset class="form-inline border-bottom">
                                                                    <label class="pr-10">{{post_value_or($m_s_fields,'SchedularName','Schedular Name')}}</label>
                                                                    <input  name="schedular_name[{{$key}}]" @if($answers != null) value="{{$answers->name}}" @endif type="text" class="form-control bg-transparent border-0 checkValidation" id="" placeholder="Enter Value">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-sm-offset-4 col-sm-2 text-center">
                                                                @if($answers != null)
                                                                    <a class="btn btn-primary call-to-add-class" href="javascript:" data-show-type="{{$show->show_type}}" onclick="addRestrictions('{{$answers->id}}','{{$show->id}}','{{$forms->id}}')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
                                                                @else
                                                                    <a class="btn btn-primary" href="javascript:" style="float: right" onclick="alertBox('Please enter scheduler name and then save show before adding classes.')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="schedual-restrictions col-md-12 pb-50 @if(isset($show)) scheduler_con_{{$show->id}}_{{$forms->id}} @endif">
                                                                @php $count = 0; @endphp
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

                                                                    <div class="row scheduler-{{$row['scheduler_key']}} schedulerCon {{(++$count%2 ? "schOdd" : "schEeven") }} ">

                                                                        <div class="col-sm-offset-11 col-sm-1" style="margin-top: 20px;">
                                                                            <a style="float: right" href="javascript:void(0)" data-show-type="{{$show->show_type}}"  class="edit-show-popup" onclick="editScheduler('{{$row['scheduler_key']}}','{{$show->id}}','{{$forms->id}}')" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                                                            <a href="javascript:void(0)" onclick="deleteScheduler($(this),'{{$row['scheduler_key']}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                                        </div>

                                                                        <div class="table-responsive">
                                                                            <table class="table table-line-braker mt-10 custom-responsive-md">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td width="180" scope="row">
                                                                                        {{post_value_or($m_s_fields,'selectClass','Classes')}}
                                                                                    </td>
                                                                                    <td class="pl-0" width="400">
                                                                                        {!! getClassNames($row['asset_id']) !!}
                                                                                    </td>
                                                                                    @if($templateType==TRAINER || $templateType == SHOW)
                                                                                    <td  class="pl-0" width="180">
                                                                                        {{post_value_or($m_s_fields,'selectScoreClass','Score From')}}
                                                                                    </td>
                                                                                    <td  class="pl-0" width="400">
                                                                                        {!!($row['score_from'] ? getClassNames($row['score_from']) : "N/A")  !!}
                                                                                    </td>
                                                                                    @endif

                                                                                </tr>

                                                                                <tr>

                                                                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectDateAndTime','Scheduler Time')}}</td>
                                                                                    <td class="pl-0" width="400">{{($row['restriction'] ? $row['restriction'] : "N/A")}}</td>
                                                                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectBlockTime','Block Time')}}</td>
                                                                                    <td class="pl-0" width="400">{{($row['block_time'] ? $row['block_time'] : "N/A")}}</td>
                                                                                </tr>
                                                                                <tr>

                                                                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</td>
                                                                                    <td class="pl-0" width="400">{{($row['block_time_title'] ? $row['block_time_title'] : "N/A")}}</td>
                                                                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Rider restrcited to Book Rides?')}}</td>
                                                                                    <td class="pl-0" width="400">{{ ($row['is_rider_restricted']==1) ? 'Yes' : "N/A" }}</td>


                                                                                </tr>
                                                                                <tr>

                                                                                    <td class="pl-0" width="180">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</td>
                                                                                    <td class="pl-0" width="400">{{ ($row['is_multiple_selection']==1) ? 'Yes' : "N/A" }}</td>
                                                                                    <td class="pl-0" width="180">Qualifying</td>
                                                                                    <td class="pl-0" width="400">{{ ($row['qualifing_check']==1) ? "($)".$row['qualifing_price'] : "N/A" }}</td>


                                                                                </tr>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>


                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                            <div class="col-sm-12 slotsContainer">
                                                                <h3>{{post_value_or($m_s_fields,'SlotsDurationOfClasses','Slots Duration of Classes')}}</h3>
                                                                <div class="adding-restrictions-options">
                                                                   @if(isset($assets))
                                                                    @if(count($assets)>0)
                                                                        <div class="row" style=" margin-bottom: 15px;">
                                                                            <div class="table-responsive" style=" max-height: 450px;overflow-y: scroll;">

                                                                                <table class="table table-line-braker mt-10 custom-responsive-md">
                                                                                    <thead>
                                                                                    <tr><th>{{post_value_or($m_s_fields,'Class','Class')}}</th><th>{{post_value_or($m_s_fields,'Slots Duration','Slots Duration')}}</th></tr></thead>
                                                                                    <tbody>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @endif
                                                                </div>
                                                            </div>


                                                            <div class=" col-md-12 bg-muted-shade3 p-4">
                                                                <div class="row">

                                                                    <div class="col-md-3">
                                                                        <h3>{{post_value_or($m_s_fields,'makeReminder','Make Reminder')}}</h3>
                                                                    </div>

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
                                @endif

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
        @endif
            <div class="hide addMoreCon" style="margin-top: 10px; position: relative">
                <div class="slide-holder">
                    <h5 class="card-header">
                        <a class="d-block title collapsed" data-toggle="collapse"  href="#2" aria-expanded="true" aria-controls="collapse-example" id="heading-example">
                             {{post_value_or($m_s_fields,'showTitle','Show Name')}}
                        </a>
                    </h5>
                    <div id="2" class="collapse show" aria-labelledby="heading-example">
                        <div class="card-body">
                            {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
                            <input type="hidden" name="appId" value="{{$appId}}">
                            <input type="hidden" value="{{$template_id}}" name="template_id"  id="template_id" class="template_id">

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'showTitle','Show Name')}}</label>
                                            <input required="required"  name="showTitle"   type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateFrom','Date From')}}</label>
                                            <input required="required"  name="dateFrom"  type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'DateTo','Date To')}}</label>
                                            <input required="required"  name="dateTo"  type="text" class="form-control form-control-bb-only datetimepicker8" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'contactinformation','Contact Information')}}</label>
                                            <input   name="contact_information"  type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">

                                    @if($templateType==SHOW)


                                        <div class="col-md-3">
                                            <fieldset class="form-group select-bottom-line-only">
                                                <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Governing_Body','Governing Body')}}</label>
                                                <select required name="governing_body" class="form-control form-control-bb-only" onchange="checkUsef($(this))">
                                                    <option value="">Select Governing Body</option>
                                                    <option value="USEF">USEF</option>
                                                    <option value="Local">Local</option>
                                                    <option value="EC">EC</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'USEF_number','USEF Competition ID')}}</label>
                                            <input required="required"  name="usef_id" value=""  type="text" class="form-control form-control-bb-only usefNo" id="" placeholder="USEF Competition ID">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowRating','Show Rating')}}</label>
                                            <select name="show_type_class" class="show-tick form-control form-control-bb-only" data-live-search="true">
                                                @if(count($ShowType)>0)
                                                    @foreach($ShowType as $asset)
                                                        <option value="{{$asset->id}}"> {{$asset->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @if($templateType==SHOW)

                                    <div class="col-md-3">

                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'show_type','Show Type')}}</label>
                                            <select  required name="show_type" class="form-control form-control-bb-only">
                                                <option value="">Select Show Type</option>
                                                <option value="Dressage">Dressage</option>
                                                <option value="Hunter">Hunter/Jumper</option>
                                                <option value="Eventing">Eventing</option>
                                                <option value="Western">Western</option>
                                                <option value="Breeding">Breeding</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'statetax','State Tax % ')}}</label>
                                            <input required="required"  name="state_tax"  value="" type="text" class="form-control form-control-bb-only" id="" placeholder="% of Total">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'federaltax','Federal Tax % ')}}</label>
                                            <input required="required"  name="federal_tax" value=""  type="text" class="form-control form-control-bb-only" id="" placeholder="Enter Value">
                                        </fieldset>
                                    </div>
                                    @endif
                                    <div class="col-md-3">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'TimeZone','Time Zone')}}</label>
                                            <select name="time_zone" title=" --- Select Time Zone --- "   class="form-control-bb-only show-tick form-control" data-live-search="true">
                                                @if($timeZoneArr>0)
                                                    @foreach($timeZoneArr as $time=>$zone)
                                                        <option  value="{{$time}}">{{$zone}} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 map-location"  initialize="false">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'Location','Location')}}</label>
                                            <input required="required"  name="location"  value="" type="text" class="form-control form-control-bb-only location allow-copy" autocomplete="off" id="search_input_1" placeholder="Enter Location">
                                        </fieldset>
                                    </div>
                                    @if($templateType==SHOW)

                                    <div class="col-md-6">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'division','Select Division')}}</label>
                                            <select  multiple name="divisions[]" title=" --- Select Division --- "   class="selectpicker form-control-bb-only show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                                                @if($divisions->count()>0)
                                                    @foreach($divisions as $div)
                                                        <option  value="{{$div->id}}" > {{GetAssetName($div)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                        <div class="col-md-12"><span style="font-size:12px;padding:3px 0px 15px 0px;color: #651e1c; float: left;">Note: Add all classes of selected division(s) as part of your show by clicking on 'Add Classes' button.</span>
                                        </div>
                                    </div>
                                        @endif
                                </div>
                                @if($templateType==SHOW || $templateType == TRAINER)
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowDescription','Show Description')}} </label>
                                            <textarea  maxlength="240" name="show_description" class="form-control form-control-bb-only"></textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                    <fieldset class="form-group">
                                        <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'ShowInfoOnInvoice','Info On Invoice')}} </label>
                                        <textarea maxlength="240" name="info_on_invoice" class="form-control form-control-bb-only"></textarea>
                                    </fieldset>
                                    </div>
                                </div>
                                @endif

                                <div class="row text-right">

                                    <div class="col-sm-12 text-right">

                                        {!! Form::submit("Save Show" , ['class' =>"btn btn-small btn-secondary"]) !!}
                                    </div>

                                </div>

                                <div class="invite-participant-history hide">
                                    @if($schedular_forms->count())
                                        @foreach($schedular_forms as $forms)
                                            @php
                                                $restriction = [];

                                                $res =  restrictedScheduledDates($forms->id,0);
                                               $answers = $res->first();
                                               if($answers!=''){
                                                    $model = $answers->SchedulerRestriction($answers->id,$forms->id);

                                                    if($model)
                                                    $restriction = $model->get()->toArray();

                                                    $requiredClass = '';
                                                    }


                                              $key = $forms->id;

                                               if($answers != null)
                                               {
                                                 if($answers->name!='')
                                                  $requiredClass = 'required';
                                               }

                                            @endphp

                                            @if($answers!= "")
                                                @php $key = $answers['id']; @endphp
                                                <input type="hidden" name="schedual_id[{{$key}}]" value="{{$answers['id']}}">
                                            @else
                                                @php $key = $forms->id;@endphp
                                            @endif
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

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-gradient-top p-3">
                                                        <h3 class="text-secondary">{{$forms->name}}</h3>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <fieldset class="form-inline border-bottom">
                                                                    <label class="pr-10">{{post_value_or($m_s_fields,'SchedularName','Schedular Name')}}</label>
                                                                    <input  name="schedular_name[{{$key}}]" @if($answers != null) value="{{$answers->name}}" @endif type="text" class="form-control bg-transparent border-0 checkValidation" id="" placeholder="Enter Value">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-sm-offset-4 col-sm-2 text-center">
                                                                @if($answers != null)
                                                                    <a class="btn btn-primary call-to-add-class" href="javascript:" data-show-type="{{$show->show_type}}" onclick="addRestrictions('{{$show->id}}','{{$forms->id}}')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
                                                                @else
                                                                    <a class="btn btn-primary" href="javascript:" style="float: right" onclick="alertBox('Please enter scheduler name and then save show before adding classes.')">{{post_value_or($m_s_fields,'AddClasses','Add Classes')}}</a>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="schedual-restrictions col-md-12 pb-50 @if(isset($show)) scheduler_con_{{$show->id}}_{{$forms->id}} @endif">
                                                                @php $count = 0; @endphp
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

                                                                    <div class="row scheduler-{{$row['scheduler_key']}} schedulerCon {{(++$count%2 ? "schOdd" : "schEeven") }} ">

                                                                        <div class="col-sm-offset-11 col-sm-1" style="margin-top: 20px;">
                                                                            <a style="float: right" href="javascript:void(0)" data-show-type="{{$show->show_type}}" class="edit-show-popup" onclick="editScheduler('{{$row['scheduler_key']}}','{{$show->id}}','{{$forms->id}}')" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                                                            <a href="javascript:void(0)" onclick="deleteScheduler($(this),'{{$row['scheduler_key']}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                                        </div>

                                                                        <div class="table-responsive">
                                                                            <table class="table table-line-braker mt-10 custom-responsive-md">
                                                                                <tbody>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>


                                                                    </div>
                                                                @endforeach

                                                            </div>
                                                            <div class="col-sm-12 slotsContainer">
                                                                <h3>{{post_value_or($m_s_fields,'SlotsDurationOfClasses','Slots Duration of Classes')}}</h3>
                                                                <div class="adding-restrictions-options">
                                                                    @if(isset($assets))
                                                                    @if(count($assets)>0)
                                                                        <div class="row" style=" margin-bottom: 15px;">
                                                                            <div class="table-responsive" style=" max-height: 450px;overflow-y: scroll;">

                                                                                <table class="table table-line-braker mt-10 custom-responsive-md">
                                                                                    <thead>
                                                                                    <tr><th>{{post_value_or($m_s_fields,'Class','Class')}}</th><th>{{post_value_or($m_s_fields,'Slots Duration','Slots Duration')}}</th></tr></thead>
                                                                                    <tbody>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @endif

                                                                </div>
                                                            </div>


                                                            <div class=" col-md-12 bg-muted-shade3 p-4">
                                                                <div class="row">

                                                                    <div class="col-md-3">
                                                                        <h3>{{post_value_or($m_s_fields,'makeReminder','Make Reminder')}}</h3>
                                                                    </div>

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

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <a class="removeScheduler" href="javascript:" style="position: absolute; top: 0px; right: -16px;">
                        <i data-toggle="tooltip" title="" class="fa fa-trash" data-original-title="Delete"></i>
                    </a>
                </div>

            </div>

</div>
</div>

        <div id="addReminder" class="modal fade bs-example-modal-sm"  role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-md" role="document" style="width: 1000px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">Add Reminder</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(['url'=>'','method'=>'post','id'=>'addReminderForm']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>

                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">Select Days</label>
                                            <select name="reminderDays" title=" Select Days  "   class="form-control reminderDays form-control-bb-only" >
                                                @for($i=1;$i<7; $i++)
                                                    <option  value="{{$i}}">{{$i}} Day before</option>
                                                @endfor
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-sm-12">
                                        <fieldset class="form-group select-bottom-line-only">
                                            <label class="text-content-dark" for="">Select Hours</label>
                                            <select name="reminderHours" title="Select Hours"   class="form-control reminderHours form-control-bb-only"  data-live-search="true">
                                                @for($i=1;$i<24; $i++)
                                                    <option  value="{{$i}}">{{$i}} Hour</option>
                                                @endfor
                                            </select>
                                        </fieldset>
                                    </div>


                                    <div class="col-sm-12">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark">Select Minutes</label>
                                            <input name="reminderMinutes"  type="text" min="5" step="1" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control reminderMinutes  form-control-bb-only">
                                        </fieldset>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="modal-buttons">
                            <div class="row">
                                <input type="hidden" value="" name="scheduler_id"  id="scheduler_id"  class="scheduler_id">
                                <input type="hidden" value="" name="show_id"  id="show_id"  class="show_id">
                                <input type="hidden" value="" name="form_id"  id="form_id"  class="form_id">

                                <input type="hidden" id="csrf-token" value="{{csrf_token()}}">


                                <div class="col-sm-2" style="padding-right: 8px;">
                                    <input type="submit" value="Save"   class="btn btn-sm btn-success" />
                                </div>
                                <div class="col-sm-2" style="padding-left: 8px;padding-right: 8px;">
                                    <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-defualt" />
                                </div>



                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div id="addRestrictions" class="modal fade"  role="dialog" >
            <div class="modal-dialog modal-lg" role="document" style="width: 1000px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">Add Restrictions</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(['url'=>'','method'=>'post','id'=>'addClasses']) !!}
                    <div class="modal-body">
                        <div class="row" style="padding: 0px 15px">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>

                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <input type="hidden" name="template_id" value="" class="addtemplateid">
                                <div class="row">

                                    <div class="col-sm-12" id="ClassesContainer">
                                            <label class="text-content-dark" for="">Select Classes</label>
                                            <select multiple name="assets[]" title=" --- Select Classes --- "   class="form-control selectClasses selectpicker form-control-bb-only allAssets" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true" required>
                                                @if($AllAssets->count()>0)
                                                    @foreach($AllAssets as $asset)
                                                        <option value="{{$asset->id}}" > {{GetAssetName($asset)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                    </div>

                                    @if($templateType==SHOW)
                                    <div class="col-sm-12 mt-10">
                                        <label class="text-content-dark" for="">Class Type <small>(for group classes same time slot will not be book)</small></label>
                                        <select  name="is_group"  class="form-control selectpicker form-control-bb-only is_group">
                                              <option value="0">Non group</option>
                                            <option value="1">Group</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-10">
                                            <label class="text-content-dark" for="">Select Score From</label>
                                            <select  multiple name="score_from[]" title=" --- Select Classes --- "  class="form-control scoreFrom selectpicker form-control-bb-only" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true">
                                                @if($AllAssets->count()>0)
                                                    @foreach($AllAssets as $asset)
                                                        <option value="{{$asset->id}}" > {{GetAssetName($asset)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                    </div>
                                    @endif
                                    <div class="col-sm-12 mt-10">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'SelectDateAndTime','Select Date and Time')}}</label>
                                            <input autocomplete="off"  value="" type="text" class="daterange2222 form-control SelectDateAndTime form-control-bb-only"
                                                   placeholder="Add Date" name="dateTimeSchedual" required />
                                        </fieldset>

                                    </div>
                                    <div class="col-sm-12 mt-10">
                                        <fieldset class="form-group">
                                            <label class="text-content-dark" for="">{{post_value_or($m_s_fields,'SelectBlockTime','Select Block Time')}}</label>
                                            <input  type="text" class="daterange form-control blockTime form-control-bb-only" placeholder="Add Block Time" name="blockTime" />
                                            <label style="margin-top: 15px;">{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Titlle')}}</label>
                                            <input type="text" class="form-control blockTimeTitle form-control-bb-only"  placeholder="Add Title Of Block Time" name="blockTimeTitle" value="" />
                                        </fieldset>
                                    </div>
                                        @if($templateType==SHOW)
                                        <div class="col-sm-12 mt-10 multipleSelect">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input multipleSelection" value="1" name="multipleSelection" id="" type="checkbox">
                                                <span>{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</span>
                                            </label>
                                        </div>
                                    </div>
                                        @endif

                                        <div class="col-sm-12 mt-10">

                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input restrictRiders" value="1" name="restrictRiders" id="" type="checkbox">
                                                <span>{{post_value_or($m_s_fields,'restrictRiderToBookRides','Restrict Riders to Book Rides')}}</span>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 show-qualifying-at-initiate  mt-10">
                                        <div class="form-group row">
                                            <div class="text-box-holder col-sm-3">

                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input qualifingcheckbox" value="1" name="qualifingPoints" id="legendCheck1" type="checkbox">
                                                    <span>Qualifying</span>
                                                </label>
                                            </div>
                                            </div>

                                            <div class="text-box-qprice col-sm-5" style="display:none">
                                                <label>
                                                    Add Price ($)
                                                    <input type="number" name="qualifingPrice" class="form-control qualifing_price">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" value="{{$template_id}}" name="template_id"  id="template_id" class="template_id">
                            <input type="hidden" value="" name="form_id"  id="form_id"   class="form_id" >
                            <input type="hidden" value="" name="show_id"  id="show_id"  class="show_id">
                            <input type="hidden" value="" name="scheduler_id"  id="scheduler_id"  class="scheduler_id">
                            <input type="hidden" value="" name="scheduler_key"  id="scheduler_key"  class="scheduler_key">

                            <input type="hidden" id="csrf-token" value="{{csrf_token()}}">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>


                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


        @endsection
        @section('footer-scripts')
        @include('layouts.partials.datatable')
        <script src="{{ asset('/js/schedular-form-jquery.js') }}"></script>

        <script src="{{ asset('/js/schedual.js') }}"></script>
            <script src="{{ asset('/js/google-map-script-form.js') }}"></script>

            <link href="{{ asset('/old_css/vender/daterangepicker.css') }}" rel="stylesheet" />

            <style>
                /*div.inner*/
                /*{*/
                    /*overflow-y: hidden !important;*/
                /*}*/
                /*.inner ul.dropdown-menu {*/

                /*}*/
                .table-responsive
                {
                    overflow-y: scroll!important;

                }

            </style>
            <div class="col-sm-12 map-location" initialize="false">
                <div id="map_wrapper">
                    <div id="map_canvas_1" class="map-canvas mapping"></div>
                </div>
            </div>
        @endsection