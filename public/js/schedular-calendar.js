$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    jQuery(".multiClass option:first-child").attr("selected", true);
    $(".multiClass").first().change();
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
       var tabId = $(e.target).data('id');
        $("#tab_"+tabId).find(".multiClass").change();
        $("#formId").val(tabId);

    });

});

var calId;

$(document).ready(function () {

    $('.selectpicker').selectpicker('refresh');
    $('#datepicker').datepicker({
        inline: true,
        onSelect: function (dateText, inst) {
            var d = new Date(dateText);
            console.log(d);
            $('#calendar-' + calId).fullCalendar('gotoDate', d);
        }
    });

    $('#datetimepicker8').datetimepicker({
        sideBySide: true,
        format: 'MM/DD/YYYY h:mm A',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }

    });
    $('#datetimepicker9').datetimepicker({
        sideBySide: true,
        format: 'MM/DD/YYYY h:mm A',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"

        }

    });

    $(".sendReminder").unbind("click").click(function (e) {
        e.preventDefault();
        var url = '/master-template/schedular/sendReminder';
        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $("#addNotes").serialize(),
            success: function (data) {

                $(".addNotesMessage").toggle('slow');
                $(".addNotesMessage").html(data['success']);
                setTimeout(function () {
                    $(".addNotesMessage").toggle();
                }, 2000);

            }
        });
    });

    $("#markDone").unbind("click").click(function (e) {
        e.preventDefault();

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var timeFrom = $("#timeFrom").val();
        var timeTo = $("#timeTo").val();

        var endTime = $(".endTime").val();

        var templateId = $(".templateId").val();
        var assetId = $(".assetId").val();
        var formId = $(".formId").val();
        var showId = $(".showId").val();

        var masterScheduler = $(".masterScheduler").val();


        var startDateCal = moment(timeFrom).format('YYYY/MM/DD HH:mm:ss');
        var enddateCal = moment(timeTo).format('YYYY/MM/DD HH:mm:ss');

        var endTimeDate = moment(endTime).format('YYYY/MM/DD HH:mm:ss');

        var notes = $(".notes").val();


        var url = '/master-template/schedular/markDone';

        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $("#addNotes").serialize(),
            success: function (data) {
                if ( typeof data['compulsoryRequired']!== 'undefined' && data['compulsoryRequired']==1){
                    alertBox('Please fill out the information for compulsory feedback forms. ')
                }else{
                    $(".addNotesMessage").toggle('slow');
                    $(".addNotesMessage").html(data['success']);
                    setTimeout(function () {
                        $('#eventContent').modal('hide');
                        $(".addNotesMessage").toggle();

                        var newEvent = {
                            title: notes,
                            start: startDateCal,
                            end: enddateCal,
                            id: data['id'],
                            backgroundColor: '#2ca02c',
                            "restrictionType": 2,
                            "horse_id": data['horse_id'],
                            "asset_id": data['asset_id'],
                            "show_id": data['show_id'],
                            "isMark": 1,
                            "slots_duration":   data['slots_duration'],
                            "endDaterestriction": endTime,
                            "description":  data['description'],
                            "reason":   data['reason'],
                            "formId": data['formId'],
                            "template_id": data['template_id'],
                            "userId": data['user_id']
                        };
                        if(data['scheduler_type']==2 && data['is_multiple_selection']==1)
                        {
                            return false;
                        }
                        $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                        $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                        $('#calendar-' + calId).fullCalendar('refetchEvents');
                        if (masterScheduler == 1)
                            getCalendar(assetId, templateId, formId,showId);

                    }, 1000);
                }

            }
        });
    });

    var isValidEvent = function (start, end) {
        return $("#calendar-" + calId).fullCalendar('clientEvents', function (event) {
                return (event.rendering === "background" && //Add more conditions here if you only want to check against certain events
                (start.isAfter(event.start) || start.isSame(event.start, 'minute')) &&
                (end.isBefore(event.end) || end.isSame(event.end, 'minute')));
            }).length > 0;
    };
    window.selectedTime = null;

    $("#calendar-" + calId).on("click", ".fc-bgevent", function (event) {

        $("#myDiv").html('');

        $(".markDone").hide();

        markEnable(); // this is for enable button of actions before and after mark done

        var restrictionVal;

            restrictionVal = $(this).data().restrictionType;

            EndDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

            endTime = '23:45';

            startTime = moment(window.selectedTime.replace('pm','')).format("HH:mm:ss");

            startDate = moment(window.selectedTime.replace('pm','')).format("YYYY/MM/DD");

            $(".endTime").val(EndDate + ' ' + endTime);

        $(".ReasonCon").addClass('hide');
        $(".reason").attr('required',false);

        $("#eventContent").modal("show");
        $("#eventContent").addClass("show");

        $(".backgrounbdSlotId").val($(this).data().id);

        populate(startTime, endTime, startDate, EndDate, restrictionVal);

    });

    $("#timeFrom").selectpicker('refresh');
    $("#timeTo").selectpicker('refresh');

    $('#addNotes').on('submit', function (e) {
        e.preventDefault();

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var timeFrom = $("#timeFrom").val();
        var timeTo = $("#timeTo").val();

        var templateId = $(".templateId").val();
        var assetId = $(".assetId").val();
        var formId = $(".formId").val();

        var masterScheduler = $(".masterScheduler").val();


        var endTime = $(".endTime").val();

        var startDateCal = moment(timeFrom).format('YYYY/MM/DD HH:mm:ss');
        var enddateCal = moment(timeTo).format('YYYY/MM/DD HH:mm:ss');

        var endTimeDate = moment(endTime).format('YYYY/MM/DD HH:mm:ss');

        var notes = $(".notes").val();

        if (new Date(timeFrom) >= new Date(timeTo)) {
            $("#myDiv").html('Start Time must be less then the end Time');
            return false;
        }

        var url = '/master-template/schedular/addNotes';
        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $(this).serialize(),
            success: function (data) {


              $(".addNotesMessage").toggle('slow');
              $(".addNotesMessage").html(data['success']);
                setTimeout(function () {
                   $("#addNotes")[0].reset();
                   $('#eventContent').modal('hide');
                   $('.addNotesMessage').html('');
                   $(".schedule_id").val();
                   $(".addNotesMessage").toggle();

                    /* to add score we need this check for mark done ***/

                    if(data['isMark']==1)
                    {
                        var newEvent = {
                            title:data['description'],
                            start: startDateCal,
                            end: enddateCal,
                            id: data['id'],
                            backgroundColor: '#2ca02c',
                            "isMark": 1,
                            "restrictionType": 2,
                            "horse_id": data['horse_id'],
                            "notes": data['notes'],
                            "asset_id": data['asset_id'],
                            "show_id": data['show_id'],
                            "template_id": data['template_id'],
                            "schedual_id": data['schedual_id'],
                            "formId": data['formId'],
                            "userId": data['user_id'],
                            "endDaterestriction": endTime,
                            "description":   data['description'],
                            "slots_duration":   data['slots_duration'],
                            "reason":   data['reason'],
                            "is_multiple_selection":   data['is_multiple_selection'],
                            "restriction_id":   data['restriction_id'],
                            'score':   data['score'],
                            'horse_rating_type':   data['horse_rating_type'],
                            "multiple_scheduler_key":   data['multiple_scheduler_key'],



                    };
                    }else {

                        var newEvent = {
                            title: data['description'],
                            start: startDateCal,
                            end: enddateCal,
                            id: data['id'],
                            "restrictionType": 2,
                            "horse_id": data['horse_id'],
                            "notes": data['notes'],
                            "asset_id": data['asset_id'],
                            "show_id": data['show_id'],
                            "template_id": data['template_id'],
                            "formId": data['formId'],
                            "userId": data['user_id'],
                            "endDaterestriction": endTime,
                            "description": data['description'],
                            "slots_duration": data['slots_duration'],
                            "reason": data['reason'],
                            "is_multiple_selection": data['is_multiple_selection'],
                            "restriction_id": data['restriction_id'],
                            'score':   data['score'],
                            'horse_rating_type':   data['horse_rating_type'],
                            "multiple_scheduler_key":   data['multiple_scheduler_key'],
                            "faults_option":data['faults_option']
                        };
                    }


                    if(data['is_multiple_selection']==1 && data['is_update']==1){
                        getPositionsScore(data['asset_id'], data['show_id'],data['restriction_id'],data['formId']);
                        return false;
                    }
                    eventsdate = moment(startDateCal).format('hh:mm:ss');
                    eventedate = moment(enddateCal).format('hh:mm:ss');

                   $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');

                    if(masterScheduler==1)
                    getPositionsScore(data['asset_id'], data['show_id'],data['restriction_id'],data['formId']);

                    // if (masterScheduler == 1)
                    //     getCalendar(assetId, templateId, formId);

                }, 1000);
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $('form.updateTimeSLots').on('submit', function (e) {
        e.preventDefault();


//            message: 'Slot update time should be apply to all the selected class',

        var templateId = $("#template_id_slots").val();
        var assetId = $("#event_asset_id").val();
        var showId = $("#show_id_slots").val();
        var formId = $("#form_id").val();

        var url = '/master-template/schedular/updateTimeSlots';
        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function (data) {
               $(".updateTimeSLots")[0].reset();
                $(".timeSlotUpdate").toggle('slow');
                $(".timeSlotUpdate").html(data['success']);
                setTimeout(function () {
                    $(".timeSlotUpdate").toggle();
                   $('.timeSlotButton').trigger("click");
                    $(".selectpicker").selectpicker('refresh');
                    $('.showTime').trigger("click");
                    $('input.showTime').parent().find("div").removeClass('chk-checked');
                    $('input.showTime').parent().find("div").addClass('chk-unchecked');
                    $('input.showTime').attr("checked",false);
                    getCalendar(assetId, templateId, formId,showId);

                }, 2000);

            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $('.removeRow').on('click', function (e) {
        $(this).parent().parent().remove();
    });

});

function delEvent(event, obj) {
    event.stopPropagation();
    event.preventDefault();

    var spectatorsId = $("#spectatorsId").val();

    if(spectatorsId) {
        return false;
    }

    var id = $(obj).data("id");
    $('#confirm').addClass('show');

    $('#confirm').modal({
        backdrop: 'static',
        keyboard: false
    })
        .one('click', '#delete', function (e) {
            // $(obj).parents('a').remove();
            var url = '/master-template/schedular/deleteNotes/' + id;
            $.ajax({
                url: url,
                type: "get",
                success: function (data) {

                    $('#calendar-' + calId).fullCalendar('removeEvents', id);
                    $('#calendar-' + calId).fullCalendar('refresh');
                    $(".deleteNotesMessage").toggle('slow');
                    $(".deleteNotesMessage").html(data['success']);
                    setTimeout(function () {
                        $('.deleteNotesMessage').html('');
                        $(".deleteNotesMessage").toggle();
                    }, 3000);

                }, error: function () {
                    //  alert("error!!!!");
                }
            }); //end of ajax

        });

}
function delMultiEvent(event, obj,eventId) {
    event.stopPropagation();
    event.preventDefault();

    var spectatorsId = $("#spectatorsId").val();

    if(spectatorsId) {
        return false;
    }

    var id = $(obj).data("id");
    $('#confirm').addClass('show');

    $('#confirm').modal({
        backdrop: 'static',
        keyboard: false
    })
        .one('click', '#delete', function (e) {
            // $(obj).parents('a').remove();
            var url = '/master-template/schedular/deleteMultiNotes/' + id;
            $.ajax({
                url: url,
                type: "get",
                success: function (data) {

                    $('#calendar-' + calId).fullCalendar('removeEvents', eventId);
                    $('#calendar-' + calId).fullCalendar('refresh');
                    $(".deleteNotesMessage").toggle('slow');
                    $(".deleteNotesMessage").html(data['success']);
                    setTimeout(function () {
                        $('.deleteNotesMessage').html('');
                        $(".deleteNotesMessage").toggle();
                    }, 3000);

                }, error: function () {
                    //  alert("error!!!!");
                }
            }); //end of ajax

        });

}

function populate(start, end, dateFrom, dateTo, dateSelectedTo, restrictionVal,slots_duration) {

  // console.log("DateFrom>>>>"+dateFrom+"start>>>>"+start+"DateTo>>>>"+dateTo+"dateSelectedTo>>>>"+dateSelectedTo+"slots_duration>>>>"+slots_duration);

    var timeFrom = $("#timeFrom");
    var timeTo = $("#timeTo");


    var selectAtt = '';
    var selected = '';

    var startTime = start.split(':'); // split it at the colons
    var endTime = end.split(':'); // split it at the colons


    if(startTime.length==3)
        var startMinutes = (+startTime[0]) *60*60 + (+startTime[1]*60)+ (+startTime[2]);
    else
        var startMinutes = (+startTime[0]) *60*60 + (+startTime[1]*60);

    if (restrictionVal == 1) {
        var endMinutes = 23 * 60*60 + 45*60;
    }
    else {
        var endMinutes = (+endTime[0])*60 * 60 + (+endTime[1]*60);
    }

    timeFrom.html('');
    timeTo.html('');

    var hours, minutes, ampm;


    var k=0;
    for (var i = startMinutes; i <= endMinutes; i += parseInt(slots_duration)) {
       k++;

        var minu = i/60;

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
        timeFrom.append($('<option></option>')
            .attr('value', dateFrom + ' ' + hoursVal + ':' + minutes)
            .text(hours + ':' + minutes + ' ' + ampm)
            .attr('selected', selectAtt)
        );
        timeTo.append($('<option></option>')
            .attr('value', dateTo + ' ' + hoursVal + ':' + minutes)
            .text(hours + ':' + minutes + ' ' + ampm)
            .attr('selected', selected)
        );
    }
   // $(".selectpicker").selectpicker('refresh');

   timeFrom.prev('.jcf-unselectable').remove();
   timeFrom.removeClass('jcf-hidden');
    timeFrom.parent().removeClass('jcf-hidden');

   timeFrom.selectpicker('refresh');
   timeTo.selectpicker('refresh');


}
function parseTimestamp(timestampStr) {
    return new Date(new Date(timestampStr).getTime() + (new Date().getTimezoneOffset() * 60 * 1000));
}
function getCalendar(assetId, templateId, formId, showId, realScheduler) {

    var spectatorsId = $("#spectatorsId").val();

    if(spectatorsId!='')
        var spectator = '/'+spectatorsId;
    else
        var spectator = '';

    $(".templateId").val(templateId);
    $(".assetId").val(assetId);
    $(".formId").val(formId);
    $(".showId").val(showId);

    var url = '/master-template/getEvents/' + assetId + "/" + templateId + "/" + formId+ "/" + showId+spectator;

    $.ajax({
        url: url,
        type: "GET",
        dataType: 'html',
        beforeSend: function (xhr) {


            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $("#calendarContainer").html(data);
            //$("#calendarContainer").html(data.sas);

            setFeedbackForms();
            setPositions();
            $(".selectpicker").selectpicker();


        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function getPrimaryCalendar(assetId,template_id) {


    var url = '/master-template/getPrimaryEvents/' + assetId+'/'+template_id;

    $.ajax({
        url: url,
        type: "GET",
        dataType: 'html',
        beforeSend: function (xhr) {


            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            //console.log(data);

            $("#calendarContainer").html(data);
            //$("#calendarContainer").html(data.sas);


            $(".selectpicker").selectpicker();


        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function setPositions(){
    $(".placing-position").html("");
    var placing = $(document).find('.placing-ajax').html();
    $(".placing-position").html(placing);
    $(".selectpickers").selectpicker();
    
    //Adding champion division
    $(".champion-div-display").html("");
    var champion = $(document).find('.champion-division').html();
    $(".champion-div-display").html(champion);
}
function setFeedbackForms(){
    $(".feedBackBtns").html("");
    var link = $(document).find('.linksToFeedbackForm').html();
    $(".feedBackBtns").html(link);
}
function markDisabled() {

    $("#notes").attr("disabled", true);
    $("#timeFrom").attr("disabled", true);
    $("#timeTo").attr("disabled", true);
    $(".markSave").attr("disabled", "disabled");
    $("#markDone").attr("disabled", "disabled");
    $("#sendReminder").attr("disabled", "disabled");
}
function markEnable() {

    $("#notes").attr("disabled", false);
    $("#timeFrom").attr("disabled", false);
    $("#timeTo").attr("disabled", false);
    $(".markSave").attr("disabled", false);
    $("#markDone").attr("disabled", false);
    $("#sendReminder").attr("disabled", false);

}
function goToFeedBack(form_id) {
    var schedule_id = $("#schedule_id").val();
    var spectatorsId = $("#spectatorsId").val();
    if (spectatorsId != '')
        var url = schedule_id+ '/' +form_id+ '/' + spectatorsId;
    else
        var url = schedule_id+ '/' +form_id;

    window.open("/master-template/schedular/feedBack/" + url, '_blank');

}
function hideButtonSpectator() {
    $("#notes").attr("disabled", true);
    $("#timeFrom").attr("disabled", true);
    $("#timeTo").attr("disabled", true);
    $(".markSave").parent().hide();
    $("#markDone").parent().hide();
    $("#sendReminder").parent().hide();
}
function searchMarkDone(e,id) {

    var url = '/master-template/schedular/searchMarkDone/'+id;

    $.ajax({
        url: url,
        type: "POST",
   success: function (data) {
            $(e).hide();
            $(e).parent().html("Done")

           // alert('Success');
        }

    });
}
function getScheduler(show_id,form_id,asset_id,associated_id,templateId, realScheduler) {


    if(!asset_id)
     return false;

    var isSubParticipant =  $("#isSubParticipant").val();

    var subId =  $("#subId").val();

    $(".templateId").val(templateId);
    $(".assetId").val(asset_id);
    $(".show_id").val(show_id);
    $(".formId").val(form_id);


    if(subId > 0)
        var url = '/master-template/list/participant/scheduler/' + show_id + "/" + form_id + "/" + asset_id+ "/" + associated_id+ "/"+isSubParticipant+"/"+subId+"/";
    else
        var url = '/master-template/list/participant/scheduler/' + show_id + "/" + form_id + "/" + asset_id+ "/" + associated_id+ "/0/";

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {


            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $("#calendarContainer").html(data);
            setFeedbackForms();
            setPositions();

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function getMultipleHorseAssets(show_id,asset_id,obj,type) {

    var user_id = obj.value;

        var restrictionId = $("#restriction_id").val();


    if(user_id!='')
        var url = '/master-template/participant/Horses/'+ show_id + "/" + asset_id+"/"+restrictionId+"/multiple/" + user_id;
    else
        var url = '/master-template/participant/Horses/'+ show_id + "/" + asset_id+"/"+restrictionId+"/multiple";

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
                $(obj).closest('.row').find('.ClassHorse').html(data);
                $(obj).closest('.row').find('.ClassHorse').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function getHorseAssets(show_id,asset_id,restrictionId,user_id) {


    if(user_id!='')
    var url = '/master-template/participant/Horses/'+ show_id + "/" + asset_id+"/"+restrictionId+ "/single/" + user_id;
    else
        var url = '/master-template/participant/Horses/'+ show_id + "/" + asset_id+"/"+restrictionId+"/single";

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
           $(".ClassHorse").html(data);
               $('.ClassHorse').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax
}
function getHorseName(horse_id) {

        var url = '/master-template/participant/getHorseName/' + horse_id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            $(".ClassHorse").html(data);
            $('.ClassHorse').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax
}
function populate2(start, end, dateFrom, dateTo, dateSelectedTo, restrictionVal,slots_duration) {

    var timeFrom = $("#timeFrom");
    var timeTo = $("#timeTo");


    var selectAtt = '';
    var selected = '';

    var startTime = start.split(':'); // split it at the colons
    var endTime = end.split(':'); // split it at the colons


    if(startTime.length==3) {
        var startMinutes = (+startTime[0]) * 60 * 60 + (+startTime[1] * 60) + (+startTime[2]);
        var startMinutes = (+startTime[0]) * 60 * 60 + (+startTime[1] * 60) + (+startTime[2]);

    }
    else {
        var startMinutes = (+startTime[0]) * 60 * 60 + (+startTime[1] * 60);
    }

    //console.log(slots_duration);


    var endMinutes = startMinutes+(+slots_duration);

    timeFrom.html('');
    timeTo.html('');



    getTimer(startMinutes,timeFrom,dateFrom,dateTo,dateSelectedTo);
    getTimer(endMinutes,timeTo,dateFrom,dateTo,dateSelectedTo);


    timeFrom.prev('.jcf-unselectable').remove();
    timeFrom.removeClass('jcf-hidden');
    timeFrom.parent().removeClass('jcf-hidden');

    timeFrom.selectpicker('refresh');
    timeTo.selectpicker('refresh');


}
function getSettingForm(obj, id) {

    var userId = $(obj).attr("data-id");

    window.open("/settings/" + id + "/2/" + userId + "/view", '_blank');

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
function InviteInEvent(id,slots_Time) {

    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);

    }

    var url = '/master-template/getEventsData/'+id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);
            $(".is_multiple_selection").val('1');
            $(".restriction_id").val(data.restriction_id);
            $(".form_id").val(data.form_id);
            $(".schedule_id").val(data.schedule_id);
            $(".asset_id").val(data.asset_id);
            $(".backgrounbdSlotId").val(data.schedule_id);

            $("#masterInviteRiders").addClass("show");
            $("#masterInviteRiders").modal("show");
            $("#schedule_id").val('');

            $('.removeRow').trigger("click");
            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

            var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populateMaster(startTime, endTime, startDate, endDate,false,slots_duration);

            $(".selectpicker").selectpicker();


        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax


}
function participateInEvent(id,slots_Time,isViewDetail,type,user_id) {

    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);
    }
    var url = '/master-template/getEventsData/'+id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(".restriction_id").val(data.restriction_id);
            $(".is_multiple_selection").val('1');
            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);
            $(".scheduler_type").val(type);
            $(".is_rider").val(1);


            $("#eventContent").modal("show");
            $("#eventContent").addClass("show");

           if(isViewDetail==1)
           {
               $("#eventsUsers").modal("hide");

              if(type==1)
               $("#eventContent").css('z-index',99999);
               $("#schedule_id").val(id);
           }else {
               $("#schedule_id").val('');
           }

           //console.log(data.asset_id+","+data.show_id+","+data.user_id);
            getHorseAssets(data.show_id,data.asset_id,data.restriction_id,user_id);


            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

           var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populate2(startTime, endTime, startDate, endDate,false,false,slots_duration);
            if(type==2) {
                $(".selectpicker").selectpicker();
                setTimeout(function () {
                    $(".ClassHorsess").attr("disabled", true);
                }, 500);
            }
        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax


}
function viewDetailInEvent(id,slots_Time,isViewDetail,type,horse_id) {

    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);
    }

    var url = '/master-template/getEventsData/'+id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(".masterScheduler").val(1);
            $(".event_asset_id").val(data.asset_id);
            $(".restriction_id").val(data.restriction_id);
            $(".is_multiple_selection").val('1');
            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);

            $(".scheduler_type").val(type);
            $("#notes").val(data.notes);

            $("#eventContent").modal("show");
            $("#eventContent").addClass("show");
            $(".markSave").attr("disabled", false);


            getScoreForScheduler(data.asset_id,data.show_id,data.horse_id,data.restriction_id,data.form_id);

            if(isViewDetail==1)
            {
                $("#eventsUsers").modal("hide");

                if(type==1)
                    $("#eventContent").css('z-index',99999);
                $("#schedule_id").val(id);
            }else {
                $("#schedule_id").val('');
            }
            getHorseName(horse_id);


            // if(data.height!='')
            // {
            //     $("#ClassHeight").show();
            //     $(".heightCon").html(' <label>Height</label><select class="heightCon selectpicker"><option value='+data.height+'>'+data.height+'</option></select>');
            // }else
            // {
            //     $("#ClassHeight").hide();
            // }

            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

            var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populate2(startTime, endTime, startDate, endDate,false,false,slots_duration);
                $(".selectpicker").selectpicker();
                setTimeout(function () {
                    $(".ClassHorsess").attr("disabled", true);
                }, 500);

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax


}
function getEventsParticipants(show_id,form_id,asset_id,slots_Time,dateFrom,dateTo,type,restriction_id) {

    var url = '/master-template/getEventsParticipants/'+show_id+'/'+form_id+'/'+asset_id+'/'+dateFrom+'/'+dateTo+'/'+type+'/'+slots_Time+'/'+restriction_id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $("#eventsUsers").modal("show");
            $("#eventsUsers").addClass("show");

            $("#eventsUserCon").html(data);


        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax



}
function checkTimeAvailabelity(timeFrom,timeTo,show_id,asset_id) {

    var timeFrom = timeFrom.replace("/", "-");
    var timeTo = timeTo.replace("/", "-");
    var timeFrom = timeFrom.replace("/", "-");
    var timeTo = timeTo.replace("/", "-");

    var url = '/master-template/participant/checkTimeAvailability/' + timeFrom + "/" + timeTo+ "/" + show_id+"/"+asset_id+"/1";
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            if(data['results']==1)
            {
               alertBox(data['message'],'TYPE_INFORMATION');
            }else
            {
                $("#eventContent").modal("show");
                $("#eventContent").addClass("show");

            }

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function checkTimeAvailabelityMaster(timeFrom,timeTo,show_id,asset_id) {

    var timeFrom = timeFrom.replace("/", "-");
    var timeTo = timeTo.replace("/", "-");
    var timeFrom = timeFrom.replace("/", "-");
    var timeTo = timeTo.replace("/", "-");

    var url = '/master-template/participant/checkTimeAvailability/' + timeFrom + "/" + timeTo+ "/" + show_id+"/"+asset_id+"/2";
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            if(data['result']!=0) {
                $(".userAlreadyCheck").html(data['result']);
                $(".selectpicker").selectpicker();
            }
            }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function getHorseHeight(id) {

    var url = '/master-template/getHorseHeight/' + id ;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            $("#ClassHeight").show();
            $(".heightCon").html(data);
            $(".selectpicker").selectpicker();

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function selectCombined(asset_id,form_id) {


   // $('a[href="#tab_'+form_id+'"]').tab("show");

   var select = $("#tab_"+form_id).find(".multiClass");

    select.selectpicker('val', asset_id);
  //  select.change();

}

function getPositionsScore(asset_id,show_id,restriction_id,form_id) {

 //   $(".restriction_id").val(restriction_id);
    var url = '/master-template/getPositionsScore/'+asset_id+"/"+show_id+"/"+restriction_id+"/"+form_id ;
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            $(".selectpicker").selectpicker('refresh');

            $(".positionCon").html(data);

            $(".selectpickers").selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    });



}

$(document).on('submit','#SaveHorsePosition', function (e) {
    e.preventDefault();


    $('.placing-position select.check-select-opt').selectpicker('destroy');
    var total = $('.placing-position select.check-select-opt').length;
    var selected_option = $('.placing-position').find("select.check-select-opt option[value!='']:selected").length;

    $('.placing-position select.check-select-opt').selectpicker('refresh');


    var templateId = $("#template_id_slots").val();
    var assetId = $("#event_asset_id").val();
    var showId = $("#show_id_slots").val();
    var formId = $("#form_id").val();
    var url = '/master-template/schedular/add-position';

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function (data) {

                // if(total==selected_option)
                // {
                    alertBox('Calculate champions from champion calculator screen');
               // }

                getCalendar(assetId, templateId, formId,showId);

                $(".positionCon").html(data);
                $(".selectpickers").selectpicker('refresh');

            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax




//            message: 'Slot update time should be apply to all the selected class',




});

function checkScore(obj) {

    if(obj.val()==null) {

        obj.parent().next('.scores').hide();
        obj.parent().next('.scores').val('0');
        obj.parent().next('.scores').prop("disabled","disabled")
    }
    else
    {
        obj.parent().next('.scores').show();
        obj.parent().next('.scores').prop("disabled",false)
    }
}

function getScoreForScheduler(asset_id,show_id,horse_id,restriction_id,form_id) {


    var url = '/master-template/getScoreForScheduler/'+asset_id+"/"+show_id+"/"+horse_id+"/"+restriction_id+"/"+form_id;
    $.ajax({
        url: url,
        type: "GET",
        async: false,
        data: $(this).serialize(),
        success: function (data) {
            $('.score').val(data);
            $('.score').html(data);

            return data;
        }, error: function () {
            alert("error!!!!");
        }

    }); //end of ajax
}

function viewDetailInGroup(id,slots_Time,isViewDetail,type) {

    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);
    }

    var url = '/master-template/getEventsData/'+id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(".masterScheduler").val(1);
            $(".event_asset_id").val(data.asset_id);
            $(".event_id").val(data.id);

            $(".restriction_id").val(data.restriction_id);
            $(".is_multiple_selection").val('1');
            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);

            $(".scheduler_type").val(type);
            $("#notes").val(data.notes);

            $("#eventContent").modal("show");
            $("#eventContent").addClass("show");
            $(".markSave").attr("disabled", false);
            $('select[name=asset_id]').val(data.asset_id);

            $('.mySelect').selectpicker('refresh');

            $('.mySelect').attr('disabled','disabled');

            if(isViewDetail==1)
            {
                $("#eventsUsers").modal("hide");

                if(type==1)
                    $("#eventContent").css('z-index',99999);
                $("#schedule_id").val(id);
            }else {
                $("#schedule_id").val('');
            }
            //getHorseName(horse_id);


            // if(data.height!='')
            // {
            //     $("#ClassHeight").show();
            //     $(".heightCon").html(' <label>Height</label><select class="heightCon selectpicker"><option value='+data.height+'>'+data.height+'</option></select>');
            // }else
            // {
            //     $("#ClassHeight").hide();
            // }

            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

            var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populate_time_groups(startTime, endTime, startDate, endDate,false,false,slots_duration,'timeFrom','timeTo');
            // $(".selectpicker").selectpicker('refresh');
            $(".mySelect").selectpicker('refresh')

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax


}