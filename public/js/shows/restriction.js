$(function () {
  //Add
  $(".addNewCharges").click(function(){
    $(".additiona-charge-form #input-hidden-for-edit").html("");
    if ($(".additiona-charge-form input.form-control").val().length >0) {
       $(".additiona-charge-form input.form-control").val(""); 
        $(".additiona-charge-form textarea").val(""); 
    };
    $(".additiona-charge-form #storeonly").val("Save"); 
    
    $(".charges-hidden-form").slideDown( "slow");
  });
  //Edit
  $(".edit-additional-charges").click(function(){
    var id = $(this).attr("data-attr");
    var title = $(this).closest('tr').find('.list-title').val()
    var description = $(this).closest('tr').find('.list-description').val()
    var amount = $(this).closest('tr').find('.list-amount').val()
    var required = $(this).closest('tr').find('.list-required').val()

    $(".additiona-charge-form #input-hidden-for-edit").html("<input type='hidden' name='additional_charge_id' value='"+id+"'>");
    $(".additiona-charge-form [name='title']").val(title);
    $(".additiona-charge-form [name='description']").val(description);
    $(".additiona-charge-form [name='amount']").val(amount);
    if (required == 1) {
        $('.additiona-charge-form input:checkbox').prop('checked', true);
    }else{
        $('.additiona-charge-form input:checkbox').prop('checked', false);
    }
    $(".additiona-charge-form #storeonly").val("Update"); 
    $(".charges-hidden-form").slideDown( "slow");
  });

});