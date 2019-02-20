var modId = Cookies.get("previousHistory");
var PenaltyDate = Cookies.get("PenaltyDate");
var permissionBtn = Cookies.get("permissionBtn");
var customPermission = Cookies.get("customPermission");


//$('input:radio[name="module"][value="'+permissionBtn+'"]').attr('checked',true);

// Cookies.set("radioButtonValues", null, { path: '/' });

var dateVal= $("#datepicker").val(PenaltyDate);

var radioButtons = Cookies.get("radioButtonValues");
if(radioButtons) {
    var res = JSON.parse(radioButtons);
    // $.each(res, function (i, item) {
    //     $('input:radio[name="' + item.name + '"][value="' + item.value + '"]').attr('checked', true);
    // });
}
$(".invoiceAttach-"+modId).attr('checked',false);

function getInvoiceForm(obj,id,moduleId,asset_id) {

    var elm = $(obj).closest('table').find('.radioButton').filter(':checked');

    var radioButtonValues = []; // note this

    elm.each(function() {
        var res = {name: $(this).attr('name'), value:parseInt($(this).attr('value'))}
        radioButtonValues.push(res)  ;
    });

    Cookies.set('radioButtonValues', radioButtonValues);

    if(obj.checked) {
        var base_url = '/master-template/Invoice/' + id + '/preview/' + moduleId+'/'+ asset_id;
        window.open(base_url, '_self');
    }
}


$(document).ready(function () {
    $('#datepicker').datepicker({
        inline: true,
        onSelect: function (dateText, inst) {
            $(".setPenaltyInvoice").show();
            var dateVal= $("#datepicker").val();
            Cookies.set('PenaltyDate',dateVal);

        }
    });
    // Hide show after penalty date

    var dateV = $("#datepicker").val();

    if(dateV!='')
        $(".setPenaltyInvoice").show();
    else
        $(".setPenaltyInvoice").hide();


    $(".cancelPenalty").click(function (e) {
        $(this).parent().hide();
        $("#datepicker").val('');
    })

    // reset cookies using in permission balde

    $(".submitVals").click(function (e) {
        Cookies.set("radioButtonValues", null, { path: '/' });
        Cookies.set("PenaltyDate",'', { path: '/' });
        Cookies.set("permissionBtn",'', { path: '/' });
        Cookies.set("customPermission",'', { path: '/' });

    })



    $('input:radio[name="permission"]').click(function (e) {
        Cookies.set('permissionBtn',$(this).val());
    })
    $(".custom-permission").change(function (e) {
        Cookies.set('customPermission',$(this).val());
    })


    $(".saveBtnPos").click(function (obj) {

        var elm = $(this).closest('table').find('.radioButton').filter(':checked');

        var radioButtonValues = []; // note this

        elm.each(function() {
            var res = {name: $(this).attr('name'), value:parseInt($(this).attr('value'))}
            radioButtonValues.push(res)  ;
        });


        Cookies.set('radioButtonValues', radioButtonValues);

    })




});

function ParentShow(obj) {
    $(obj).parents().parents().next('.child').toggleClass('hide');
    $(obj).parents().parents().next().next('.SubChilds').toggleClass('hide');
}

function showChild(obj) {
    $(obj).parents().next().toggleClass('hide');

}
$(".showhr").click(function() {
    $(this).closest("table.aser").removeClass("aser");
});


$('#checkall').change(function() {
    //jcf.customForms.destroyAll();
     $("input.check").prop('checked', $(this).prop('checked'));
    //jcf.customForms.replaceAll();

});

$('#checkAllHistory').change(function() {

    //jcf.customForms.destroyAll();
    $("input.checkHistory").prop('checked', $(this).prop('checked'));
    //jcf.customForms.replaceAll();

});

// $('#checkall').change(function() {
//
//     // $(".check").prop('checked', $(this).prop('checked'));
//     jcf.customForms.destroyAll();
//
//     if($(this).is(':checked')){
//         $('input.check').parent().find("div").removeClass('chk-unchecked');
//         $('input.check').parent().find("div").addClass('chk-checked');
//         $('input.check').attr("checked",true);
//     }
//     else
//     {
//         $('input.check').parent().find("div").removeClass('chk-checked');
//         $('input.check').parent().find("div").addClass('chk-unchecked');
//         $('input.check').attr("checked",false);
//     }
//     jcf.customForms.replaceAll();
//
//
// });