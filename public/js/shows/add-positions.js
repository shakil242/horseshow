$(function () {
  //Numaric field ristricton
  $('body').on('keypress','.NumaricRistriction', function(event){
      if(event.which == 8 || event.which == 0){
          return true;
      }
      if(event.which < 46 || event.which > 59) {
          return false;
          //event.preventDefault();
      } // prevent if not number/dot

      if(event.which == 46 && $(this).val().indexOf('.') != -1) {
          return false;
          //event.preventDefault();
      } // prevent if already dot
  });

  //Percentage restriction
$('body').on('keydown keyup','.percentage-check', function(e){
    var max = $(this).attr('max');
    if ($(this).val() > 100 
        && e.keyCode !== 46 // keycode for delete
        && e.keyCode !== 8 // keycode for backspace
       ) {
       e.preventDefault();
       $(this).val(100);
    }
});

//Percentage restriction
$('body').on("keydown keyup", ".total-amount , .percentage-prize", function(e) {
    var totalAmount = $(".total-amount").val();
    var percent = $(".percentage-prize").val();
    var totalPrize = ( totalAmount * percent / 100 ).toFixed(3);
    $(".prize-money").val(totalPrize);
    $(".total-prize").val(totalPrize);
});

  //Percentage restriction
$('body').on("keydown keyup", ".position-percentage", function(e) {
    var totalAmount = $(".total-prize").val();
    var percent = $(this).val();
    var totalPrize = ( totalAmount * percent / 100 ).toFixed(3);
    $(this).closest('.duplicator').find('.position-price').val(totalPrize);
});

//Page
$('body').on("keydown keyup", ".add-back-amount , .payback-entries", function(e) {
    var totalAmount = $(".add-back-amount").val();
    var percent = $(".payback-entries").val();
    var totalPrize = (totalAmount*percent).toFixed(3);
    $(".payback-total-money").val(totalPrize).trigger('change');
});

//Payback restriction
$('body').on("change paste keyup", ".payback-total-money , .prize-money", function(e) {
    var paybackPrice = $(".payback-total-money").val();
    var prizemoney = $(".prize-money").val();

    var totalPrize =parseFloat(paybackPrice)+parseFloat(prizemoney);
    $(".total-prize").val(totalPrize);
});

  $('.add-more').on("click", function () {
        var len = parseInt($(".position-listing .duplicator").length);
        lengt = len+1;
        var component = "<div class='duplicator' style='display: none;'>\
                <div class='row'>\
                <div class='col-sm-1' style='text-align: center;'>\
                    <label>"+lengt+"</label>\
                </div>\
                <div class='col-sm-4 row'>\
                   <div class='col-sm-2' style='padding: 5px;'>(%)</div> <div class='col-sm-9'><input name='placingprice[place]["+lengt+"][percent]' type='number' class='form-control NumaricRistriction percentage-check position-percentage' placeholder='Add %' max='100' ></div>\
                </div>\
                <div class='col-sm-4 row'>\
                   <div class='col-sm-2' style='padding-top: 5px;'>($)</div> <div class='col-sm-10'><input name='placingprice[place]["+lengt+"][price]' type='number' class='form-control NumaricRistriction position-price' placeholder='Add Amount in $' step='any' min='0' max='1000' value='0' ></div>\
                </div>\
                <input name='placingprice[place]["+lengt+"][position]' type='hidden' value='"+lengt+"'>\
                <div class='col-xs-1'>\
                        <button type='button' class='btn btn-default removeButton delete-position'><i class='fa fa-minus'></i></button>\
                </div>\
                </div></div>";
        //$(".position-listing").append();
        $(component).appendTo('.position-listing').slideDown("slow");
  });

    $('body').on("click", '.delete-position', function () {
        if (confirm('Are you sure?')) {
          $target = $(this).closest(".duplicator");
          $target.hide('slow', function(){ $target.remove(); });
        }
  });


  

});