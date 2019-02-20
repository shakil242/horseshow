{!! $calendar->calendar() !!}
{!! $calendar->script() !!}
<!-- For participants schedular -->
@if(isset($champ))
<div class="champion-division hidden">

    <div class="list-group">
        <div class="col-md-12"><h3>{{$champ->division_name}}</h3></div>
        <table id="crudTable2" class="table primary-table">
            <tbody>
           
            <tr>
                <td>Champion</td>
                <td> <?php if(isset($champ->champions)){echo getDivisionChampion($champ);}else{ echo "Not set.";}?> </td>
            </tr>
            <tr>
                <td>Reserve Champion</td>
                <td> <?php if(isset($champ->champions)){echo getDivisionChampion($champ,2);}else{ echo "Not set.";}?> </td>
            </tr>
            </tbody>

          </table>
    </div>

</div>
@endif
@if($existingPositions->count())


    <div class="placing-ajax hidden">
        <div class="list-group">
            <div class="col-md-12"><h3>Placements</h3></div>
            <table id="crudTable2" class="table primary-table">
                <thead class="hidden-xs">
                <tr>
                    <th style="width:25%">#</th>
                    <th>Participant</th>
                </tr>
                </thead>
                <tbody>


            @foreach($existingPositions as $classPosition)

                @php
                $pos_answers = json_decode($classPosition->position_fields,true);
                if($horse_rating_type!=1){
                $pos_answers = record_sort($pos_answers, "score",true);
                array_unshift($pos_answers, "phoney");
                unset($pos_answers[0]);
                }

                @endphp

            {{--<div class="col-md-12"> <h5>{{GetAssetNamefromId($existingPositions->asset_id)}}</h5></div>--}}

                @foreach ($pos_answers as $key => $post)
                    @if(isset($post['horse_id']))
                        <tr>
                            <td style="padding-right: 0px;">
                                <h5 class="text-secondary">
                                    <img class="pr-5" src="{{asset('img/icons/icon-rank.svg')}} "><strong>{!! getPostionText($key) !!}</strong></h5>
                            </td>
                            <td><span style="overflow-wrap: break-word;">{!! getHorseNameAndUserfromid($post['horse_id'],$classPosition->asset_id,$classPosition->show_id,'participant') !!}</span></td>
                        </tr>
                    @endif
                @endforeach


            @endforeach
                </tbody>

            </table>
        </div>
    </div>

@endif
    <input type="hidden" value="{{$slots_duration}}" id="slots_duration">

<link href="{{ asset('/css/vender/bootstrap-dialog.min.css') }}" rel="stylesheet" />
<script src="{{ asset('/js/vender/bootstrap-dialog.min.js') }}"></script>

<style>

    .fc-time-grid-event
    {
        margin-right: 0px!important;

    }
</style>
    <script>
        var isCombined;
    @if($isCombined > 0)
     isCombined = '{{$isCombined}}';

    $(".combined").show();
    $(".combined").html($($.parseHTML("{!! $htmlContent !!}")).text());
    $("#calendarContainer").css({ opacity: 0.5 });
    $(".calendarCon").find("*").prop("disabled", true);
    $(".calendarCon").find("input,select,textarea,button").prop("disabled",true);
    //    $(".calendarCon").attr('disabled','disabled');
    @else
    $(".combined").hide();
    $(".combined").html('');

    $("#calendarContainer").css({ opacity:1 });
    $(".calendarCon").attr('disabled','');
    @endif


    var calId='{{$calendar->getId()}}';
    if('{{$dateFrom}}'!='')
        $('#calendar-'+calId).fullCalendar('gotoDate','{{$dateFrom}}');

    var id="{{session('feedBackAssciated')}}";

    //$("#event_asset_id").val("{{session('assetId')}}");
    $("#asset_id").val("{{$variables['assetId']}}");
    $("#schedule_id").val("{{$variables['scheduler_id']}}");

    $("#show_id").val("{{$variables['show_id']}}");



    if(id==0)
        $(".feedBackBtn").hide();
    else
        $(".feedBackBtn").show();


    $('#timeFrom, #timeTo').change(function () {
        $(".ReasonCon").addClass('hide');
        $(".reason").attr('required',false);
        if (new Date($('#timeFrom').val()) >= new Date($('#timeTo').val())) {

            $("#myDiv").html('Start Time must be less then the end Time');
        }
        else {

            $("#myDiv").html('');
        }
    });

    //$("#calendar-" + calId).unbind('click').click(".fc-bgevent", function (event) {


    $("#calendar-" + calId).on("click", ".fc-bgevent", function (event) {

        if(isCombined>0)
        {
            return false;
        }

$("#myDiv").html('');

        $(".markDone").hide();

        markEnable();

        $(".ReasonCon").addClass('hide');

        $(".reason").attr('required',false);

        var asset_id = $("#asset_id").val();
        var show_id = $("#show_id").val();
        var userId = $("#userId").val();

        var slots_Time = $("#slots_duration").val();

        if (slots_Time.indexOf(':') > -1)
        {
        var segments =  slots_Time.split(':');
        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])
        }else
        {
        var slots_duration = parseInt(slots_Time*60);
        }
       var subInviteeId = $("#subInviteeId").val();
        if(subInviteeId!=0)
        {
            userId = subInviteeId;
        }
        var restriction_id = $(this).data().restriction_id;


        $(".scoreCon").hide(); // hide the score for scheduler as no ride exist


        getHorseAssets(show_id,asset_id,restriction_id,userId);

        var restrictionVal;

        restrictionVal = $(this).data().restrictionType;

        class_group_key = $(this).data().class_group_key;
        is_group = $(this).data().is_group;




        var is_multiple_selection = $(this).data().is_multiple_selection;

        var is_rider_restricted = $(this).data().is_rider_restricted;

        if(is_rider_restricted==1)
        {
        alertBox(" Show will select order of go",'TYPE_INFORMATION');
        return false;
        }

        EndDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

        endTime = '23:45';

        startTime = moment(window.selectedTime.replace('pm','')).format("HH:mm:ss");

        startDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

        $("#endTime").val(EndDate + ' ' + endTime);

        $("#backgrounbdSlotId").val($(this).data().id);
        $("#class_group_key").val(class_group_key);

        $("#restriction_id").val(restriction_id);

        $("#is_multiple_selection").val(is_multiple_selection);

        populate2(startTime, endTime, startDate, EndDate,false,restrictionVal,slots_duration);

        var timeFrom =  $("#timeFrom").val();
        var timeTo =    $("#timeTo").val();

        checkTimeAvailabelity(timeFrom,timeTo,show_id,asset_id);

    });



function  addMoreUsers(event, obj) {

    setTimeout(function () {
        $("#eventContent").modal("show");
        $("#eventContent").addClass("show");

        $("#notes").val('');
        $("#schedule_id").val('');
    },500);

}


</script>

<style>

    .master .col-sm-3 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
    { width: 180px!important;}
    .master .col-sm-2 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
    { width: 125px!important;}
    .col-sm-4 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
    { width: 157px!important;}
    .col-sm-3 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)
    { width: 120px!important;}
</style>