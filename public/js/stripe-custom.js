$(function () {

    $(".openInvoiceDialogue").click(function () {

        $('#amount').val($(this).data('content'));
        $('#invoiceId').val($(this).data('id'));
        $('#addPayment').modal('show');
    });


});
$( "#SaveAmount" ).unbind().click( function(e) {
    e.preventDefault();

    var url = '/master-template/Invoice/saveInvoiceAmount';
    $.ajax({
        url: url,
        type:"POST",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        data: $("#addInvoice").serialize(),
        success:function(data) {
            $(".addNotesMessage").toggle('slow');
            $(".addNotesMessage").html(data['success']);

          setTimeout(function () {
              $('#addPayment').modal('hide');
              location.reload();
          },1000);

        }
    });
});
