
$(document).ready(function () {

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
                        "userId": data['user_id'],
                        "endDaterestriction": endTime,
                        "description":   data['description'],
                        "slots_duration":   data['slots_duration'],
                        "reason":   data['reason'],
                        "assets":data['assets']

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
                        "assets":data['assets']


                    };

                    $('#calendar-' + calId).fullCalendar('removeEvents', data['id']);
                    $('#calendar-' + calId).fullCalendar('renderEvent', newEvent, true);
                    $('#calendar-' + calId).fullCalendar('refetchEvents');


                }, 1000);

            }
        });
    });

});

