    $(function(){
      $(document).on('click', ".btn-invite-parti", function () {
        var email = $(this).attr("data-email");
        $(".previous-participants").append("<input type='hidden' name='pastParticipats[]' value='"+email+"'>")
        $(this).attr('class', 'btn-uninvite-parti');
        $(this).text('Unselect this participant');
      });
      $(document).on('click', ".btn-uninvite-parti", function () {
        var email = $(this).attr("data-email");
        $('.previous-participants input[value="'+email+'"]').remove();
        $(this).attr('class', 'btn-invite-parti');
        $(this).text('Select for Invite');
      });
    //select all script
    $(document).on('change','.select-past-participant',function () { 

      if ($(this).is(':checked')) {
        $('.btn-invite-parti').click();
      }else{
        $('.btn-uninvite-parti').click();
      };
    });

   $(document).on('click', ".btn-add-new-user", function () {
     var count = $(".add-more-participants-fields .number-emails").length;
      $(".add-more-participants-fields").append("<div class='row mb-20 number-emails'>\
              <div class='col-sm-5'>\
                <div class=''>\
                  <input name='emailName["+count+"][name]' type='text' placeholder='Name' class='form-control' />\
                </div>\
              </div>\
              <div class='col-sm-5'>\
                <div class=''>\
                  <input name='emailName["+count+"][email]' pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$' type='email' placeholder='Email' class='form-control'/>\
                </div>\
              </div>\
              <div class='col-sm-1' style='position: absolute;right:0px;' >\
                <div class='pull-left'>\
                <button type='button' class='btn btn-default remove-this-entry' aria-label='Close'>\
                  <span aria-hidden='true'>Remove</span>\
                </button>\
                </div>\
              </div>\
              </div>").slideDown("slow");
   });
    $(document).on('click', ".remove-this-entry", function () {
      $(this).closest('.number-emails').slideUp("normal", function() { $(this).remove(); } );
   });


        $('input#checkForm').change(function () {

            if(this.checked) {
                $(".checkSpectators").parent().addClass('chk-label-active');
                $(".checkSpectators").prev().removeClass('chk-unchecked');
                $(".checkSpectators").prev().addClass('chk-checked');
                $(".checkSpectators").prop('checked','checked');
            }
            else
            {
                $(".checkSpectators").parent().removeClass('chk-label-active');
                $(".checkSpectators").prev().addClass('chk-unchecked');
                $(".checkSpectators").prev().removeClass('chk-checked');
                $(".checkSpectators").prop('checked','');

            }
        });


        $('.selectpicker').selectpicker({
            selectAllText: 'Your select-all-text',
            deselectAllText: 'Your deselect-all-text'
        });

    });
