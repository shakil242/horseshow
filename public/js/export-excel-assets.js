//Onload click. By default show the form.
$(function(){

    $('.asset-position-export-excel').click(function() {
        var base_url = window.location.protocol + "//" + window.location.host + "/";
        var form_id = $(this).closest('form').find('#form_id_assets').val();
        var url = base_url +"master-template/"+form_id+"/export/assets/positions";
        $(this).attr('href',url).click;
        // $.ajax({

        //     url: base_url +"master-template/"+form_id+"/export/assets/positions",
        //     type: "GET",
        //     beforeSend: function (xhr) {
        //         var token = '{{ csrf_token() }}';
        //         $("#ajax-loading").show();
        //         if (token) {
        //             return xhr.setRequestHeader('X-CSRF-TOKEN', token);
        //         }
        //     },

        //     data: $('.singleCheck:checked').serialize(),
        //     success: function(data) {
        //         $("#ajax-loading").hide();
                
        //         // window.location = base_url + "master-template/billing/multipleInvocie";
        //     }
        // });


    });

});
