@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

        @php
            $title = getShowName($variables['show_id']);
            $added_subtitle = Breadcrumbs::render('master-template-list-schedular-forms-schedule');
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])


    @php $templateType = GetTemplateType($variables['templateId']);@endphp
    <div class="white-board">

        <div class="row">
            <div class="col-12">

                <div class="info">
                    <p style="display: none" class="deleteNotesMessage text-center alert alert-success"></p>

                @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>

                        @if(count($formArray)>0)
                        <div class="tabs-header">

                        <ul id="display-scheduler-assets" class="nav nav-tabs">
                            @php $i = 0 @endphp
                            @foreach($formArray as $k => $row)
                                @php
                                    list($key,$schedulerId) = explode(',', $k);
                                @endphp
                                <li class="t-{{$key}} nav-item ">
                                    <a class="nav-link @if($i==0) active @endif"  data-id="{{$key}}" data-attr="{{nxb_encode($key)}}" data-toggle="tab" href="#tab_{{$key}}" role="tab" aria-controls="home" aria-selected="true">{{getSchedulerName($schedulerId)}}</a>
                                </li>
                                @php $i += 1  @endphp
                            @endforeach
                        </ul>
                        </div>
                        <div class="tab-content">
                        @if(count($formArray)>0)
                        <?php $i = 0 ?>
                        @foreach($formArray as $k => $row)
                        <?php
                        list($key,$schedulerId) = explode(',', $k);
                        ?>
                            <div  data-id="{{$key}}" data-attr="{{nxb_encode($key)}}" class="box-shadow bg-white p-4 mt-30 mb-30 tab-pane fade {{$loop->first?'show active':''}}" id="tab_{{$key}}" role="tabpanel" aria-labelledby="division-tab">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-content-dark" for="">Select Class</label>
                                        <select  class="selectpicker multiClass form-control form-control-bb-only" onchange="getScheduler('{{$variables['show_id']}}','{{$key}}',$(this).val(),'{{$variables['associatedKey']}}','{{$variables['templateId']}}')" name="asset" data-live-search="true">
                                            @foreach($row as $res)
                                                <option value="{{$res['asset_id']}}" @if(old("asset") != null)
                                                    {{ (in_array($res['asset_id'], old("asset")) ? "selected":"") }}
                                                        @endif> {{ GetAssetNamefromId($res['asset_id'])}} -- Est. Start Time {{current(explode('-',$res['restrcition']))}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>


                            </div>
                        @endforeach
                        @endif
                        </div>

                <input id="csrf-token" type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="combined" style="display: none"> </div>



    <div class="row">

        <div class="col-sm-2">
            <div class="champion-div-display">
                                
            </div>
            <div class="placing-position">

            </div>
        </div>
        <div class="col-sm-10 calendarCon">
            <div id="calendarContainer" class="SchedularContainerCustom">
                {{--{!! $calendar->calendar() !!}--}}
                {{--{!! $calendar->script() !!}--}}
            </div>
        </div>
    </div>
@else
        <div class="row"><h6 style="text-align: center">App Owner has not configured any scheudler against this application</h6></div>
@endif
            </div>
        </div>
    </div>
    </div>
         <div id="eventContent" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
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
                        <div class="invite-holder" style="padding:10px;">
                            <input type="hidden" name="template_id" value="" class="addtemplateid">
                            <div class="col-sm-12">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group  ClassHorse">
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
                                         <textarea style="background: #ffffff" name="notes" id="notes" class="notes  form-control form-control-lg"></textarea>
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-12 ReasonCon hide" >--}}
                                        {{--<div class="form-group">--}}
                                            {{--<input disabled  placeholder="Reason to update Schedual time" name="reason" class="reason  form-control-bb-only" />--}}
                                        {{--</div>--}}
                                    {{--</div>--}}


                                    <div class="col-sm-6 scoreCon">
                                        <div class="form-group">
                                            <strong>Score :</strong> <span style="font-weight: bold; font-size: 16px; padding-left: 10px; color: #00C851" class="score"></span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-buttons" style="color: #9b8a30">
                            <div class="row">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <input type="hidden" value="" name="startTime" class="startTime"  id="startTime"/>
                                <input type="hidden" value="" name="endTime"  class="endTime" id="endTime"/>

                                <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="templateId">
                                <input type="hidden" value="{{$variables['userId']}}" name="userId"  id="userId">
                                <input type="hidden" value="" name="form_id"  id="formId" class="formId">
                                <input type="hidden" value="{{$variables['assetId']}}" name="asset_id"  id="asset_id">
                                <input type="hidden" value="{{$variables['associatedKey']}}" name="associatedKey"  id="associatedKey">
                                <input type="hidden" value="{{$variables['isSubParticipant']}}" name="isSubParticipant"  id="isSubParticipant">
                                <input type="hidden" value="{{$variables['subId']}}" name="subId"  id="subId">
                                <input type="hidden" value="" name="restriction_id"  id="restriction_id"  class="restriction_id">

                                <input type="hidden" value="" name="class_group_key"  id="class_group_key"  class="class_group_key">


                                <input type="hidden" value="{{$variables['subInviteeId']}}" name="subInviteeId"  id="subInviteeId">


                                <input type="hidden" value="" name="multiple_scheduler_key" class="multiple_scheduler_key"  id="multiple_scheduler_key">

                                <input type="hidden" value="" name="is_multiple_selection" class="is_multiple_selection"  id="is_multiple_selection">

                                <input type="hidden" value="" name="show_id"  id="show_id" class="show_id">

                                <input type="hidden" value="" name="backgrounbdSlotId"  id="backgrounbdSlotId" class="backgrounbdSlotId">

                                <input type="hidden" value="" name="schedule_id"  id="schedule_id" class="schedule_id">


                                <input type="hidden" value="" name="is_rider"  id="is_rider" class="is_rider">



                                <div class="col-sm-2">
                                    <input type="submit" value="Save"   id="markSave" class="btn btn-primary btn-invite-more" />
                                </div>
                                <div class="col-sm-2">
                                    <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-defualt" />
                                </div>

                                <?php $templateType = GetTemplateType($variables['templateId']); ?>
                                @if($templateType!=SHOW)
                                <div class="col-sm-2 markDone">
                                    <input type="button" value="Mark Done"  id="markDone"  class="btn btn-primary markDone" />
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
               {!! Form::close() !!}
                </div>
            </div>
        </div>


        <div id="confirm" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel2">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalLabel">Confirmation</h2>
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

        <div id="eventsUsers" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-md" role="document"  style="width: 800px;">
                <div class="modal-content">
                    <div class="modal-header">

                        <h2 class="modal-title" id="modalLabel">Event Participants</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="rr-datatable" id="eventsUserCon">

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

    @endsection

    @section('footer-scripts')

        <script>

            $('.fc-title').hover(function() {
                $(this).parent().parent().parent().attr('colSpan', 2);
            });

            {{--var calId='{{$variables['calId']}}';--}}
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

        <style>

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
            .fc-content{ padding: 5px 0px 0px 5px;}
            .assetClass {
                color: #FFF;
            }

            .fc-slats tr {
                line-height: 6.5;
            }
            .fc-title{ display: none!important;}
            .select-selectpicker{
                display: none;
            }

             .select-selectpicker{
                 display: none;
             }

            .primary-table tr td:last-child a {
                margin: 0;
                float: none!important;
                color: #000; }

            .primary-table tr td:last-child a:hover {
                background-color: #337ab7!important;
                color: #FFFFFF!important;
            }
            /*.bootstrap-select .dropdown-toggle:focus*/
            /*{*/
                /*color: #FFFFFF!important;*/

            /*}*/
            .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus
            {
                background-color: #337ab7!important;
                color: #FFFFFF!important;
            }


            a.list-group-item:hover, button.list-group-item:hover, a.list-group-item:focus, button.list-group-item:focus, a.list-group-item.active {
                text-decoration: none;
                color: #FFF;
                background-color: #4d1311;
            }
            .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
            { width: 157px!important;}

            a.HorseAsset {
                text-decoration: underline;
            }


            .reason
            {
                background: #ffffff none repeat scroll 0 0;
                border: medium none;
                color: #000;
                height: 31px;
                margin-bottom: 0;
                padding-bottom: 0;
                padding-left: 9px;
                width: 100%;

            }
            .hide{ display: none}

            .master .col-sm-3 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
                width: 134px !important;
            }

            .fc-ltr .fc-time-grid .fc-event-container
            {

                margin: 0px!important;
            }

        </style>


    @endsection