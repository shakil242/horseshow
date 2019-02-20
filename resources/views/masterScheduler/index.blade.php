@extends('layouts.equetica2')
@section('main-content')
    <div class="page-menu">
        <div class="row justify-content-between collapse-box menu-holder">
            <div class="d-flex flex-nowrap left-panel">
                <span class="menu-icon" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <img src="{{ asset('/img/icons/icon-menu.svg') }}" />
                </span>
                    <h1 class="title flex-shrink-1">{{getShowName($variables['show_id'])}}
                    </h1>

            </div>


            <div class="right-panel">

                <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
                            <i class="fa fa-navicon"></i>
                        </span>

                    {{--<div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">--}}
                        {{--<form class="form-inline justify-content-end">--}}
                            {{--<div class="search-field">--}}
                                {{--<div class="input-group">--}}
                                    {{--<input  id="typeahead-activity" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" type="text">--}}
                                    {{--<div class="input-group-prepend">--}}
                                        {{--<span class="input-group-text" id="basic-addon1"><img src="img/icons/icon-search.svg"></span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}

                    {{--</div>--}}
                </div>
            </div>


            @if($manageShows->count()>0 && $variables['spectatorsId']=='')
                <div class="collapse menu-box" id="collapseExample">
                        <span class="close-menu" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <img src="{{ asset('/img/icons/icon-close.svg')}}" />
                        </span>
                    <div class="menu-links">
                        <div class="row">
                            <!-- col-md-3  -->
                            <div class="col-md-3" >
                                <ul class="nav flex-column" >
                                    @foreach($manageShows as $show)
                                        <li class="nav-item">
                                            <a class="nav-link {{($show->id==$variables['show_id'])?'active':''}}"  href="{{URL::to('master-template') }}/{{nxb_encode($variables['templateId'])}}/{{nxb_encode($show->id)}}/masterSchedular{{($variables['spectatorsId']) ? '/'.nxb_encode($variables['spectatorsId']) : '' }}">{{$show->title}}
                                            </a>
                                        </li>

                                        @if($loop->iteration % 5 ==0)
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="nav flex-column">
                                    @endif
                                    @endforeach
                                </ul>

                            </div>
                        </div>


                    </div>
                </div>
            @endif

        </div>
    </div>

   @php $templateType = GetTemplateType($variables['templateId']);@endphp

    {{--@include('masterScheduler.userSearch')--}}

    <div class="white-board">

        <div class="row">
            <div class="col-12">
                <div class="tabs-header">



                    @if(count($formArray)>0)

                        <ul id="myTab" class="nav nav-tabs" role="tablist">
                            @php $i = 0 @endphp
                            @foreach($formArray as $k => $row)
                                @php
                                    list($key,$schedulerId) = explode(',', $k);
                                @endphp

                        <li class="t-{{$key}} nav-item ">
                                    <a class="nav-link @if($i==0) active @endif"  data-id="{{$key}}"  @if($i==0) aria-selected="true" @else aria-selected="false" @endif data-attr="{{nxb_encode($key)}}" data-toggle="tab" href="#tab_{{$key}}" role="tab" aria-controls="home">{{getSchedulerName($schedulerId)}}</a>
                                </li>
                                @php $i += 1  @endphp
                            @endforeach
                        </ul>
                    @endif
                </div>
                @if(count($formArray)>0)
               <div class="tab-content" id="myTabContent">
                @foreach($formArray as $k => $row)
                    @php
                        list($key,$schedulerId) = explode(',', $k);
                    @endphp
                        <div class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade {{$loop->first?'show active':''}}" id="tab_{{$key}}" role="tabpanel" aria-labelledby="division-tab">

                            <!-- Filter of Tabs including Select Class and Modify Slot Time -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="text-content-dark" for="">Select Class</label>
                                    <select  class="selectpicker multiClass form-control form-control-bb-only" onchange="getCalendar($(this).val(),'{{nxb_encode($variables['templateId'])}}','{{nxb_encode($key)}}','{{nxb_encode($variables['show_id'])}}')" name="asset" data-live-search="true">
                                        @foreach($row as $res)
                                            <option value="{{nxb_encode($res['asset_id'])}}" @if(old("asset") != null)
                                                {{ (in_array($res['asset_id'], old("asset")) ? "selected":"") }}
                                                    @endif> {{ GetAssetNamefromId($res['asset_id'])}} -- Est. Start Time {{current(explode('-',$res['restrcition']))}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($AssetsForms->count()>0 && $variables['spectatorsId']=='')
                                <div class="col-md-6 align-self-end text-right">
                                    <a style="color: #FFFFFF" class="btn btn-success" data-toggle="collapse" data-target="#demo-{{$key}}">Modify Slot Time</a>
                                </div>
                                @endif

                            </div>

                          <div id="demo-{{$key}}" class="collapse mt-20 col-md-12 box-shadow bg-white p-3 mb-20">

                                    {!! Form::open(['url'=>'','method'=>'post','class'=>'updateTimeSLots']) !!}

                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="template_id_slots">
                                    <input type="hidden" value="{{$variables['assetId']}}" name="asset_id"  id="event_asset_id" class="event_asset_id">
                                    <input type="hidden" value="{{$variables['spectatorsId']}}" name="spectatorsId"  id="spectatorsId_slots">

                                    <input type="hidden" value="{{$variables['show_id']}}" name="show_id"  id="show_id_slots">
                                    <input type="hidden" value="" name="form_id"  class="formId">

                                    <div class="row" style="padding: 20px 15px 0px 30px">
                                        <div class="info">
                                            <p style="display: none" class="timeSlotUpdate text-center alert alert-success"></p>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <select multiple  required class="selectpicker form-control" title="Select Class"  name="changeClasses[]" data-live-search="true">
                                                @foreach($row as $res)
                                                    <option value="{{nxb_encode($res['asset_id'])}}" @if(old("asset") != null)
                                                        {{ (in_array($res['asset_id'], old("asset")) ? "selected":"") }}
                                                            @endif> {{ GetAssetNamefromId($res['asset_id']) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="col-md-3">

                                        <fieldset class="form-group">
                                            <input  placeholder="Enter Minutes" required onkeypress="return isNumber(event)" class="form-control form-control-sm" id="reminderMinutes" name="reminderMinutes" type="number"  min="" step="1" id="reminderMinutes" >
                                        </fieldset>
                                        </div>



                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input showTime" name="is_show_time_change" value="1" id="legendCheck1" type="checkbox">
                                                <span>Change Show Time</span>
                                            </label>
                                        </div>



                                    </div>

                                    <div class="row">

                                        <div class="col-sm-9" >
                                            <fieldset class="form-group">
                                                <input required   placeholder="Reason to update Schedual time" class="form-control form-control-lg" name="reason" />
                                            </fieldset>
                                        </div>


                                        <div class="col-sm-3">
                                            <input type="submit" value="Update Time"  class="btn btn-primary" />
                                        </div> </div>


                                    {!! Form::close() !!}

                                </div>


                    </div>
                    <!-- Ring 2 Data-->
                 @endforeach
               </div>
                    @endif

                </div>
            <input type="hidden" value="{{$variables['spectatorsId']}}" name=""  id="spectatorsId">

            <input id="csrf-token" type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="combined mb-20 ml-20 col-md-12" style="display: none"> </div>
            </div>
        @if(count($formArray)>0)
        <div class="col-md-12">
            <input type="hidden" class="form-control"  id="datepicker">
            <div class="row calendarCon">
                <div class="col-md-2">
                    <div class="champion-div-display">

                    </div>
                    <div class="placing-position">

                    </div>
                </div>
                <div class="col-md-10 card-small-area">
                    <!-- Feedback & Class Info -->
                    <div id="calendarContainer" class="SchedularContainerCustom">

                        {{--{!! $calendar->calendar() !!}--}}
                        {{--{!! $calendar->script() !!}--}}

                    </div>

                    </div>


            </div>

        </div>
        @else
            <div class="text-center m-4 col-md-12">
            <h4>No Form exist</h4>
            </div>
        @endif

        </div>


        <div id="eventContent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">Appointments</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    {!! Form::open(['url'=>'','method'=>'post','id'=>'addNotes']) !!}

                    <div class="modal-body">

                        <div class="row" style="padding: 0px 15px">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>

                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <input type="hidden" name="template_id" value="" class="addtemplateid">
                                <div class="col-sm-12">

                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group select-bottom-line-only ClassHorse">
                                            <select required id="ClassHorsess" class="form-control form-control-bb-only">
                                              <option value="">Please Select Horse</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="ClassHeight" style="display: none">
                                        <div class="form-group select-bottom-line-only heightCon">
                                            <label>Height</label>
                                            <select class="form-control form-control-bb-only">

                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-4">
                                        <div class="form-group select-bottom-line-only ">
                                            <label>Start Time</label>
                                            <select id="timeFrom" name="timeFrom" class="form-control   selectpicker">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group select-bottom-line-only">
                                            <label>End Time</label>
                                            <select id="timeTo" name="timeTo"  class="form-control  selectpicker">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="row">

                                    <div id="myDiv" style="color: red; padding-left: 16px; padding-bottom: 10px;"></div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <textarea  name="notes" id="notes" class="notes form-control form-control-lg "></textarea>
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-12 ReasonCon mb-15 mt-10 hide" >--}}
                                        {{--<div class="form-group">--}}
                                            {{--<input  placeholder="Reason to update Schedual time" name="reason" class="reason  form-control" />--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-12">
                                    <div class="scoreContainer row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Score</label>
                                            <input type="number" placeholder="Enter Score"  name="score" class="form-control-inline score" />
                                        </div>
                                    </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>Faults</label>
                                                <select multiple name="faults_option[]" class="selectpicker form-control-inline faults_option" placeholder="Select Faults" multiple  data-live-search="true">
                                                    @foreach(SHOWS_FAULTS as $key=>$value)
                                                        <option value="{{$key}}"> {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row col-sm-12 padding-10">
                            <div class="feedBackBtns"> </div>
                        </div>
                        <h4 class="modal-title" id="exampleModalLabel">Profiles</h4>
                        <div class="app-actions" style="width:100%;display:block">
                            <div class="row">

                                <?php $Profiler_Forms = getFormsForProfile($variables['templateId'],2) ?>
                                @if($Profiler_Forms->count())
                                    @foreach($Profiler_Forms as $form)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <a  href="javascript:" onclick="getSettingForm(this,'{{nxb_encode($form->id)}}')" class="app-action-link schedulerProfileView">{{$form->name}}</a>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-id="" class="col-md-8 schedulerProfileView"> No Form attached for this application</div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <div class="row">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <input type="hidden" value="" name="startTime"  id="startTime" class="startTime"/>
                                <input type="hidden" value="" name="endTime"  id="endTime" class="endTime" />

                                <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="templateId" class="templateId">
                                <input type="hidden" value="" name="userId"  id="userId" class="userId">
                                <input type="hidden" value="" name="form_id"  id="form_id" class="form_id formId">
                                <input type="hidden" value="{{$variables['assetId']}}" name="asset_id"  id="event_asset_id" class="event_asset_id">
                                <input type="hidden" value="{{$variables['show_id']}}" name="show_id"   class="showId">

                                <input type="hidden" value="" name="masterScheduler"  id="masterScheduler" class="masterScheduler">

                                <input type="hidden" value="" name="restriction_id"  id="restriction_id"  class="restriction_id">

                                <input type="hidden" value="" name="is_multiple_selection" class="is_multiple_selection"  id="is_multiple_selection">
                                <input type="hidden" value="" name="multiple_scheduler_key" class="multiple_scheduler_key"  id="multiple_scheduler_key">

                                <input type="hidden" value="" name="scheduler_type" class="scheduler_type"  id="scheduler_type">

                                <input type="hidden" value="" name="backgrounbdSlotId"  id="backgrounbdSlotId" class="backgrounbdSlotId">
                                <input type="hidden" value="" name="schedule_id"  id="schedule_id" class="schedule_id">

                                <div class="modal-footer">
                                        <input type="submit" value="Save"  id="markSave" class="btn btn-primary btn-invite-more markSave" />
                                        <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-defualt" />
                                        <input type="button" value="Mark Done"  id="markDone"  class="btn btn-primary markDone" />

                                        <input type="button" value="Send Reminder"  id="sendReminder"  class="btn btn-primary sendReminder" />
                                </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        <div id="eventsUsers" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h2 class="modal-title" id="modalLabel">Event Participants</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="modal-body">

                        <div class="module-holer rr-datatable" id="eventsUserCon">
                        </div>


                        <div class="row" style="padding: 0px 15px">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>


                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <input type="hidden" name="template_id" value="" class="addtemplateid">

                                <div class="row">
                                    <div class="col-sm-2" style="padding-left: 8px;padding-right: 8px;">
                                        <input type="button" value="Close" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-defualt" />
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="confirm" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel2">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title" id="modalLabel">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body">

                        <h3>Are you sure to delete?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>




@endsection
<?php $val = session('feedBackAssciated'); ?>
@section('footer-scripts')


    <script>
        {{--var calId='{{$calendar->getId()}}';--}}
        var calId='{{$calendar->getId()}}';
        var assetId='{{nxb_encode($variables['assetId'])}}';
        //var feedBackAssciated= <?php echo "$val" ?>;
        //console.log(JSON.stringify(feedBackAssciated));
        // for(i in feedBackAssciated){
        //     console.log(feedBackAssciated[i]);
        // }
        // if(feedBackAssciated==0)
        //     $(".feedBackBtn").hide();
        // else
        //     $(".feedBackBtn").show();

    </script>



    <script src="{{ asset('/js/vender/moment.min.js') }}"></script>
    <script src="{{ asset('/js/vender/fullcalendar.min.js') }}"></script>
    <script src='{{ asset('/js/vender/home.js?3.3.1-1.6.1-3') }}'></script>
    <link rel="stylesheet" href="{{ asset('/old_css/vender/fullcalendar.min.css') }}"/>
    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-tooltip.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/schedular-calendar.js') }}"></script>
            {{--<script type="text/javascript" src="{{ asset('/js/masterScheduler.js') }}"></script>--}}

            {{--<script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>--}}

    <style>

        .select-area .caret,.select-opener::after { color: #FFF!important;
            width: 25px!important;
            line-height: 40px;}

        .fc-event {
            background-color: #28a0e5;
            border: 1px solid #28a0e5;
            border-radius: 3px;
            display: block;
            font-size: 0.85em;
            font-weight: 400;
            line-height: 1.3;
            position: relative;
        }
        .fc-slats tr {
            line-height: 6.5;
        }
        .fc-content{ padding: 5px;}
        .assetClass {
            color: #FFF;
        }


        .select-selectpicker{
            display: none;
        }
        .btn.dropdown-toggle.bs-placeholder.btn-success {
            color: white;
        }

        .primary-table tr td:last-child a {
            margin: 0;
            float: none!important;
            color: #000; }

        .primary-table tr td:last-child a:hover {
            background-color: #337ab7!important;
            color: #FFFFFF!important;
        }

.bootstrap-select.show-tick.form-control-inline
{
    width: 70%;
}





    </style>
@endsection
