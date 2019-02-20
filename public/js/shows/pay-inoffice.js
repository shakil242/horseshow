$(function () {
  $('input:checkbox').on("change", function () {
         if (confirm('Are you sure you want to pay in office?')) {
          if(this.checked) {
            var payinoffice =1;
          }else{
              var payinoffice =0;

          }
          var horse_id = $(this).val();
          var show_id = $(this).data('show');

          var base_url = window.location.protocol + "//" + window.location.host + "/";
        //var token = $("meta[name=csrf-token]").attr("content");
        
        $.ajax({
            url: base_url + "shows/invoice/payinoffice",
            type: "POST",
            beforeSend: function (xhr) {
                var token = $("meta[name=csrf-token]").attr("content");
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: {
              "payinoffice": payinoffice,
              "horse_id":horse_id,
              "show_id":show_id,
            },
            // success: function(data) {
               
            // }
        });
        }else{
          $(this).prop('checked', !$(this).prop('checked'));
        }


  });


});