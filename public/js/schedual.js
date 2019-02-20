$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

window.onload = function() {
    jQuery('input[type=text]').blur();
    /*
     //For putting the Focus on particular field
     jQuery('[id$=pList]').focus();
     */
};
$("form.form-horizontal").submit(function( event ) {
    //event.preventDefault();
    $(".dataTables_filter .input-sm").val("");
    $(".dataTables_filter .input-sm").keyup();

});


$(document).ready(function() {

    $(".is_group").on('change',function() {
    if($(this).val()==1) {
        $(".multipleSelect").hide();
        $(".multipleSelection").attr('checked',false);
    }else{
        $(".multipleSelect").show();

    }


    });

    $(".allAssets").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
       // console.log('selectedD: ' + selectedD + '  newValue: ' + newValue + ' oldValue: ' + oldValue);
        var selectedD = $(this).find('option').eq(clickedIndex).val();
        var form_id = $(".form_id").val();
        var scheduler_key = $(".scheduler_key").val();
        var show_id = $(".show_id").val();



        if(newValue==false)
        {

            var exitConfirmDialog = new BootstrapDialog.show({
                title: 'Confirmation Box',
                message: 'Are you sure you want to Delete this class?',
                closable: false,
                buttons: [
                    {
                        label: 'No',
                        action: function (dialog) {
                            $(e.target).selectpicker('val',eval(selectedD));
                            dialog.close();
                        }
                    },
                    {
                        label: 'Yes',
                        cssClass: 'btn btn-success',
                        action: function (dialog) {
                            if(scheduler_key!='') {
                                var url = '/master-template/deleteSchedulerClass/' + selectedD + "/" + scheduler_key + "/" + show_id;
                                $.ajax({
                                    url: url,
                                    type: "get",
                                    success: function (data) {
                                        location.reload();

                                    }, error: function () {
                                        alert("error!!!!");
                                    }
                                }); //end of ajax
                            }
                            dialog.close();
                        }
                    }]
            });

            return false;

        }else
        {
               var url = '/master-template/checkAlreadyExist/'+selectedD+"/"+form_id+ "/" + show_id;
                $.ajax({
                    url: url,
                    type: "get",
                    success: function (data) {

                        if(data > 0)
                        {
                            var exitConfirmDialog = new BootstrapDialog.show({
                                title: 'Confirmation Box',
                                message: 'This class is already part of the show. Do you want to select it again?',
                                closable: false,
                                buttons: [
                                    {
                                        label: 'No',
                                        action: function (dialog) {
                                            $(e.target).find('option').eq(clickedIndex).removeAttr("selected");
                                            $('.selectpicker').selectpicker('render');
                                            dialog.close();
                                        }
                                    },
                                    {
                                        label: 'Yes',
                                        cssClass: 'btn btn-success',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });


                        }


                    }, error: function () {
                        alert("error!!!!");
                    }
                }); //end of ajax
        }

        var selectedD = $(this).find('option').eq(clickedIndex).val();

        var tableElement = $(".slot_con_"+show_id+"_"+form_id);
            //$(this).closest('.schedual-restrictions').parent().next().children().find('.display').find('tbody');


        // var url = '/master-template/getTimeSLots/'+selectedD+"/"+form_id;
        // $.ajax({
        //     url: url,
        //     type: "get",
        //     success: function (data) {
        //         if ($(tableElement).find('.asset_'+selectedD).length == 0) {
        //
        //            $(tableElement).prepend(data);
        //
        //         }
        //         else
        //         {
        //             return false;
        //         }
        //     }, error: function () {
        //         alert("error!!!!");
        //     }
        // }); //end of ajax

        //    console.log('selectedD: ' + selectedD + '  newValue: ' + newValue + ' oldValue: ' + oldValue);    });
    });


    $(".scoreAssets").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {


        var selectedD = $(this).find('option').eq(clickedIndex).val();
        var form_id = $(this).data('id');

        if(oldValue==true)
        {
            var scheduler_key = $(".scheduler_key").val();


            var classes = [];
            $.each($(".selectClasses option:selected"), function(){
                classes.push($(this).val());
            });


            classes =  JSON.stringify(classes);

            var exitConfirmDialog = new BootstrapDialog.show({
                title: 'Confirmation Box',
                message: 'Are you sure you want to Delete this class?',
                closable: false,
                buttons: [
                    {
                        label: 'No',
                        action: function (dialog) {
                            dialog.close();
                        }
                    },
                    {
                        label: 'Yes',
                        cssClass: 'btn btn-success',
                        action: function (dialog) {

                            var url = '/master-template/deleteScoreClass/'+scheduler_key+"/"+selectedD+"/"+classes;
                            $.ajax({
                                url: url,
                                type: "get",
                                success: function (data) {
                                }, error: function () {
                                    alert("error!!!!");
                                }
                            }); //end of ajax
                            dialog.close();
                        }
                    }]
            });

            return false;

        }


        //    console.log('selectedD: ' + selectedD + '  newValue: ' + newValue + ' oldValue: ' + oldValue);    });
    });



    $('.checkValidation').on('change keyup mousedown',function(){

        if($(this).val()!='')
        {
            var coup = $(this).closest('.fields');
            coup.find(".allAssets").prop('required',true);
            coup.find(".datetime-control").prop('required',true);

        }else
        {
            var coup = $(this).closest('.fields');

            coup.find(".allAssets").prop('required',false);
            coup.find(".datetime-control").prop('required',false);

        }

    });


    var allAsset = [];
    $('.updateTimeChange').on('apply.daterangepicker', function(ev, picker) {
        var assets =$(this).parent().parent().parent().children('.ClassAssets').find('.selectpicker').val();
        console.log($(this).parent().parent().parent()
            .children('.ClassAssets').find('.selectpicker').val());

        if(allAsset.length>0)
            allAsset +=","+assets;
        else
            allAsset +=assets;

        $(".dateChangeCon").val(allAsset);
    });



    $(document).on('click','.AddMoreSch',function () {

   //  jcf.customForms.destroyAll();

    $(".defaultClasses").removeClass("in");
    var Clone = $('.addMoreCon').clone();
    var addMoreCon = Clone.removeClass("addMoreCon hide");
    var numItems = parseInt($('.panel-default').length+2);

    var cloneNumber =  Clone.children().children().children().attr("data-target","#"+numItems);
    Clone.find(".panel-collapse").attr('id',numItems);
      $(addMoreCon).appendTo( "#accordion" );

    Clone.find('.bootstrap-select > .dropdown-toggle').remove();

    $('.selectpicker').selectpicker('refresh');
    triggerAllmethods();
    initialize();

    $(".allAssets").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
            if(oldValue==true)
                return false;
            var selectedD = $(this).find('option').eq(clickedIndex).val();
            var form_id = $(this).data('id');

            var tableElement = $(this).closest('.schedual-restrictions').parent().next().children().find('.display').find('tbody');

            /******comment this for now if we need it in future we will enable this feature.*************/
            // var url = '/master-template/getTimeSLots/'+selectedD+"/"+form_id;
            // $.ajax({
            //     url: url,
            //     type: "get",
            //     success: function (data) {
            //         if ($(tableElement).find('.asset_'+selectedD).length == 0) {
            //             $(tableElement).prepend(data);
            //         }
            //         else
            //         {
            //             return false;
            //         }
            //     }, error: function () {
            //         alert("error!!!!");
            //     }
            // }); //end of ajax

        });


    // jcf.customForms.replaceAll();
    //
});
});

$(document).on('click','.removeScheduler',function () {
$(this).parent().remove();
});

$(document).ready(function () {

    //.fnAdjustColumnSizing( false )

        $('.display').DataTable({

            "paging": false,
            "info":     true,
            "bFilter": true,
        });
        $('.dataTable').wrap('<div class="dataTables_scroll" />');

    $('#saveSchedulerTime').on('submit', function (e) {
        e.preventDefault();

        var url = '/master-template/schedular/saveSchedulerTime/';
        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function (data) {
                // alert('success')


            }, error: function () {
                alert("error!!!!");
            }
        }); //end of ajax

    });


    $('.slotsTime').on('keydown', function(e) {
        //your code
        if(e.keyCode != 8 && (this.value.length === 2 || this.value.length === 5)) {
            this.value += ":";
        }
        //collapse double colons
        this.value=this.value.replace(/:+/g,":");
    });

});
$('.datetimepicker8').datetimepicker({
    sideBySide: true,
    format: 'MM/DD/YYYY',
    icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    }

});
$("#addClasses").submit(function( event ) {
    event.preventDefault();
    var url = '/master-template/schedular/addRestrictions';

   var scheduler_key = $(".scheduler_key").val();
   var form_id = $(".form_id").val();
   var show_id = $(".show_id").val();

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

            location.reload();

           if(scheduler_key==''){
              $(".scheduler_con_"+show_id+"_"+form_id).prepend(data);
           }else
           {
               $(".scheduler-"+scheduler_key) .html(data);
           }
            $("#addRestrictions").modal('hide');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

});

$("#addReminderForm").submit(function( event ) {
    event.preventDefault();
    var url = '/master-template/schedular/addReminder';

    var scheduler_id = $(".scheduler_id").val();
    var show_id = $(".show_id").val();
    var form_id = $(".form_id").val();

    $.ajax({
        url: url,
        type: "POST",
        // beforeSend: function (xhr) {
        //     var token = $('#csrf-token').val();
        //     if (token) {
        //         return xhr.setRequestHeader('X-CSRF-TOKEN', token);
        //     }
        // },
        data: $(this).serialize(),
        success: function (data) {
            $(".reminder_"+show_id+"_"+form_id).html(data);
            $("#addReminder").modal('hide');

        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax

});


function triggerAllmethods() {

    $('.datetimepicker8').datetimepicker({
        format: 'MM/DD/YYYY',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }

    });


    $('.checkValidation').on('change keyup mousedown',function(){

        if($(this).val()!='')
        {
            var coup = $(this).closest('.fields');
            coup.find(".allAssets").prop('required',true);
            coup.find(".datetime-control").prop('required',true);

        }else
        {
            var coup = $(this).closest('.fields');

            coup.find(".allAssets").prop('required',false);
            coup.find(".datetime-control").prop('required',false);
        }


    });
    $('.reminder-checkbox').change(function () {

        var c = $(this).prop('checked');
        if(c==false)
        {
            $(this).prev().removeClass('chk-checked');
        }else
        {
            $(this).prev().addClass('chk-checked');
        }


        var coup = $(this).closest('.create-form');
        coup.find('.makeRestriction').toggle(this.checked);
    }).change(); //ensure visible state matches initially
    $('.daterange').daterangepicker({

        timePicker: true,
        timePickerSeconds:true,
        timePickerIncrement: 1,
        defaultDate: "",
        autoUpdateInput: false,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        locale: {
            format: 'MM/DD/YYYY h:mm:ss A'
        }
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY h:mm:ss A') + ' - ' + picker.endDate.format('MM/DD/YYYY h:mm:ss A'));
    });
}

function  checkUsef(obj) {
    if(obj.val()!='USEF') {
        obj.parent().next('.usef_number').find('.usefNo').prop('required', false);
    }
    else {
        obj.parent().next('.usef_number').find('.usefNo').prop('required', true);
    }
}

function addRestrictions(scheduler_id,show_id,form_id) {

    $(".form_id").val(form_id);
    $(".show_id").val(show_id);
    $(".scheduler_id").val(scheduler_id);

    $("#addClasses")[0].reset();
     $('.selectClasses').selectpicker('val',"");
     $('.scoreFrom').selectpicker('val',"");
   //  $('.SelectDateAndTime').val('');

    template_id = $("#template_id").val();

    var url = '/shows/getUnSelectedClasses/'+form_id+"/"+show_id+"/"+template_id;
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            $("#ClassesContainer").html(data);
            $(".allAssets").selectpicker();
        }
    });

    $('.scheduler_key').val('');
    $('.multipleSelection').attr('checked',false);
    $('.restrictRiders').attr('checked',false);

    $('.blockTimeTitle').val('');
    $("#addRestrictions").modal('show');
    $("#addRestrictions").addClass('show');
    // $(".modal-backdrop").addClass('show');

}
function addReminder(scheduler_id,show_id,form_id) {
    $(".scheduler_id").val(scheduler_id);
    $(".show_id").val(show_id);
    $(".form_id").val(form_id);



    $("#addReminder").addClass('show');
    $("#addReminder").modal().show();
}

function editReminder(scheduler_id,show_id,form_id,days,houres,minutes) {
    $('.reminderDays').val(days);
    $('.reminderHours').val(houres);
    $('.reminderMinutes').val(minutes);
    $(".show_id").val(show_id);
    $(".form_id").val(form_id);
    $(".scheduler_id").val(scheduler_id);
    $("#addReminder").addClass('show');
    $("#addReminder").modal().show();

}

function editScheduler(scheduler_key,show_id,form_id) {

    template_id = $("#template_id").val();
    var url = '/shows/getUnSelectedClasses/'+form_id+"/"+show_id+"/"+template_id;
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            $("#ClassesContainer").html(data);
            $(".selectClasses").selectpicker();
    var url = '/master-template/editSchedulerTime/'+scheduler_key;
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {


           // console.log(data.resData);

            $("#addRestrictions").addClass('show');
            $("#addRestrictions").modal('show');
            $('.SelectDateAndTime').val(data.resData.restriction);
            $('.blockTime').val(data.resData.block_time);
            $('.blockTimeTitle').val(data.resData.block_time_title);
            $('.scheduler_key').val(data.resData.scheduler_key);
            $(".show_id").val(data.resData.show_id);
            $(".scheduler_id").val(data.resData.scheduler_id);
            $(".form_id").val(data.resData.form_id);
            $(".qualifing_price").val(data.resData.qualifing_price);
            $('.selectClasses').selectpicker('val',eval(data.selectedClasses));
            $('.scoreFrom').selectpicker('val',eval(data.scoreFrom));
            if(data.resData.is_group==1) {
                $(".multipleSelect").hide();
                $('.is_group').selectpicker('val',eval(data.resData.is_group));
            }

            if(data.resData.is_multiple_selection==1) {
                //jcf.customForms.destroyAll();
                $(".multipleSelection").attr('checked',true);
                //jcf.customForms.replaceAll();
            }
             if(data.resData.qualifing_check==1) {
                //jcf.customForms.destroyAll();
                $(".qualifingcheckbox").attr('checked',true);
                $('.text-box-qprice').show();
                //jcf.customForms.replaceAll();
            }else{
                //jcf.customForms.destroyAll();
                $(".qualifingcheckbox").attr('checked',false);
                $('.text-box-qprice').hide();
                //jcf.customForms.replaceAll();
            }
            if(data.resData.is_rider_restricted==1) {
                //jcf.customForms.destroyAll();
                $(".restrictRiders").attr('checked',true);
                //jcf.customForms.replaceAll();

            }
        }, error: function () {
            alert("error!!!!");
        }

    }); //end of ajax
        }
    });

}

function deleteScheduler(obj,scheduler_key) {


    if(scheduler_key=='')
    {
        alertBox("Wrong data details or old data is here");
        return false;
    }
    var exitConfirmDialog = new BootstrapDialog.show({
        title: 'Confirmation Box',
        message: 'Are you sure you want to Delete this Scheduler?',
        closable: false,
        buttons: [
            {
                label: 'No',
                action: function (dialog) {
                    dialog.close();
                }
            },
            {
                label: 'Yes',
                cssClass: 'btn btn-success',
                action: function (dialog) {

                    var url = '/master-template/deleteSchduler/'+scheduler_key;
                    $.ajax({
                        url: url,
                        type: "get",
                        success: function (data) {

                            obj.parent().parent().remove();
                            location.reload();

                        }, error: function () {
                            alert("error!!!!");
                        }
                    }); //end of ajax
                    dialog.close();
                }
            }]
    });


}