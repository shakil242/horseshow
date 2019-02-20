var calId;
$(document).ready(function () {

    if (dateFrom != '')
        $('#calendar-' + calId).fullCalendar('gotoDate',dateFrom);

    // $('.selectpicker').selectpicker('refresh');

    $('#datepicker').datepicker({
        inline: true,
        onSelect: function (dateText, inst) {
            var d = new Date(dateText);
            //   console.log(d);
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
            data: $("#facilityAddNotes").serialize(),
            success: function (data) {

                $(".addNotesMessage").toggle('slow');
                $(".addNotesMessage").html(data['success'])
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

        // if(timeFrom >= timeTo) {
        //     $("#myDiv").html('Start Time must be less then the end Time');
        //     return false;
        // }

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

                $(".addNotesMessage").toggle('slow');
                ;
                $(".addNotesMessage").html(data['success'])

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
                        "userId": data['user_id'],


                    };

                    $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');
                    if (masterScheduler == 1)
                        getCalendar(assetId, templateId, formId,showId);

                }, 1000);

            }
        });
    });

    $('.addRow').on('click', function (e) {

      //  jcf.customForms.destroyAll();

       $('.selectpicker').selectpicker('destroy');
        var clone = $(this).parent().parent();


        var Clone = clone.clone();

        Clone.find('.addRow').text('Remove').removeClass('addRow').addClass('removeRow').end()
            .insertAfter(clone);


        Clone.find('.ClassHorse').html('<label>Select Horse</label>\
                <select class="selectpicker form-control">\
            <option value="">No Horse Selected</option>\
        </select>');

        $('.selectpicker').selectpicker();

        $('.removeRow').on('click', function (e) {
            $(this).parent().parent().remove();
        });

      //  jcf.customForms.replaceAll();
    });

    var isValidEvent = function (start, end) {
        return $("#calendar-" + calId).fullCalendar('clientEvents', function (event) {
                return (event.rendering === "background" && //Add more conditions here if you only want to check against certain events
                (start.isAfter(event.start) || start.isSame(event.start, 'minute')) &&
                (end.isBefore(event.end) || end.isSame(event.end, 'minute')));
            }).length > 0;
    };



    // $("#timeFrom").selectpicker('refresh');
    // $("#timeTo").selectpicker('refresh');

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


                //  console.log(data);

                $(".addNotesMessage").toggle('slow');
                $(".addNotesMessage").html(data['success']);
                setTimeout(function () {
                    $("#addNotes")[0].reset();
                    $('#eventContent').modal('hide');
                    $('.addNotesMessage').html('');
                    $(".schedule_id").val();
                    $(".addNotesMessage").toggle();

                    var newEvent = {
                        title:data['description'],
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
                        "description":   data['description'],
                        "slots_duration":   data['slots_duration'],
                        "reason":   data['reason']

                    };

                    eventsdate = moment(startDateCal).format('hh:mm:ss');
                    eventedate = moment(enddateCal).format('hh:mm:ss');

                    $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');

                    // if (masterScheduler == 1)
                    //     getCalendar(assetId, templateId, formId);

                }, 1000);
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $('#updateTimeSLots').on('submit', function (e) {
        e.preventDefault();

        var templateId = $("#template_id_slots").val();
        var assetId = $("#event_asset_id").val();
        var showId = $("#show_id_slots").val();
        var formId = $("#form_id").val();

        var url = '/master-template/schedular/updateTimeSlots';
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
                $("#updateTimeSLots")[0].reset();
                $(".timeSlotUpdate").toggle('slow');
                $(".timeSlotUpdate").html(data['success']);
                setTimeout(function () {
                    $(".timeSlotUpdate").toggle();
                    $('.timeSlotButton').trigger("click");
                    getCalendar(assetId, templateId, formId,showId);

                }, 2000);

            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $('#masterInvite').on('submit', function (e) {
        e.preventDefault();

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        // var timeFrom = $("#timeFrom").val();
        // var timeTo = $("#timeTo").val();

        var templateId = $(".templateId").val();
        var assetId = $(".assetId").val();
        var formId = $(".formId").val();

        var masterScheduler = $(".masterScheduler").val();

        var endTime = $("#endTime").val();
        //
        // var startDateCal = moment(timeFrom).format('YYYY/MM/DD HH:mm');
        // var enddateCal = moment(timeTo).format('YYYY/MM/DD HH:mm');
        //
        var endTimeDate = moment(endTime).format('YYYY/MM/DD HH:mm');

        var notes = $("#notes").val();
        //
        // if (new Date(timeFrom) >= new Date(timeTo)) {
        //     $("#myDiv").html('Start Time must be less then the end Time');
        //     return false;
        // }

        var url = '/master-template/PrimarySchedular/sendInvite';
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
                    $("#masterInvite")[0].reset();
                    $('#masterInviteRiders').modal('hide');
                    $('.addNotesMessage').html('');
                    $(".schedule_id").val();
                    $(".addNotesMessage").toggle();

                    // parseJSON.data['results'];
                    console.log(data);

                    var obj = jQuery.parseJSON(data['results']);
                     console.log(obj);
                    var newEvents = [];

                    $.each(obj.users, function(key,value) {
                        if(obj.is_multiple_selection==1)
                        {

                            var startDateCal = moment(obj.timeFrom[key]).format('YYYY/MM/DD HH:mm:ss');
                            var enddateCal = moment(obj.timeTo[key]).format('YYYY/MM/DD HH:mm:ss');
                            //
                            // var title = '<a href="javascript:" onclick="getGroupsParticipants(\'' + data['show_id'] + '\',\'' +data['schedual_id']+ '\',\'' + data['slots_duration'] + '\',\'' + startDateCal+ '\',\'' + enddateCal+ '\',2,\'' + data['restriction_id']+ '\')" class="viewBtn"   >View participants</a><br>\
                            //     <a href="javascript:"  onclick="participateInGroup(\'' + data['id']+ '\',\''+data['slots_duration'] + '\',0,2,\''+data['user_id'] + '\')" class="viewBtn participantLink">Participate</a>';


                            var title = '<a href="javascript:" style="width:100%" onclick="getGroupsParticipants(\'' + obj.show_id + '\',\'' +obj.backgrounbdSlotId + '\',\'' + obj.slots_duration[key] + '\',\'' + obj.customDateFrom[key]+ '\',\'' + obj.customDateTo[key]+ '\',2,\'' + obj.restriction_id+ '\')" class="viewBtn"   >View participants</a><br>\
                                <a href="javascript:"  onclick="participateInGroup(\'' + obj.id[key]+ '\',\''+ obj.slots_duration[key]+ '\',0,2)" class="viewBtn participantLink">Participate</a>';


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
                                "asset_id":obj.assets[key],
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
                            var newEvent = {
                                title: obj.userName[key],
                                start: obj.timeFrom[key],
                                end: obj.timeTo[key],
                                id: obj.schedulerId[key],
                                "restrictionType": 2,
                                "endDaterestriction": endTime,
                                "description": obj.notes,
                                "show_id": obj.show_id,
                                "asset_id": obj.assets[key],
                                "restriction_id": obj.restriction_id,
                                "horse_id": obj.ClassHorse[key],
                                "scheduler_id": obj.backgrounbdSlotId,
                                "userId": value,
                                "formId": obj.form_id,
                                "template_id": obj.template_id,
                                "slots_duration": obj.slots_duration[key],

                            };
                            //  $('#calendar-' + calId).fullCalendar('removeEvents', obj.schedulerId[key]);

                            newEvents.push(newEvent);
                        }
                    });
                    $('#calendar-' + calId).fullCalendar('addEventSource', newEvents,true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');
                    $('.selectpicker').selectpicker('refresh');

                    // if (masterScheduler == 1)
                    //    getCalendar(assetId, templateId, formId);// have to crevert it back.

                }, 1000);
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $('#facilityAddNotes').on('submit', function (e) {
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

        var url = '/master-template/schedular/addFaciltyNotes';
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
                 console.log(data);

                $(".addNotesMessage").toggle('slow');
                $(".addNotesMessage").html(data['success']);
                setTimeout(function () {
                    $("#facilityAddNotes")[0].reset();
                    $('#eventContent').modal('hide');
                    $('.addNotesMessage').html('');
                    $(".schedule_id").val();
                    $(".addNotesMessage").toggle();
                    if(data['is_multiple_selection']==1)
                    {

                        var title = '<a style="width: 100%" href="javascript:" onclick="getGroupsParticipants(\'' + data['show_id'] + '\',\'' +data['schedual_id']+ '\',\'' + data['slots_duration'] + '\',\'' + data['customDateFrom']+ '\',\'' + data['customDateTo']+ '\',2,\'' + data['restriction_id']+ '\')" class="viewBtn"   >View participants</a><br>\
                                <a href="javascript:"  onclick="participateInGroupRider(\'' + data['id']+ '\',\''+data['slots_duration'] + '\',0,2,\''+data['user_id'] + '\')" class="viewBtn participantLink">Participate</a>';


                        var newEvent = {
                            title:  title,
                            start: startDateCal,
                            end: enddateCal,
                            id: data['id'],
                            "backgroundColor":"green",
                            "restrictionType": 2,
                            "horse_id": data['horse_id'],
                            "notes": data['notes'],
                            "asset_id": data['asset_id'],
                            "show_id": data['show_id'],
                            "template_id": data['template_id'],
                            "userId": data['user_id'],
                            "endDaterestriction": endTime,
                            "description": '',
                            "slots_duration":   data['slots_duration'],
                            "reason":   data['reason'],
                            "asset_name":data['asset_name'],
                            "asset_user":data['asset_user'],
                            "is_multiple_selection":   data['is_multiple_selection'],

                        };
                      //  return false;
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
                            "userId": data['user_id'],
                            "endDaterestriction": endTime,
                            "description": data['description'],
                            "slots_duration": data['slots_duration'],
                            "reason": data['reason'],
                            "asset_name": data['asset_name'],
                            "asset_user": data['asset_user'],
                            "is_multiple_selection": data['is_multiple_selection'],

                        };
                    }
                    // if(data['is_multiple_selection']==1){
                    //     return false;
                    // }

                    eventsdate = moment(startDateCal).format('hh:mm:ss');
                    eventedate = moment(enddateCal).format('hh:mm:ss');

                    $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');

                    // if (masterScheduler == 1)
                    //     getCalendar(assetId, templateId, formId);

                }, 1000);
            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });

    $("#facilityMarkDone").unbind("click").click(function (e) {
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

        // if(timeFrom >= timeTo) {
        //     $("#myDiv").html('Start Time must be less then the end Time');
        //     return false;
        // }

        var url = '/master-template/schedular/facilityMarkDone';


        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $("#facilityAddNotes").serialize(),
            success: function (data) {

                $(".addNotesMessage").toggle('slow');
                ;
                $(".addNotesMessage").html(data['success']);

                setTimeout(function () {
                    $('#eventContent').modal('hide');
                    $(".addNotesMessage").toggle();

                    var newEvent = {
                        title: data['description'],
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
                        "userId": data['user_id'],
                        "assets":data['assets']


                    };

                    if(data['is_multiple_selection']==1)
                    {
                        return false;
                    }


                    $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');


                }, 1000);

            }
        });
    });


});

function delGroupMultiEvent(event, obj,eventId) {
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
function participateInGroup(id,slots_Time,isViewDetail,type,user_id) {


    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);
    }


    $(".courseContainer").show();

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
           // console.log(data);

           // if(!user_id) {
                $(".courseContainer").show();
                $(".ClassHorse").show();
                $(".courseContainer select").attr('required',true);
                $(".ClassHorse select").attr('required',true);
           // }else{
             //  $(".courseContainer").hide();
             //  $(".ClassHorse").hide();
             //  $(".courseContainer select").attr('required',false);
             //  $(".ClassHorse select").attr('required',false);
           // }
            $(".restriction_id").val(data.restriction_id);
            $(".is_multiple_selection").val('1');
            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);
            $(".scheduler_type").val(type);
            $(".schedule_id").val(data.id);
            $(".backgrounbdSlotId").val(data.schedual_id);
            $(".horse_id").val(data.horse_id);

            $(".is_rider").val(1);


            getfeedBackLinks(id,data.template_id);


            if(type==2) {
                $("#masterInviteRiders").modal("show");
                $("#masterInviteRiders").addClass("show");
                var slotFrom = 'timeFromInvite';
                var slotTo = 'timeToInvite';
            }else {
                $("#eventContent").modal("show");
                $("#eventContent").addClass("show");

                getAssets(assets,data.restriction_id,data.show_id,user_id);
                $("#notes").val('');
                var slotFrom = 'timeFrom';
                var slotTo = 'timeTo';
            }
           // $(".mySelect").selectpicker('refresh');


            if(isViewDetail==1)
            {
                $("#eventsUsers").modal("hide");

                if(type==1)
                    $("#eventContent").css('z-index',99999);
                //$("#schedule_id").val(id);
            }else {
               // $("#schedule_id").val('');
            }

            //console.log(data.asset_id+","+data.show_id+","+data.user_id);
            // getHorseAssets(data.show_id,data.asset_id,data.restriction_id,user_id);


            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

            var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populate_time_groups(startTime, endTime, startDate, endDate,false,false,slots_duration,slotFrom,slotTo);
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

function participateInGroupRider(id,slots_Time,isViewDetail,type,user_id) {


    if (slots_Time.indexOf(':') > -1)
    {
        var segments =  slots_Time.split(':');

        var slots_duration =parseInt(segments[0]*60) + parseInt( segments[1])

    }else
    {
        var slots_duration = parseInt(slots_Time*60);
    }


    $(".courseContainer").show();

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
            // console.log(data);


            $(".restriction_id").val(data.restriction_id);
            $(".is_multiple_selection").val('1');
            $(".multiple_scheduler_key").val(data.multiple_scheduler_key);
            $(".scheduler_type").val(type);
            $(".backgrounbdSlotId").val(data.schedual_id);
            $(".is_rider").val(1);

            getfeedBackLinks(id,data.template_id);

                $("#eventContent").modal("show");
                $("#eventContent").addClass("show");

                getAssets(assets,data.restriction_id,data.show_id,user_id);
                $("#notes").val('');
                var slotFrom = 'timeFrom';
                var slotTo = 'timeTo';

            var time_from = data.timeFrom.split(" ");
            var time_to = data.timeTo.split(" ");

            var startDate = time_from[0];
            var startTime = time_from[1];

            var endDate = time_to[0];
            var endTime = time_to[1];

            populate_time_groups(startTime, endTime, startDate, endDate,false,false,slots_duration,slotFrom,slotTo);
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




function getfeedBackLinks(id,template_id) {

    var url = '/master-template/getFeedbackLinks/'+id+"/"+template_id+"/1";

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
            $(".appFeedback").html(data);
        }
    });
}

function populate_time_groups(start, end, dateFrom, dateTo, dateSelectedTo, restrictionVal,slots_duration,timeFromField,timeToField) {

    var timeFrom = $("#"+timeFromField);
    var timeTo = $("#"+timeToField);

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

    var endMinutes = startMinutes+(+slots_duration);

    timeFrom.html('');
    timeTo.html('');

    getTimerGroup(startMinutes,timeFrom,dateFrom,dateTo,dateSelectedTo);
    getTimerGroup(endMinutes,timeTo,dateFrom,dateTo,dateSelectedTo);

    timeFrom.prev('.jcf-unselectable').remove();
    timeFrom.removeClass('jcf-hidden');
    timeFrom.parent().removeClass('jcf-hidden');

    timeFrom.selectpicker('refresh');
    timeTo.selectpicker('refresh');


}

function getTimerGroup(startMinutes,timeVal,dateFrom,dateTo,dateSelectedTo) {
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


function getGroupsParticipants(show_id,asset_id,slots_Time,dateFrom,dateTo,type,restriction_id) {


    var url = '/master-template/getGroupParticipants/'+show_id+'/'+asset_id+'/'+dateFrom+'/'+dateTo+'/'+type+'/'+slots_Time+'/'+restriction_id;

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

            $("#eventContent").modal("hide");

            $("#eventsUsers").modal("show");
            $("#eventsUsers").addClass("show");

            $("#eventsUserCon").html(data);


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


            console.log(data);

            $(".courseContainer").hide();
            $(".ClassHorse").hide();

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

            $(".backgrounbdSlotId").val(data.schedual_id);

            $('.mySelect').attr('disabled','disabled');
            $(".schedule_id").val(data.id);
            $(".userId").val(data.user_id);
            $(".form_id").val(data.form_id);
            $(".horse_id").val(data.horse_id);


            if(isViewDetail==1)
            {
                $("#eventsUsers").modal("hide");

                if(type==1)
                    $("#eventContent").css('z-index',99999);
                $("#schedule_id").val(id);
            }else {
                $("#schedule_id").val('');
            }

            $(".horse_id").val(data.horse_id);

            getfeedBackLinks(id,data.template_id);


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
//alert(slots_duration);
            populate_time_groups(startTime, endTime, startDate, endDate,false,false,1800,'timeFrom','timeTo');
           // $(".selectpicker").selectpicker('refresh');
            $(".mySelect").selectpicker('refresh')

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax


}



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

function populateMaster(start, end, dateFrom, dateTo, restrictionVal,slots_duration) {



    var timeFrom = $("#timeFromInvite");
    var timeTo = $("#timeToInvite");


    var selectAtt = '';
    var selected = '';


    var startTime = start.split(':'); // split it at the colons
    var endTime = end.split(':'); // split it at the colons

    var startMinutes = (+startTime[0]) * 60 + (+startTime[1]);

    if (restrictionVal == 1) {
        var endMinutes = 23 * 60 + 45;
    }
    else {
        var endMinutes = (+endTime[0]) * 60 + (+endTime[1]);
    }

    timeFrom.html('');
    timeTo.html('');

    var hours, minutes, ampm;

    //console.log("start Time"+startMinutes*60+"endMinute"+endMinutes*60+"slotsDuration"+slots_duration*60);

    for (var i = startMinutes*60; i <= endMinutes*60; i += parseInt(slots_duration)) {


        var minu = i/60;

        hours = minu / 60;

        hours = Math.floor(minu / 60);

        minutes = minu % 60;

        if (minutes < 10) {
            minutes = '0' + minutes; // adding leading zero
        }
        ampm = hours % 24 < 12 ? 'AM' : 'PM';

        var dateval = Date.parse(window.selectedTime);
        var dateval2 = Date.parse(dateFrom + ' ' + hours + ':' + minutes);


        //  var d = Date.parse(dateTo + ' ' + dateSelectedTo);
        //  var d2 = Date.parse(dateTo + ' ' + hours + ':' + minutes);


        var hoursVal = hours;

        hours = hours % 12 || 12; // Adjust hours

        hours = hours;
        if (hours === 0) {
            hours = '00';
        }


        var selected = false;

        var selectAtt = false;


        // var minutes = ConvetMinutesToSeconds(minutes);
        var sign = minutes < 0 ? "-" : "";
        var min = Math.floor(Math.abs(minutes));
        var sec = Math.floor((Math.abs(minutes) * 60) % 60);
        var result = sign + (min < 10 ? "0" : "") + min + ":" + (sec < 10 ? "0" : "") + sec;




        timeFrom.append($('<option></option>')
            .attr('value', dateFrom + ' ' + hoursVal + ':' + result)
            .text(hours + ':' + result + ' ' + ampm)
            .attr('selected', selectAtt)
        );
        timeTo.append($('<option></option>')
            .attr('value', dateTo + ' ' + hoursVal + ':' + result)
            .text(hours + ':' + result + ' ' + ampm)
            .attr('selected', selected)
        );
    }

    timeFrom.prev('.jcf-unselectable').remove();
    timeFrom.removeClass('jcf-hidden');

    timeFrom.selectpicker('refresh');
    timeTo.selectpicker('refresh');
    $(".mySelect").selectpicker('refresh');


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
            if (realScheduler != '1')
                $('#ajax-loading').show();

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            //console.log(data);

            $("#calendarContainer").html(data);
            //$("#calendarContainer").html(data.sas);
            $('#ajax-loading').hide();

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

            $('#ajax-loading').show();

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            //console.log(data);

            $("#calendarContainer").html(data);
            //$("#calendarContainer").html(data.sas);
            $('#ajax-loading').hide();


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

function markDisabled() {

    $("#notes").attr("disabled", true);
    $("#timeFrom").attr("disabled", true);
    $("#timeTo").attr("disabled", true);
    $(".markSave").attr("disabled", "disabled");
    $("#markDone").attr("disabled", "disabled");
    $("#sendReminder").attr("disabled", "disabled");
    $("#facilityMarkDone").attr("disabled", "disabled");
    $("#AssetsCon").attr("disabled", "disabled");
}

function markEnable() {

    $("#notes").attr("disabled", false);
    $("#timeFrom").attr("disabled", false);
    $("#timeTo").attr("disabled", false);
    $(".markSave").attr("disabled", false);
    $("#markDone").attr("disabled", false);
    $("#sendReminder").attr("disabled", false);
    $("#AssetsCon").attr("disabled", false);
    $("#facilityMarkDone").attr("disabled", false);
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
// function getSettingForm(obj, id) {
//
//     var userId = $(obj).attr("data-id");
//
//     window.open("/settings/" + id + "/2/" + userId + "/view", '_blank');
//
// }
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
            if (realScheduler != '1')
                $('#ajax-loading').show();

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $("#calendarContainer").html(data);
            $('#ajax-loading').hide();
            setPositions();

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function  getMultipleHorseAssets(show_id,id,obj,type) {

    if(type==1) {
        var user_id = obj.value;
    }
    else
    {
        var user_id =   id; // for facily scheduler due to data reorder we need to change varialbes here
        var id = obj.value;
    }


    if(id=='')
        return false;

    var restriction_id = $(".restriction_id").val();

    if(user_id!='')
        var url = '/master-template/participant/Horses/'+show_id + "/" + id+"/"+restriction_id+"/multiple/" + user_id;
    else
        var url = '/master-template/participant/Horses/'+ show_id + "/" + id+"/"+restriction_id+"/multiple";

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



function  getHorseAssets(show_id,asset_id,restriction_id,user_id) {


    if(user_id!='')
        var url = '/master-template/participant/Horses/'+show_id + "/" + asset_id+"/"+restriction_id+"/single/" + user_id;
    else
        var url = '/master-template/participant/Horses/'+show_id + "/" + asset_id+"/"+restriction_id+"/single";

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

function  getAssets(assets,restriction_id,show_id,user_id,current_asset) {

    $("#assetsCon").val(assets);

    var url = '/master-template/participant/classes/' + assets+"/"+restriction_id+"/"+show_id+"/"+ user_id+"/"+current_asset;

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
            $(".courseContainer").html(data);
            $('#AssetsCon').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax
}

function getUserCourses(show_id,obj) {

    var user_id = obj.val();

    var restrictionId = $(".restriction_id").val();
    if(user_id=='')
    {
        return false;
    }
    if(user_id!='')
        var url = '/master-template/getCourses/'+ show_id+"/"+ restrictionId+"/" + user_id;

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
            $(obj).closest('.row').find('.courseContainer').html(data);
            $(obj).closest('.row').find('.mySelect').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

}
function getSettingForm(obj, id) {

    var userId = $(obj).attr("data-id");

    window.open("/settings/" + id + "/2/" + userId + "/view", '_blank');

}
    function  getTrainerHorses(show_id,obj,type) {


    if(type==1)
    {
       var typ = 'multiple';
    }else

    {
        var typ = 'single';
    }

    var restriction_id = $(".restriction_id").val();

    var asset_id = obj.value;

    var url = '/master-template/trainerHorses/'+show_id + "/" + asset_id+"/"+typ;

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
            $('.selectpicker').selectpicker('refresh');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax
}