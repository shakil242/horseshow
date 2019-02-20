$(document).ready(function () {
    $('form#masterInvite').on('submit', function(e) {
        e.preventDefault();

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var templateId = $(".templateId").val();
        var assetId = $(".assetId").val();
        var formId = $(".formId").val();
        var masterScheduler = $(".masterScheduler").val();
         var endTime = $("#endTime").val();
         var endTimeDate = moment(endTime).format('YYYY/MM/DD HH:mm');

        var notes = $("#notes").val();

        var url = '/master-template/schedular/sendInvite';
        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $('#masterInvite').serialize(),
            success: function (data) {


                $(".addNotesMessage").toggle('slow');
                $(".addNotesMessage").html(data['success']);
                setTimeout(function () {
                    $("#masterInvite")[0].reset();
                    $('#masterInviteRiders').modal('hide');
                    $('.addNotesMessage').html('');
                    $(".schedule_id").val();
                    $(".addNotesMessage").toggle();

                    var obj = jQuery.parseJSON(data['results']);
                    var newEvents = [];
                        $.each(obj.users, function (key, value) {

                            if(data['is_multiple_selection']==1)
                            {


                               var title = '<a href="javascript:" onclick="getEventsParticipants(\'' + obj.show_id + '\',\'' +obj.form_id + '\',\'' +obj.asset_id+ '\',\'' + obj.slots_duration[key] + '\',\'' + obj.customDateFrom[key]+ '\',\'' + obj.customDateTo[key]+ '\',2,\'' + obj.restriction_id+ '\')" class="viewBtn"   >View participants</a><br>\
                                <a href="javascript:"  onclick="InviteInEvent(\'' + obj.schedulerId[key]+ '\',\''+ obj.slots_duration[key] + '\')" class="viewBtn participantLink">Participate</a>';


                                var newEvent = {
                                    title:  title,
                                    start: obj.timeFrom[key],
                                    end: obj.timeTo[key],
                                    id: obj.schedulerId[key],
                                    "backgroundColor":"green",
                                    "endDaterestriction": endTime,
                                    "notes":obj.notes,
                                    "description":obj.notes,
                                    "show_id":obj.show_id,
                                    "asset_id":obj.asset_id,
                                    "horse_id":obj.ClassHorse[key],
                                    "scheduler_id":obj.backgrounbdSlotId,
                                    "restriction_id":obj.restriction_id,
                                    "horse_rating_type":data['horse_rating_type'],
                                    "userId":value,
                                    "isMultiple":1,
                                    "is_multiple_selection": obj.is_multiple_selection,
                                    "multiple_scheduler_key":  obj.multiple_scheduler_key,
                                    "formId":obj.form_id,
                                    "template_id":obj.template_id,
                                    "slots_duration":  obj.slots_duration[key],

                                };
                                newEvents.push(newEvent);
                                return false;
                            }else {

                               // alert(obj.horse_rating_type);

                                var newEvent = {
                                    title: obj.userName[key],
                                    start: obj.timeFrom[key],
                                    end: obj.timeTo[key],
                                    id: obj.schedulerId[key],
                                    "restrictionType": 2,
                                    "endDaterestriction": endTime,
                                    "description": obj.notes,
                                    "show_id": obj.show_id,
                                    "notes":obj.notes,
                                    "restriction_id":obj.restriction_id,
                                    "asset_id": obj.asset_id,
                                    "horse_id": obj.ClassHorse[key],
                                    "scheduler_id": obj.backgrounbdSlotId,
                                    "userId": value,
                                    "formId": obj.form_id,
                                    "template_id": obj.template_id,
                                    "slots_duration": obj.slots_duration[key],
                                    "horse_rating_type":data['horse_rating_type'],
                                    "is_multiple_selection": obj.is_multiple_selection,
                                    "multiple_scheduler_key":  obj.multiple_scheduler_key,

                                };

                                newEvents.push(newEvent);
                            }
                        });
                    $('#calendar-' + calId).fullCalendar('addEventSource', newEvents,true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');
                  //  $('.selectpicker').selectpicker('refresh');

                  }, 1000);
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $("#calendar-" + calId).on("click", ".fc-bgevent", function (event) {
        var spectatorsId = $("#spectatorsId").val();

        //alert(spectatorsId);

         if(spectatorsId) {
             return false;
         }
       // alert(isCombined);

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
        markEnable();
        var restrictionVal;
        restrictionVal = $(this).data().restrictionType;

        restriction_id = $(this).data().restriction_id;

        class_group_key = $(this).data().class_group_key;
        is_group = $(this).data().is_group;

        $("#masterInviteRiders").addClass("show");

        $("#masterInviteRiders").modal("show");


        /************* in order to get horse for next time slot after break ********************/




        EndDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

        endTime = '23:45';

        startTime = moment(window.selectedTime.replace('pm','')).format("HH:mm:ss");

        startDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

        $(".endTime").val(EndDate + ' ' + endTime);


        var is_multiple_selection = $(this).data().is_multiple_selection;

        $("#class_group_key").val(class_group_key);

        $("#restriction_id").val(restriction_id);

        $(".is_multiple_selection").val($(this).data().is_multiple_selection);
        $(".restriction_id").val($(this).data().restriction_id);

        $(".backgrounbdSlotId").val($(this).data().id);

        var asset_id = $("#assetId1").val();

        var show_id = $("#showId1").val();

        populateMaster(startTime, endTime, startDate, EndDate,restrictionVal,slots_duration);

        var timeFrom =  $("#timeFromInvite").val();
        var timeTo =    $("#timeToInvite").val();
        if(is_multiple_selection==1)
         return false;
        else
        checkTimeAvailabelityMaster(timeFrom,timeTo,show_id,asset_id);

    });
    $('.addRowMaster').unbind('click').click(function(e) {
    //$('.addRowMaster').on('click', function (e) {
        var clone = $(this).parent().parent();

        var counter = $('.master').length;

        var is_multiple_selection = $(".is_multiple_selection").val();
        var timeFromInvite = clone.find('select#timeFromInvite').val();
        horseArr =[];
        var timeToInvite = clone.find('select#timeToInvite').val();
         horses = $('select[name="ClassHorse[]"] option:selected').each(function () {
             horseArr.push($(this).val());
         });

        var horseJson = JSON.stringify(horseArr);

        var url = '/master-template/scheduler/inviteeMasterScheduler/';

        $.ajax({
            url: url,
            type: "GET",
            data: {users:users,heights:heights,assetId:assetId,showId:showId,timeFromInvite:timeFromInvite,timeToInvite:timeToInvite,counterVar: counter,is_multiple_selection:is_multiple_selection,horseJson:horseJson},
            beforeSend: function (xhr) {

                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            success: function (data) {

                $(".inviteeUsers").append(data);
                $(".selectpicker").selectpicker();
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax
        $('.removeRow').on('click', function (e) {
            $(this).parent().parent().remove();
        });

    });

});


function populateMaster(start, end, dateFrom, dateTo, restrictionVal,slots_duration) {

    var timeFrom = $("#timeFromInvite");
    var timeTo = $("#timeToInvite");

    var selectAtt = '';
    var selected = '';

    var startTime = start.split(':'); // split it at the colons
    var endTime = end.split(':'); // split it at the colons

    var startMinutes = (+startTime[0]) *60*60 + (+startTime[1]*60);

    var endMinutes = startMinutes+(+slots_duration);

    timeFrom.html('');
    timeTo.html('');

   var dateSelectedTo;
    getTimer(startMinutes,timeFrom,dateFrom,dateTo,dateSelectedTo);
    getTimer(endMinutes,timeTo,dateFrom,dateTo,dateSelectedTo);

    timeFrom.prev('.jcf-unselectable').remove();
    timeFrom.removeClass('jcf-hidden');

    timeFrom.selectpicker('refresh');
    timeTo.selectpicker('refresh');
    $(".mySelect").selectpicker('refresh');


}
function  ConvetMinutesToSeconds(minutes) {

    var sign = minutes < 0 ? "-" : "";
    var min = Math.floor(Math.abs(minutes));
    var sec = Math.floor((Math.abs(minutes) * 60) % 60);
    var result = sign + (min < 10 ? "0" : "") + min + ":" + (sec < 10 ? "0" : "") + sec;
}
function getTimer(startMinutes,timeVal,dateFrom,dateTo,dateSelectedTo) {
    var hours, minutes, ampm;


    var minu = startMinutes/60;

    hours = minu / 60;

    hours = Math.floor(minu / 60);


    minutes = minu % 60;

    if (minutes < 10) {
        minutes = '0' + minutes; // adding leading zero
    }


    var sign = minutes < 0 ? "-" : "";
    var min = Math.floor(Math.abs(minutes));
    var sec = Math.floor((Math.abs(minutes) * 60) % 60);
    var minutes = sign + (min < 10 ? "0" : "") + min + ":" + (sec < 10 ? "0" : "") + sec;

    ampm = hours % 24 < 12 ? 'AM' : 'PM';

    //  console.log(dateTo + ' ' + dateSelectedTo);

    var dateval = Date.parse(window.selectedTime);
    var dateval2 = Date.parse(dateFrom + ' ' + hours + ':' + minutes);

    //  console.log(window.selectedTime);


    var d = Date.parse(dateTo + ' ' + dateSelectedTo);
    var d2 = Date.parse(dateTo + ' ' + hours + ':' + minutes);


    var hoursVal = hours;

    hours = hours % 12 || 12; // Adjust hours

    //hours = hours;
    if (hours === 0) {
        hours = '00';
    }

    var selected = false;
    var selectAtt = false;

    if (dateSelectedTo != '') {
        if (d == d2) {
            selected = true;
        }
        else {
            selected = false;
        }


        if (dateval == dateval2) {
            selectAtt = true;
        }
        else {
            selectAtt = false;
        }
    }

    timeVal.append($('<option></option>')
        .attr('value', dateFrom + ' ' + hoursVal + ':' + minutes)
        .text(hours + ':' + minutes + ' ' + ampm)
        .attr('selected', selectAtt)
    );

}

function viewSearchScheduler(id,form_id,asset_id,show_id,template_id,encodeFormId) {

    $(".ls_result_div").hide();
    $('li.tabes').removeClass("active");
    $('li.t-'+form_id+' a ').trigger('click');
    $('li.t-'+form_id).addClass('active');
    $("#formId").val(form_id);
    getCalendar(asset_id,template_id,encodeFormId,show_id);

    //jcf.customForms.destroyAll();
    $('select[name=asset]').val(asset_id);
    $('.multiClass').selectpicker('refresh');
     //jcf.customForms.replaceAll();


}
