$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
   $("body").on( "click", ".edit-price", function() {   
      //If already editing some contents
      if($(".post_content").length !=0){
        alert("Please save the previous edited value. Or cancel to edit new value.");
      }else{
        var pText = $(this).closest("td").find(".price-display");
        var pValue = $(this).closest("td").find(".priceSet").val();  
        var ch_id = $(this).closest("td").find(".ch_id").val();
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var inputfield = "<form method='post' action='/shows/app-owner/update/invoice'>\
        ($)<input type='number' step='any' class='post_content NumaricRistriction' name='new_val' value='"+pValue+"' />\
          <input type='hidden' name='_token' value='"+csrf+"'>\
          <input type='hidden' name='ch_id' value='"+ch_id+"'>\
          <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
          <input class='btn btn-smaller btn-xs btn-cancle' value='Cancel' type='button'>\
        </form>";
        pText.replaceWith(inputfield);
        $(this).hide();
      }
    });

    $("body").on( "click", ".edit-price-trainer", function() {
        //If already editing some contents
        if($(".post_content").length !=0){
            alert("Please save the previous edited value. Or cancel to edit new value.");
        }else{
            $(this).closest("td").prev("td").find('.horse_quantity').hide();
            $(this).closest("td").prev("td").find('.horse_quantity_hidden').addClass('submited_quantity').show();
            var pText = $(this).closest("td").find(".price-display");
            var pValue = $(this).closest("td").find(".priceSet").val();
            var ch_id = $(this).closest("td").find(".ch_id").val();
            var csrf = $('meta[name="csrf-token"]').attr('content');
            var inputfield = "<form method='post' id='trainer-horse-price'>\
        ($)<input type='number' step='any' class='post_content NumaricRistriction' name='new_val' value='"+pValue+"' />\
          <input type='hidden' name='_token' value='"+csrf+"'>\
          <input type='hidden' name='ch_id' class='ch_id' value='"+ch_id+"'>\
          <input class='btn btn-smaller btn-xs saveprice' onclick='submitTrainerInvoice("+ch_id+")' value='Update' type='button'>\
          <input class='btn btn-smaller btn-xs btn-cancle' value='Cancel' type='button'>\
        </form>";
            pText.replaceWith(inputfield);
            $(this).hide();
        }
    });
    //Divisions
   $("body").on( "click", ".edit-price-division", function() {   
      //If already editing some contents
      if($(".post_content").length !=0){
        alert("Please save the previous edited value. Or cancel to edit new value.");
      }else{
        var pText = $(this).closest("td").find(".price-display");
        var pValue = $(this).closest("td").find(".priceSet").val();  
        var ch_id = $(this).closest("td").find(".ch_id").val();
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var inputfield = "<form method='post' action='/shows/app-owner/update/divisions'>\
        ($)<input type='number' step='any' class='post_content NumaricRistriction' name='new_val' value='"+pValue+"' />\
          <input type='hidden' name='_token' value='"+csrf+"'>\
          <input type='hidden' name='ch_id' value='"+ch_id+"'>\
          <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
          <input class='btn btn-smaller btn-xs btn-cancle-division' value='Cancel' type='button'>\
        </form>";
        pText.replaceWith(inputfield);
        $(this).hide();
      }
    });

   $("body").on( "click", ".edit-additional-charges", function() {   
      //If already editing some contents
      if($(".post_content").length !=0){
        alert("Please save the previous edited value. Or cancel to edit new value.");
      }else{
        var pText = $(this).closest("td").find(".additional-divi");
        
        var price = $(this).closest("td").find(".additonal-price").val();  
        var qty = $(this).closest("td").find(".additonal-qty").val();
        var line_id = $(this).closest("td").find(".additonal-row-id").val();
        
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var istrainer = $(this).closest("td").find(".trainer-split").val();
        if (istrainer) {
          var split_id = $(this).closest("td").find(".additional-split-id").val();         
          var inputfield = "<form method='post' action='/shows/app-owner/update/split'>\
            <input type='number' class='additional-input post_content NumaricRistriction' name='qty' value='"+qty+"' />\
            x ($)<input type='number' step='any' class='additional-input post_content NumaricRistriction' name='price' value='"+price+"' />\
              <input type='hidden' name='_token' value='"+csrf+"'>\
              <input type='hidden' name='line_id' value='"+line_id+"'>\
              <input type='hidden' name='MSTS_id' value='"+split_id+"'>\
              <input type='hidden' class='additonal-price' value='"+price+"'>\
            <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
              <input class='btn btn-smaller btn-xs btn-cancle-additional' value='Cancel' type='button'>\
            </form>";
        }else{
            var ch_id = $(this).closest("td").find(".ch_id").val();         
            var inputfield = "<form method='post' action='/shows/app-owner/update/additional'>\
            <input type='number' class='additional-input post_content NumaricRistriction' name='qty' value='"+qty+"' />\
            x ($)<input type='number' step='any' class='additional-input  post_content NumaricRistriction' name='price' value='"+price+"' />\
              <input type='hidden' name='_token' value='"+csrf+"'>\
              <input type='hidden' name='line_id' value='"+line_id+"'>\
              <input type='hidden' name='ch_id' value='"+ch_id+"'>\
              <input type='hidden' class='additonal-price' value='"+price+"'>\
              <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
              <input class='btn btn-smaller btn-xs btn-cancle-additional' value='Cancel' type='button'>\
            </form>";
          }
          pText.html(inputfield);
          $(this).hide();
      }
    });


   $("body").on( "click", ".edit-supplies-price", function() {   
      //If already editing some contents
      if($(".post_content").length !=0){
        alert("Please save the previous edited value. Or cancel to edit new value.");
      }else{
        var pText = $(this).closest("td").find(".additional-divi");
        
        var price = $(this).closest("td").find(".additonal-price").val();  
        var qty = $(this).closest("td").find(".additonal-qty").val();
        var line_id = $(this).closest("td").find(".additonal-row-id").val();
        
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var istrainer = $(this).closest("td").find(".trainer-split").val();
        if (istrainer) {
          var split_id = $(this).closest("td").find(".additional-split-id").val();         
          var inputfield = "<form method='post' action='/shows/app-owner/update/split'>\
            <input type='number' class='additional-input post_content NumaricRistriction' name='qty' value='"+qty+"' />\
            x ($)<input type='number' step='any' class='additional-input post_content NumaricRistriction' name='price' value='"+price+"' />\
              <input type='hidden' name='_token' value='"+csrf+"'>\
              <input type='hidden' name='line_id' value='"+line_id+"'>\
              <input type='hidden' name='MSTS_id' value='"+split_id+"'>\
              <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
              <input class='btn btn-smaller btn-xs btn-cancle-additional' value='Cancel' type='button'>\
            </form>";
        }else{
            var ch_id = $(this).closest("td").find(".ch_id").val();         
            var inputfield = "<form method='post' action='/shows/app-owner/update/additional'>\
            <input type='number' class='additional-input post_content NumaricRistriction' name='qty' value='"+qty+"' />\
            x ($)<input type='number' step='any' class='additional-input post_content NumaricRistriction' name='price' value='"+price+"' />\
              <input type='hidden' name='_token' value='"+csrf+"'>\
              <input type='hidden' name='order_supplie' value='"+line_id+"'>\
              <input type='hidden' name='ch_id' value='"+ch_id+"'>\
              <input class='btn btn-smaller btn-xs saveprice' value='Update' type='submit'>\
              <input class='btn btn-smaller btn-xs btn-cancle-additional' value='Cancel' type='button'>\
            </form>";
          }
          pText.html(inputfield);
          $(this).hide();
      }
    });
  
  $("body").on( "keypress", ".NumaricRistriction", function(event) {  
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

  $("body").on( "click", ".btn-cancle", function(event) {  
      var pValue = $(this).closest("td").find(".priceSet").val();  
      var ptext = $(this).closest("td").find(".prise-contents");  
      var html = "<span class='price-display'>($)"+pValue+"</span> <input class='priceSet' value='"+pValue+"' type='hidden'>";
      $(this).closest("td").find(".edit-price").show();
      $(this).closest("td").find(".edit-price-trainer").show();
      $(".horse_quantity_hidden").hide();
      $(".horse_quantity").show();
      ptext.html(html);
  });



    $("body").on( "click", ".btn-cancle-division", function(event) {
      var pValue = $(this).closest("td").find(".priceSet").val();  
      var ptext = $(this).closest("td").find(".prise-contents");  
      var html = "<span class='price-display'>($)"+pValue+"</span> <input class='priceSet' value='"+pValue+"' type='hidden'>";
      $(this).closest("td").find(".edit-price-division").show();
      ptext.html(html);
  });

   $("body").on( "click", ".btn-cancle-additional", function(event) {  
      var ptext = $(this).closest("td").find(".additional-divi");  
      var price = $(this).closest("td").find(".additonal-price").val();
      var qty = $(this).closest("td").find(".additonal-qty").val();
      var html = qty+" x "+price+"= ($)"+ (qty*price);
      $(this).closest("td").find(".edit-additional-charges").show();
      ptext.html(html);
  });

    $('input:checkbox.charges_selected_check').on("change", function () {

            if(this.checked) {
                $(this).parent().parent().parent().find(".quantity_selected").attr("min",1);
            }else{
                $(this).parent().parent().parent().find(".quantity_selected").attr("min",0);
            }
        });



});


function submitTrainerInvoice(ch_id){
    var new_val = $(".post_content").val();
    var horse_quantity = $(".submited_quantity").val();
    var url = '/shows/app-owner/update/invoice';
    $.ajax({
        url: url,
        type: "POST",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        data: {new_val:new_val,ch_id:ch_id,horse_quantity:horse_quantity},
        success: function (data) {
            location.reload();
        }
    });
}

function addCharges(id) {
    $('input:checkbox.charges_selected_check').attr('checked',false);
    $("#add_addional_charges-"+id).addClass('show');
    $("#add_addional_charges-"+id).modal('show');
}

