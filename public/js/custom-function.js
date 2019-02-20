


function selectCategory(show_id,sponsorFormId) {
    var token = $('meta[name="csrf-token"]').attr('content');

    var show_id = show_id;

    var data =[];
    $(":checked").each(function() {
        data.push($(this).val());
    });

    if(data!='')
        var myJsonString = JSON.stringify(data);
    else
        var myJsonString = null;

    var sponsorFormId = sponsorFormId;

    var url = '/shows/sponsors/getSelectedCategories/'+myJsonString+"/"+show_id+"/"+sponsorFormId;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (res) {
            $(".amountContainer").html(res);


        }
    });


}
function updateStatus(v,cn) {

    var value = v.val();

    if(value=='completed') {
        $(".status-" + cn).html(value);
        $(".qtyS-"+cn).show().focus();
        var requestedVal = $('.requestedQty-'+ cn).val();
        $(".qty-"+cn).prop("disabled", false).val(requestedVal).trigger('keyup');
    }
    else
    {
        $(".status-" + cn).html(value);
        $(".qtyS-"+cn).hide();
        $(".qty-"+cn).val(0).prop("disabled", true);
    }
    var st = 0;
    $('.additiona-charges-row').each(function () {

        var orderStatus = $(this).closest('.tr-row').find('select.orderStatus option:selected').val();
        //if (orderStatus=='approve') {
        var i = $('.approvedNumberQty', this);
        var up = $(i).data('unit-price');
        var q = $(i).val();
        $(this).closest('.tr-row').find(".priceinqty").text("$"+up*q);
        st = st + parseFloat(up * q);


    });

    // Subtotal price
    var assetPrice = parseFloat($(".AssetsPrice").val());
    var total = st+assetPrice;
    $(".addAdditionalPrice").html('($) '+st+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+st+'">');

    // $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');

}
function UserForm(val,obj) {
    $(obj).parent().parent().parent().hide();
    $("."+val).show();
}


$(document).ready(function () {

    $(".cancelBtn").click(function(){
        $(this).parent().parent().parent().hide();
        $(".btnContainer").show();

    });


    $(".editButton").click(function(){

        var stripe_account_email = $("#stripe_account_email").val();
        var stripe_account_id = $("#stripe_account_id").val();

        $(".emailAddress").val(stripe_account_email);
        $(".accountId").val(stripe_account_id);

        $(".stripDetailCon").hide();
        $(".alreadyUserEdit").show();

    });

    $(".editCancelBtn").click(function(){
        $(".stripDetailCon").show();
        $(this).parent().parent().parent().hide();

    });

});