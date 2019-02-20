$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
$(".bnt-menu").click(function(){
  $(".main-menu").slideToggle();
});

$(function(){

  //Feedback required fields validation
  var $inputs = $('input[name=qno1],input[name=qno2],input[name=qno3],input[name=qno4]');
  $inputs.on('input', function () {
      // Set the required property of the other input to false if this input is not empty.
      $inputs.not(this).prop('required', !$(this).val().length);
  });
  //Feedback submit
  $( "#feedback-form" ).submit(function( event ) {
      event.preventDefault();
    feedback();
  });
  //Navigation Active class
  var url = window.location.pathname, 
    urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
    // now grab every link from the navigation
    $('.main-menu li').each(function(){
    $(this).removeClass('active');
        // and test its normalized href against the url pathname regexp
        if(urlRegExp.test($(this).find('a').attr('href').replace(/\/$/,''))){
            $(this).addClass('active');
        }
    });

});

//Send comment
function feedback(){
       var values = {};
        $.each($('#feedback-form').serializeArray(), function(i, field) {
            if (field.name !="_token") {
                values[field.name] = field.value;
            };
        });
       var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
          url: "/feedback/send",
          type:"ADD",
          method:"POST",
          data: {'data_field':values ,"_token": token,},
          context: this,
          beforeSend: function()
                {
                    $('#loading').show();
                    $('#feedbackModal .btn-feedback').attr('disabled',true);
                },
          success: function(json) {
            if(json == 'true'){
                $('#feedbackModal').html("<p>Thank you for taking time to send your valueable feedback</p>");
                $(".close").trigger('click');
                //$('#feedbackModal .btn-feedback').hide();
            };
            $('#loading').hide();
          },
           error: function( _response ){
                    $('#loading').hide();
                    $('#feedbackModal btn-feedback').attr('disabled',false);

          }
        });
    return false;  
}

  $('input[name=module_launch_radio]:radio').on('change', function() {
    var checkedvalue = $('input[name=module_launch_radio]:checked').val();
    $("#module_launch_hidden").val(checkedvalue);
  });
  $('#storeandlist').on('click', function(event){
        $("#afterstore").val("storeandlist");
  });
  $('#storeonly').on('click', function(event){
        $("#afterstore").val("storeonly");
  });

$(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if(scroll > 15){
          $("#home-header").addClass("header-active");
        }else {
          $("#home-header").removeClass("header-active");
        }
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


function alertBox(msg,type) {

    if(type=='')
    {
        type = TYPE_INFORMATION;
    }
//    alert(msg);
    BootstrapDialog.show({
        type: BootstrapDialog.type, //TYPE_WARNING,TYPE_INFORMATION,TYPE_SUCCESS,TYPE_DANGER,TYPE_DEFAULT,TYPE_PRIMARY
       class:"show",
        message:msg,
        buttons: [{
            label: 'Close',
            action: function (dialog) {
                dialog.close();
               // dialog.addClass('show');
            }
        }]
    });


}

function alertConfirmation(msg,cb) {

    BootstrapDialog.confirm({
        title: 'Confirmation Box',
        message: msg,
        closable: true,
        draggable: true,
        btnCancelLabel: 'Cancel',
        btnOKLabel: 'Yes',
        btnOKClass: 'btn btn-success',
        btnCancelClass: 'btn btn-warning',
        callback: cb

    });

}

function checkConfirm(result) {
   return result;
}