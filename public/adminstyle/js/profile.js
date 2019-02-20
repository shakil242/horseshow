$(function() { 

if($('.formtype').val() != 1){
    $('.linkto').hide();
    $('.invoice').hide();
    $('.schedulers_checkbox').hide();

  }else{
    $('.linkto').show();
    $('.invoice').show();
    $('.schedulers_checkbox').show();
}

$('.formtype').on('change', function() {
  if(this.value == 5){
    $('.linkto').hide();
    $('.invoice').hide();
      $('.accessable').html('');
      $('.accessable').append('<div class="form-group">\
    <label>\
      <input type="radio" class="app-owner-radio" name="accessable_to" value="1" required>\
      For App owner\
    </label>\
    <label>\
      <input type="radio" class="app-owner-radio" name="accessable_to" value="2">\
      For Invited Pariticipants\
    </label>\
  </div>');
  }
  else if(this.value == 3) {

      $('.linkto').hide();
      $('.invoice').hide();
      $('.accessable').html('');
      $('.accessable').append('<div class="form-group">\
    <label>\
      <input type="radio" class="app-owner-radio" name="accessable_to" value="1" required>\
      For App owner\
    </label>\
    <label>\
      <input type="radio" class="app-owner-radio" name="accessable_to" value="2">\
      For Spectators\
    </label>\
  </div>');

  }
  else if(this.value == 1){
    $('.linkto').show();
    $('.invoice').show();
    $('.schedulers_checkbox').show();
     $('.accessable').html('');
  }else{
     $('.linkto').hide();
      $('.invoice').hide();
      $('.schedulers_checkbox').hide();
     $('.accessable').html('');
      $('.form-type-feedback-t').html('');
  }
});

$('body').on('change','.app-owner-radio', function() {
    var type = $(this).val();
    if (type==1) {
      $('.form-type-feedback-t').append('<div class="form-group">\
          <label>\
            <input type="radio" name="feedback_type" value="1">\
            Required\
          </label>\
        </div>');
    }else{
     $('.form-type-feedback-t').html('');
    };
    
});
});