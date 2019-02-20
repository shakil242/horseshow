$(document).ready(function(){
    $(".formScheduler").on("show.bs.modal", function(e) {
        var template_id = $(e.relatedTarget).data('target-id');
        var assetId = $(e.relatedTarget).data('title');
        var key = $(e.relatedTarget).data('key');
        var id = $(e.relatedTarget).data('id');
        var status = $(e.relatedTarget).data('status');

        if(id!='')
        {
        acceptRequest(id,status,assetId);
        }
        var url = "/master-template/"+template_id+"/"+assetId+"/"+key+"/list/schedular/forms/";

        $.get(url, function( data ) {

            setTimeout(function () {
            $(".modal-body").html(data);
            },1000);
            });
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

       var tabSelected = $(e.target).data('placement');
            setTimeout(function () {
                var url = "/employee/appManager/isEmployee/"+tabSelected;
                $.get(url,function () {

                });

            },1000)

    });
    });


function  acceptRequest(template_id,invite_asociated_key,id,asset_id,status,penalty) {

    if(penalty==1)
    {
        $('#penaltyMessage').modal("show");

        $(".penaltyYes").attr('onclick',"acceptRequest('"+template_id+"','"+invite_asociated_key+"','"+id+"','"+asset_id+"','"+status+"',0)");

        return false;
    }else
    {
        $('#penaltyMessage').modal("hide");
    }

    var url = "/participant/sendInvite/response/"+id+"/"+status+"/"+asset_id;
    $.get(url, function(data ) {

       $('#ajax-loading').show();
       $("#innerViewCon").html(data);

       setTimeout(function () {
           $('#ajax-loading').hide();
           // if(status==1) {
           //     $('#formScheduler').modal('show');
           //     getSchedulerForms(template_id, asset_id, invite_asociated_key);
           // }
           },1000);

    });

}


function getSchedulerForms(template_id,asset_id,invite_asociated_key) {

    $('#formScheduler').modal('show');

    var url = "/master-template/"+template_id+"/"+asset_id+"/"+invite_asociated_key+"/list/schedular/forms/";

    $.get(url, function( data ) {

        setTimeout(function () {
            $(".modal-body").html(data);
        },1000);
    });

}
function getSubSchedulerForms(template_id,asset_id,invite_asociated_key,id) {

    $('#formScheduler').modal('show');

    var url = "/master-template/"+template_id+"/"+asset_id+"/"+invite_asociated_key+"/"+id+"/subParticipant/list/schedular/forms/";

    $.get(url, function( data ) {

        setTimeout(function () {
            $(".modal-body").html(data);
        },1000);
    });

}