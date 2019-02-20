 $(function () {

  $('form.targetvalue').submit(function( event ) {
        $(".division-container .parent-div-row input.form-check-input").attr('disabled', false);
    });
  $('.collaps-on-click .tr-row.parent-div-row input:checkbox').each(function() {
      var divId = $(this).val();
      var totalclasses = $(this).closest(".collaps-on-click").find(".total-classes-div-"+divId).val();
      var checkedOne = $(this).closest(".collaps-on-click").find(".division-"+divId+" input:checkbox:checked").length;
     //alert(totalclasses+" == "+checkedOne);
     // jcf.customForms.destroyAll();
      if(checkedOne == totalclasses){
        $(this).trigger('click').prop("checked", true).attr('disabled', true);
      }
    //  jcf.customForms.replaceAll();
  });
$('.except-division input:checkbox:checked').each(function() {
        var type = $(this).attr("data-attr");
        var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
        var total = parseFloat($(".totalPrice").val());
        var Qty = parseInt($(this).closest('.tr-row').find('.numberQty').val())
        
        //adding required
        if($(this).is(':checked')) {
          //$(this).closest('.tr-row').find('select').attr('required', true);
        } else {
          //$(this).closest('.tr-row').find('select').removeAttr('required');
        }

        //Pricing.
        if ($.isNumeric(price)) {
          if (type == "assets-charges") {
              var horses = parseInt($(this).closest(".tr-row").find(".not-scratched-horses").length);
              var basicPrise = parseFloat($(this).closest(".tr-row").find(".orignalPriceSet").val());
              price=horses*basicPrise;
              $(this).closest(".tr-row").find(".priceSet").val(price);
              $(this).closest(".tr-row").find(".horse-assets-select").html("&nbsp;&nbsp; Horse: "+horses+", price: "+price+"($)");
    

              if ($(this).is(':checked')) {
                var assetPrice = parseFloat($(".AssetsPrice").val());
                assetPrice = assetPrice+price;
                total = total+price;
              }else{
                var assetPrice = parseFloat($(".AssetsPrice").val());
                assetPrice = assetPrice-price;
                total = total-price;

              }
            $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
            $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
          
          }else{
         
          };
          
        };
  });

 $('.division-container .tr-row.hiddenRow input:checkbox:checked').each(function() {
        // var type = $(this).attr("data-attr");
        // var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
        // var total = parseFloat($(".totalPrice").val());
        // var Qty = parseInt($(this).closest('.tr-row').find('.numberQty').val())
        
        //adding required
        if($(this).is(':checked')) {
          //$(this).closest('.tr-row').find('select').attr('required', true);
          $(this).closest('.tr-row').find('select').removeAttr('required');
        } else {
          //$(this).closest('.tr-row').find('select').removeAttr('required');
        }

        //Pricing.
        // if ($.isNumeric(price)) {
        //   if (type == "assets-charges") {
        //       var horses = parseInt($(this).closest(".tr-row").find(".not-scratched-horses").length);
        //       var basicPrise = parseFloat($(this).closest(".tr-row").find(".orignalPriceSet").val());
        //       price=horses*basicPrise;
        //       $(this).closest(".tr-row").find(".priceSet").val(price);
        //       $(this).closest(".tr-row").find(".horse-assets-select").html("&nbsp;&nbsp; Horse: "+horses+", price: "+price+"($)");
    

        //       if ($(this).is(':checked')) {
        //         var assetPrice = parseFloat($(".AssetsPrice").val());
        //         assetPrice = assetPrice+price;
        //         total = total+price;
        //       }else{
        //         var assetPrice = parseFloat($(".AssetsPrice").val());
        //         assetPrice = assetPrice-price;
        //         total = total-price;

        //       }
        //     $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
        //     $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
          
        //   }
          
        // };
  });



 });