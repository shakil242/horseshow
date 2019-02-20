@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        if($templateType==TRAINER)
        $title = getShowName($variables['show_id']);
        else
        $title = GetAssetNamefromId($variables['show_id']);

        $added_subtitle = Breadcrumbs::render('master-template-list-schedular-forms-schedule');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

    <!-- Content Panel -->
        <div class="white-board">
        <?php $templateType = GetTemplateType($variables['templateId']); ?>

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>


    <div class="row">
            <div class="col-sm-12">
                <div id="calendarContainer" class="SchedularContainerCustom">
                    {!! $calendar->calendar() !!}
                    {!! $calendar->script() !!}
                </div>
            </div>
    </div>
    </div>
    </div>

    <div id="eventContent" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">


                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Training Appointments</h4>
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

                                {{--<div class="col-sm-4" id="Hrs">--}}
                                    {{--<div class="form-group ClassHorse">--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-sm-3" id="StrtTime">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <select id="timeFrom" name="timeFrom" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" id="eTime">
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
                                     <textarea  name="notes" id="notes" class="notes form-control form-control-lg"></textarea>
                                    </div>
                                </div>

                                {{--<div class="col-sm-12 ReasonCon hide" >--}}
                                    {{--<div class="form-group">--}}
                                        {{--<input disabled  placeholder="Reason to update Schedual time" name="reason" class="reason" />--}}
                                    {{--</div>--}}
                                {{--</div>--}}


                            </div>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <div class="row">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <input type="hidden" value="" name="startTime" class="startTime"  id="startTime"/>
                            <input type="hidden" value="" name="endTime"  class="endTime" id="endTime"/>

                            <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="templateId">
                            <input type="hidden" value="{{$variables['userId']}}" name="userId"  id="userId">
                            <input type="hidden" value="{{$variables['associatedKey']}}" name="associatedKey"  id="associatedKey">
                            <input type="hidden" value="{{$variables['isSubParticipant']}}" name="isSubParticipant"  id="isSubParticipant">
                            <input type="hidden" value="{{$variables['subId']}}" name="subId"  id="subId">
                            <input type="hidden" value="" name="schedule_id"  id="schedule_id" class="schedule_id">
                            <input type="hidden" value="" name="assetsCon"  id="assetsCon" class="assetsCon">
                            <input type="hidden" value="" name="is_multiple_selection"  id="is_multiple_selection" class="is_multiple_selection">
                            <input type="hidden" value="" name="horse_id"   class="horse_id">

                            <input type="hidden" value="" name="restriction_id"  id="restriction_id" class="restriction_id">
                            <input type="hidden" value="" name="event_id" class="event_id">



                            <input type="hidden" value="" name="show_id"  id="show_id" class="show_id">
                            <input type="hidden" value="" name="asset_id_edit"  id="asset_id" class="assetId">

                            <input type="hidden" value="" name="backgrounbdSlotId"  id="backgrounbdSlotId" class="backgrounbdSlotId">

                            <div class="modal-footer mt-20">
                                <input type="submit" value="Save"  id="markSave" class="btn btn-sm btn-success btn-invite-more markSave" />
                                <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-defualt" />
                                <input type="button" value="Mark Done"  id="facilityMarkDone"  class="btn btn-sm btn-success markDone" />
                            </div>



                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
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


    <input type="hidden" value="30" id="slots_duration">

@endsection

@section('footer-scripts')

<script>

    var dateFrom ='{{$dateFrom}}';

    var calId='{{$calendar->getId()}}';


</script>

<script src="{{ asset('/js/vender/moment.min.js') }}"></script>
<script src="{{ asset('/js/vender/fullcalendar.min.js') }}"></script>
<script src='{{ asset('/js/vender/home.js?3.3.1-1.6.1-3') }}'></script>
<link rel="stylesheet" href="{{ asset('/css/vender/fullcalendar.min.css') }}"/>
<script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />
<script type="text/javascript" src="{{ asset('/js/vender/bootstrap-tooltip.js') }}"></script>

    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('/js/faciltyCalendar.js') }}"></script>--}}
<script>
    var assets="{{$assets}}";

</script>
<script type="text/javascript" src="{{ asset('/js/calendar.js') }}"></script>
<link href="{{ asset('/css/calendarView.css') }}" rel="stylesheet" />


<script>


    $(document).ready(function () {

        var templateType="{{$templateType}}";


        var id="{{session('feedBackAssciated')}}";

        //$("#event_asset_id").val("{{session('assetId')}}");

        $("#show_id").val("{{$variables['show_id']}}");

        if(id==0)
            $(".feedBackBtn").hide();
        else
            $(".feedBackBtn").show();

        $("#calendar-" + calId).on("click", ".fc-bgevent", function (event) {


            $("#myDiv").html('');

            $(".markDone").hide();


            $("#Hrs").show();
            $("#StrtTime").removeClass("col-sm-6").addClass("col-sm-3");
            $("#eTime").removeClass("col-sm-6").addClass("col-sm-3");
            $("#exampleModalLabel2").hide();
            $("#exampleModalLabel1").show();

            markEnable();

            $(".ReasonCon").addClass('hide');
            $(".reason").attr('required',false);
            var restriction_id = $(this).data().restriction_id;
            var show_id = $("#show_id").val();
            var userId = $("#userId").val();
            $(".restriction_id").val(restriction_id);

            var slots_Time = $("#slots_duration").val();

            // alert(slots_Time);


            $(".courseContainer").show();
            $(".ClassHorse").show();

            if (slots_Time.indexOf(':') > -1)
            {
                var segments =  slots_Time.split(':');

                var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

            }else
            {
                var slots_duration = parseInt(slots_Time*60);

            }

            if(templateType!=4) {
                var assets = $(this).data().assets;
            }else{
                var assets="{{$assets}}";
            }

            getAssets(assets,restriction_id,show_id,userId);

            var restrictionVal;
            restrictionVal = $(this).data().restrictionType;
            $(".is_multiple_selection").val($(this).data().is_multiple_selection);
            $(".schedule_id").val($(this).data().schedule_id);
            restrictionVal = $(this).data().restrictionType;
            $(".restriction_id").val($(this).data().restriction_id);



            EndDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

            endTime = '23:45';

            startTime = moment(window.selectedTime.replace('pm','')).format("HH:mm:ss");

            startDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");


            $("#endTime").val(EndDate + ' ' + endTime);


            $("#eventContent").addClass("show");
            $("#eventContent").modal("show");

            $("#backgrounbdSlotId").val($(this).data().id);
            $('.mySelect').selectpicker("val",'');
            $('.ClassHorse').selectpicker("val",'');
            populate(startTime, endTime, startDate, EndDate,false,restrictionVal,slots_duration);

        });



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

    .fc-event-container p{ line-height: 1rem!important;}

</style>

@endsection