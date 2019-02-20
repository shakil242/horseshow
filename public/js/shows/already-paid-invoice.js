$(function () {
  $('input:checkbox.PIO-checkBox').on("change", function () {
     if (confirm('Are you sure? Once This invoice is moved to paid, You will not be able to mark it unpaid.')) {
        if(this.checked) {
            var horse_id =$(this).val();
            var show_id =$(this).closest('label').find(".show_id").val();
            
            if(horse_id){
              window.location.replace('/shows/invoice-already-paid/'+horse_id+'/'+show_id);
            }
        }
      }else{
        //  jcf.customForms.destroyAll();

              if($(this).prop('checked')==true)
                  $("#invoicePayLink").show();
              else
                  $("#invoicePayLink").hide();

           $(this).prop('checked', !$(this).prop('checked'));
           //jcf.customForms.replaceAll();
      }
  });

});

function exportClaimDetails(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var claim_id = $("#claim_id").val();
    var horse_id = $(".horse_id").val();
    var show_id = $(".show_id").val();

    var url ="/Billing/exportClaimForm/"+horse_id+"/"+show_id+"/single";
    $.ajax({
        url:url ,
        method:"GET",
        context: this,
        "success": function(data) {
            window.location.href = url;
           // window.location = 'file.xls';
        }
    });
}

function GetPrizeClaimForm(horse_id,show_id){
    var token = $('meta[name="csrf-token"]').attr('content');

    $(".horse_id").val(horse_id);
    $(".show_id").val(show_id);


    $.ajax({
        url: "/Billing/getPrize/claimForm",
        method:"POST",
        data: {'horse_id':horse_id ,'show_id':show_id , "_token": token},
        context: this,
        "success": function(data) {
            if(data)
            {
                $("#prize_amount").val(data.prize_amount);
                $("#social_security_number").val(data.social_security_number);
                $("#federal_id_number").val(data.federal_id_number);
                $("#claim_id").val(data.id);

            }

            $('#billing_prize_claim').addClass('show');
            $('#billing_prize_claim').modal('show');
            $(".modal-backdrop").addClass('show');
        }
    });
}

function PayInOffice(horse_id,show_id,assetTotal,additionalPrice,royaltyFinal,prizeWon,splitPrice,total,divisionTotal,stallPrice,total_taxis){
    var token = $('meta[name="csrf-token"]').attr('content');
    //re initilize
    $("#paid_in_office_popup").closest('form').find("input[type=text]").val('');
    $(".payinoffice_details").val(' ');

    //Setting ids
    $(".horse_id").val(horse_id);
    $(".show_id").val(show_id);

    //setting vals
    $("#paid_in_office_popup .assetTotal").val(assetTotal);
    $("#paid_in_office_popup .additionalPrice").val(additionalPrice);
    $("#paid_in_office_popup .royaltyFinal").val(royaltyFinal);
    $("#paid_in_office_popup .prizeWon").val(prizeWon);
    $("#paid_in_office_popup .splitPrice").val(splitPrice);
    $("#paid_in_office_popup .total").val(total);
    $("#paid_in_office_popup .divisionTotal").val(divisionTotal);
    $("#paid_in_office_popup .stallPrice").val(stallPrice);
    $("#paid_in_office_popup .total_taxis").val(total_taxis);

    $('#paid_in_office_popup').addClass('show');
    $('#paid_in_office_popup').modal('show');
    //$(".modal-backdrop").addClass('show');
}

function editPayInOffice(billing_id,check_detail){
    $("#editpaid_in_office_popup").closest('form').find("input[type=text]").val('');
    $("#editpaid_in_office_popup .payinoffice_details").val('');
    check_detail = JSON.parse(JSON.stringify(check_detail)).replace(/\"/g, "");
    $("#editpaid_in_office_popup .payinoffice_details").val(check_detail);
    $("#editpaid_in_office_popup .billing_id").val(billing_id);

    $('#editpaid_in_office_popup').addClass('show');
    $('#editpaid_in_office_popup').modal('show');
}