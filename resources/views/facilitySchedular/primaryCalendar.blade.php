@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    <?php $templateType = GetTemplateType($variables['templateId']);?>

    @php
        $title = GetAssetNamefromId($variables['primary_asset_id']);
        $added_subtitle = '';

        if($templateType==TRAINER)
            $added_subtitle = getShowName($variables['show_id']);

    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

    <!-- Content Panel -->
        <div class="white-board">

                @include('masterScheduler.userSearch')


{{--<div class="row" style="margin: 20px 0px; ">--}}
    {{--@if($variables['spectatorsId']=='')--}}
        {{--<div class="col-md-6 align-self-end text-right">--}}
            {{--<a style="color: #FFFFFF" class="btn btn-success" data-toggle="collapse" data-target="#demo">Modify Slot Time</a>--}}
        {{--</div>--}}
    {{--@endif--}}

    {{--<div id="demo" class="collapse mt-20 col-md-12 box-shadow bg-white p-3 mb-20">--}}

        {{--{!! Form::open(['url'=>'','method'=>'post','class'=>'updateTimeSLots']) !!}--}}
        {{--<input type="hidden" value="primary" name="primaryScheduler">--}}

        {{--<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />--}}
        {{--<input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="template_id_slots">--}}
        {{--<input type="hidden" value="{{$variables['assetId']}}" name="asset_id"  id="event_asset_id" class="event_asset_id">--}}
        {{--<input type="hidden" value="{{$variables['spectatorsId']}}" name="spectatorsId"  id="spectatorsId_slots">--}}
        {{--<input type="hidden" value="{{$variables['primary_asset_id']}}" name="primary_asset_id"  id="show_id_slots">--}}
        {{--<input type="hidden" value="{{$variables['show_id']}}" name="show_id"  id="show_id_slots">--}}
        {{--<input type="hidden" value="" name="form_id"  class="formId">--}}

        {{--<div class="row" style="padding: 20px 15px 0px 30px">--}}
            {{--<div class="info">--}}
                {{--<p style="display: none" class="timeSlotUpdate text-center alert alert-success"></p>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="row">--}}


            {{--<div class="col-md-3">--}}

                {{--<fieldset class="form-group">--}}
                    {{--<input  placeholder="Enter Minutes" required onkeypress="return isNumber(event)" class="form-control form-control-sm" id="reminderMinutes" name="reminderMinutes" type="number"  min="" step="1" id="reminderMinutes" >--}}
                {{--</fieldset>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="row">--}}

            {{--<div class="col-sm-9" >--}}
                {{--<fieldset class="form-group">--}}
                    {{--<input required   placeholder="Reason to update Schedual time" class="form-control form-control-lg" name="reason" />--}}
                {{--</fieldset>--}}
            {{--</div>--}}


            {{--<div class="col-sm-3">--}}
                {{--<input type="submit" value="Update Time"  class="btn btn-primary" />--}}
            {{--</div> </div>--}}


        {{--{!! Form::close() !!}--}}

    {{--</div>--}}




{{--</div>--}}

                <div class="row">

                    <div class="col-sm-12">
                        @if($templateType==TRAINER)
                        <div class="row mb-15 ml-10">
                        <div class="col-md-1 pl-0 pr-0 pt-5"><strong>Select Scheduler</strong></div><div class="col-md-10">
                            <select name="showSelected" class="selectpicker" onchange="location = this.options[this.selectedIndex].value;">
                                @foreach($schedulers as $sh)
                                <option {{($show_id==$sh)?'selected':''}} value="{{URL::to('master-template') }}/assets/primarySchedular/{{nxb_encode($variables['templateId'])}}/{{nxb_encode($variables['primary_asset_id'])}}/{{nxb_encode($sh)}}">{{getShowName($sh)}}</option>
                                @endforeach
                             </select>
                        </div>
                    </div>
                     @endif
                        <div class="row">
                            <div class="col-sm-12">

                                <div id="calendarContainer"  class="SchedularContainerCustom">
                                    {!! $calendar->calendar() !!}
                                    {!! $calendar->script() !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

        </div>
    </div>

    <div id="eventContent" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel3">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">

                <div class="modal-header">
                    {{--<h4 class="modal-title" id="exampleModalLabel">Appointments  Class : (<span class="assetsTitle"></span>) User: (<span class="userTitle"></span>)</h4>--}}
                    <h4 class="modal-title" id="exampleModalLabel">Training Appointment </h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                {!! Form::open(['url'=>'','method'=>'post','id'=>'facilityAddNotes']) !!}

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
                                <div class="col-sm-3 courseContainer">
                                    <div class="form-group">
                                        <label>Select Asset</label>
                                        <select name="asset_id"  class="mySelect">
                                            <option value="">Select Asset</option>
                                            @for($i=0;$i<count($subAsset);$i++)
                                                <option value="{{$subAsset[$i]}}">{{GetAssetNamefromId($subAsset[$i])}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <select id="timeFrom" name="timeFrom" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <select id="timeTo" name="timeTo"  class="form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div id="myDiv" style="color: red; padding-left: 16px; padding-bottom: 10px;"></div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea style="background: #ffffff" name="notes" class="notes form-control form-control-lg" id="notes"></textarea>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <h2 class="modal-title" id="exampleModalLabel">Feedbacks</h2>


                    <div class="app-actions appFeedback" style="width:100%;display:block">
                        <div class="row">
                            <?php $feedBack_Forms = getFeedBackForFacility($variables['templateId']) ?>
                            @if($feedBack_Forms->count())
                                @foreach($feedBack_Forms as $form)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <a style="background: #28a0e5;color: #FFF;" target="_blank" href="{{URL::to('master-template')}}/schedular/faciltyFeedBack/{{nxb_encode($variables['templateId'])}}/{{nxb_encode($form->id)}}/{{$variables['primary_asset_id']}}" class="app-action-link feeds">{{$form->name}}</a>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>

                    <h2 class="modal-title" id="exampleModalLabel">Profiles</h2>

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
                                <div data-id="" class="col-md-8 schedulerProfileView"> No Profile attached for this application</div>
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
                            {{--<input type="hidden" value="{{$variables['assetId']}}" name="asset_id"  id="event_asset_id" class="event_asset_id">--}}
                            <input type="hidden" value="{{$variables['show_id']}}" name="show_id"   class="showId">

                            <input type="hidden" value="" name="masterScheduler"  id="masterScheduler" class="masterScheduler">

                            <input type="hidden" value="" name="backgrounbdSlotId" class="backgrounbdSlotId">
                            <input type="hidden" value="{{$variables['primary_asset_id']}}" name="schedule_id"   class="schedule_id">

                            <input type="hidden" value="" name="event_id" class="event_id">

                            <input type="hidden" value="" name="restriction_id"   class="restriction_id">

                            <input type="hidden" value="" name="ClassHorse"   class="horse_id">

                            <input type="hidden" value="" name="asset_id"  id="asset_id" class="assetId">
                            <input type="hidden" value="" name="is_multiple_selection"  id="is_multiple_selection"  class="is_multiple_selection">

                            <div class="modal-footer mt-20">
                                <input type="submit" value="Save"  id="markSave" class="btn btn-sm btn-success btn-invite-more markSave" />
                                <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-defualt" />
                                <input type="button" value="Mark Done"  id="facilityMarkDone"  class="btn btn-sm btn-success markDone" />
                                <input type="button" value="Send Reminder"  id="sendReminder"  class="btn btn-sm btn-success sendReminder" />
                            </div>

                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="eventsUsers" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document"  style="width: 1000px;">
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


    <div id="confirm" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel2">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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
    <div id="masterInviteRiders" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px!important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Invite Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>


                {!! Form::open(['url'=>'','method'=>'post','id'=>'masterInvite']) !!}

                <div class="modal-body">

                    <div class="row" style="padding: 0px 15px">
                        <div class="info">
                            <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                        </div>
                    </div>

                    <div class="invite-wrapper">
                        <div class="invite-holder">
                            <input type="hidden" name="template_id" value="" class="addtemplateid">

                            <div class="row master">

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Select User</label>
                                        <select  @if($templateType==TRAINER) onchange="getUserCourses('{{$variables['show_id']}}',$(this))" @endif name="users[]"  class="selectUser form-control">
                                            <option value="">Select User</option>
                                            @foreach($userArr as $key=>$v)
                                                <option value="{{$key}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 courseContainer">
                                    <div class="form-group">
                                        <label>Select Asset</label>
                                        <select name="assets[]" required  class="mySelect form-control selectpicker">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2 ClassHorse">
                                    <div class="form-group">
                                        <label for="">Select Horse</label>
                                        <select class="form-control selectpicker" required>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <select id="timeFromInvite" name="timeFrom[]"  class="form-control selectpicker">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <select id="timeToInvite" name="timeTo[]"  class="form-control selectpicker">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <a  href="javascript:" class="addRow mt-30 btn-primary btn"> Add More </a>
                                </div>


                            </div>

                            <div class="row">

                                <div id="myDiv" style="color: red; padding-left: 16px; padding-bottom: 10px;"></div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea style="background: #ffffff" name="notes" class="notes form-control form-control-lg" id="notes1"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <div class="row">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <input type="hidden" value="" name="startTime" id="startTime1"   class="startTime"/>
                            <input type="hidden" value="" name="endTime"  id="endTime1" class="endTime" />

                            <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="" class="templateId">
                            <input type="hidden" value="" name="userId"  id="userId1" class="userId">
                            <input type="hidden" value="{{$variables['show_id']}}" name="show_id"  id="showId1"  class="showId">
                            <input type="hidden" value="" name="is_multiple_selection"  id=""  class="is_multiple_selection">
                            <input type="hidden" value="" name="form_id"  class="form_id formId">
                            <input type="hidden" value="" name="masterScheduler"  id="masterScheduler1" class="masterScheduler">
                            <input type="hidden" value="" name="backgrounbdSlotId"   class="backgrounbdSlotId">
                            <input type="hidden" value="" name="restriction_id"   class="restriction_id">

                            {{--<input type="hidden" value="" name="horse_id"   class="horse_id">--}}

                            <input type="hidden" value="" name="schedual_id"   class="schedule_id">

                            <div class="modal-footer">
                                <button type="submit"  id="markSave" class="btn btn-primary btn-invite-more">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>


                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>


    <!-- Tab containing all the data tables -->

    <input type="hidden" value="30" id="slots_duration">


@endsection

@section('footer-scripts')


<script>


    var calId='{{$calendar->getId()}}';
    var dateFrom ='{{$dateFrom}}';
    var feedBackAssciated="{{session('feedBackAssciated')}}";

    if(feedBackAssciated==0)
        $(".feedBackBtn").hide();
    else
        $(".feedBackBtn").show();



    $('#timeFrom, #timeTo').change(function () {
        $(".ReasonCon").removeClass('hide');
        $(".reason").attr('required',true);
        if (new Date($('#timeFrom').val()) >= new Date($('#timeTo').val())) {

            $("#myDiv").html('Start Time must be less then the end Time');
        }
        else {

            $("#myDiv").html('');
        }
    });
</script>


    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('/js/vender/moment.min.js') }}"></script>
    <script src="{{ asset('/js/vender/fullcalendar.min.js') }}"></script>
    <script src='{{ asset('/js/vender/home.js?3.3.1-1.6.1-3') }}'></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/fullcalendar.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-tooltip.js') }}"></script>
    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>

    {{--<script type="text/javascript" src="{{ asset('/js/schedular-calendar.js') }}"></script>--}}
            <script type="text/javascript" src="{{ asset('/js/calendar.js') }}"></script>
            <link href="{{ asset('/css/calendarView.css') }}" rel="stylesheet" />

            <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>

    <script>
//        function participateInEvent(id,slots_Time,isViewDetail,type,user_id) {
//
//            if (slots_Time.indexOf(':') > -1)
//            {
//                var segments =  slots_Time.split(':');
//
//                var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])
//
//            }else
//            {
//                var slots_duration = parseInt(slots_Time*60);
//            }
//            var url = '/master-template/getEventsData/'+id;
//
//            $.ajax({
//                url: url,
//                type: "GET",
//                beforeSend: function (xhr) {
//                    var token = $('#csrf-token').val();
//                    if (token) {
//                        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
//                    }
//                },
//                success: function (data) {
//
//                    $(".restriction_id").val(data.restriction_id);
//                    $(".is_multiple_selection").val('1');
//                    $(".multiple_scheduler_key").val(data.multiple_scheduler_key);
//                    $(".scheduler_type").val(type);
//                    $(".is_rider").val(1);
//
//
//                    $("#eventContent").modal("show");
//                    $("#eventContent").addClass("show");
//
//                    if(isViewDetail==1)
//                    {
//                        $("#eventsUsers").modal("hide");
//
//                        if(type==1)
//                            $("#eventContent").css('z-index',99999);
//                        $("#schedule_id").val(id);
//                    }else {
//                        $("#schedule_id").val('');
//                    }
//
//                    //console.log(data.asset_id+","+data.show_id+","+data.user_id);
//                   // getHorseAssets(data.show_id,data.asset_id,data.restriction_id,user_id);
//
//
//                    var time_from = data.timeFrom.split(" ");
//                    var time_to = data.timeTo.split(" ");
//
//                    var startDate = time_from[0];
//                    var startTime = time_from[1];
//
//                    var endDate = time_to[0];
//                    var endTime = time_to[1];
//                    populate2(startTime, endTime, startDate, endDate,false,false,slots_duration);
//                    if(type==2) {
//                        $(".selectpicker").selectpicker();
//                        setTimeout(function () {
//                            $(".ClassHorsess").attr("disabled", true);
//                        }, 500);
//                    }
//                }, error: function () {
//                    alert("error!!!!");
//                }
//            }); //end of ajax
//
//
//        }


        $(document).ready(function () {

            $("#calendar-" + calId).on("click", ".fc-bgevent", function (event) {

                var spectatorsId = $("#spectatorsId").val();

//                if(spectatorsId!='')
//                    return false;
                $(".courseContainer").show();
                $(".ClassHorse").show();
                var slots_Time = $("#slots_duration").val();

                if (slots_Time.indexOf(':') > -1)
                {
                    var segments =  slots_Time.split(':');

                    var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

                }else
                {
                    var slots_duration = parseInt(slots_Time*60);

                }

                $('.removeRow').trigger("click");

                $(this).clone(true);
                $("#myDiv").html('');

                $(".markDone").hide();


                var restrictionVal;

                restrictionVal = $(this).data().restrictionType;

                EndDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

                endTime = '23:45';

                startTime = moment(window.selectedTime.replace('pm','')).format("HH:mm");


                startDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

                $(".endTime").val(EndDate + ' ' + endTime);
                $("#masterInviteRiders").addClass("show");
                $("#masterInviteRiders").modal("show");


                $(".is_multiple_selection").val($(this).data().is_multiple_selection);
                $(".templateId").val($(this).data().template_id);
                $(".form_id").val($(this).data().form_id);

                $(".restriction_id").val($(this).data().restriction_id);

                $(".backgrounbdSlotId").val($(this).data().id);
                populateMaster(startTime, endTime, startDate, EndDate,restrictionVal,slots_duration);
                $('.selectUser').selectpicker("val",'');
                $('.mySelect').selectpicker("val",'');
                $('.ClassHorse').selectpicker("val",'');

            });

            var id = "{{session('feedBackAssciated')}}";

            $("#event_asset_id").val("{{session('assetId')}}");
            $("#asset_id").val("{{session('assetId')}}");

            if (id == 0)
                $(".feedBackBtn").hide();
            else
                $(".feedBackBtn").show();
        });
    </script>
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

    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
    {

        width: 190px!important;

    }
.fc-event-container p{ line-height: 1rem!important;}


</style>

@endsection
