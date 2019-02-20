// $('.datetimepickerTime').datetimepicker({
//     sideBySide: true,
//     format: 'MM/DD/YYYY h:mm A'
//     //format: 'HH:mm'
// });

// $('.multiDatePicker').multiDatesPicker();
//



$('.timePicker').timepicker({
    'step': 15,
    'minTime': '7:00 AM',
    'maxTime': '9:00 PM'
});

function removeSlot(obj) {

    $(obj).parent().parent().not(':first').remove();
    $(obj).parent().remove();

    $(obj).parent().parent().remove();
}

function getAssetTitles(asset_id) {

    var url = '/master-template/getAssetTitles/' + asset_id;
    $.ajax({
        url: url,
        type: "Get",
        beforeSend: function (xhr) {

        },
        success: function (data) {
            $(".assetTitle").html(' - '+data);
        }
    });


}
function getSchedualTime(asset_id,id,form_id,show_id) {

    if(asset_id=='all')
    {
        var assets = [];

        $('input[name="MultiScheduler[]"]:checked').each(function(){
            assets.push($(this).val());
        });

        if(assets.length > 0) {
            var asset_id = assets.join(",");

            $('.multiDatePicker').multiDatesPicker();
            $("#schedulaTime").modal('show');
            $("#schedulaTime").addClass("show");

            $("#asset_id").val(asset_id);
           getAssetTitles(asset_id);


        }else
        {
           alertBox('Please select at least one checkbox first!','TYPE_WARNING');
        }

    }else {

        var url = '/master-template/assets/assetSchedulers/' + asset_id+'/'+id+'/'+form_id+"/"+show_id;
        $.ajax({
            url: url,
            type: "Get",
            beforeSend: function (xhr) {

            },
            success: function (data) {
                $('.selectpicker').selectpicker('val', show_id);
                $(".TimeShceduler").html(data.view);
                $(".assetTitle").html(' - '+data.asset_title);
            }
        });

        $("#schedulaTime").modal('show');
        $("#schedulaTime").addClass("show");
        $("#asset_id").val(asset_id);
    }
}

