$(function () {
    $("body").on('change',"input[name='ordered_as']", function(){
        var unique = $(this).val();
        if (unique == 2) {
            $('.rider-horses').show();
            $('.rider-horses select').attr('required', true);
        }else{
            $('.rider-horses').hide();
            $('.rider-horses select').removeAttr('required');

        }
    });

    $("body").on('submit','#orderSupplyRequest',function(e){
       var checkedone = $(".checkbox-additional:checked").length;
        if (checkedone == 0) {
            alert(' Please select atleast one order supply. ');
            e.preventDefault();
        }
    });

    $('input:checkbox').change(function(){
        if($(this).is(':checked')) { 
            $(this).closest('tr').find('.destination-horse-select').show();
            $(this).closest('tr').find('.destination-horse-select select').removeAttr('disabled');
            $(this).closest('tr').find('.numberQty').removeAttr('disabled').attr('required',true);
            $('.selectpicker').selectpicker('refresh');
        }else{
            $(this).closest('tr').find('.destination-horse-select').hide();
            $(this).closest('tr').find('.destination-horse-select select').attr('disabled',true);
            $(this).closest('tr').find('.numberQty').removeAttr('required').attr('disabled',true);
            $('.selectpicker').selectpicker('refresh');
        }
        var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
        var total = parseFloat($(".totalPrice").val());
        var Qty = parseInt($(this).closest('.tr-row').find('.numberQty').val())
        

         if ($(this).is(':checked')) {
                var additionalPrice = parseFloat($(".additionalPrice").val());
                additionalPrice = additionalPrice+(price*Qty);
                total = total+(price*Qty);
              }else{
                var additionalPrice = parseFloat($(".additionalPrice").val());
                additionalPrice = additionalPrice-(price*Qty);
                total = total-(price*Qty);
              }
        $(".addAdditionalPrice").html('($) '+additionalPrice+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+additionalPrice+'">');
        $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
            
    });

        $('.numberQty').on("change", function () {
            var st = 0;
            $('.additiona-charges-row').each(function () {
                var checkbox = $(this).closest('.tr-row').find('.checkbox-additional').is(':checked');
                if (checkbox) {
                  var i = $('.numberQty', this);
                  var up = $(i).data('unit-price');
                  var q = $(i).val();
                  $(this).closest('.tr-row').find(".priceinqty").text("$"+up*q);
                  st = st + (up * q);
                }else{
                  var ii = $('.numberQty', this);
                  var upp = $(ii).data('unit-price');
                  var qq = $(ii).val();
                  $(this).closest('.tr-row').find(".priceinqty").text("$"+upp*qq);
                }; 
            });
            // Subtotal price
            var assetPrice = parseFloat($(".AssetsPrice").val());
            var total = st+assetPrice;
            $(".addAdditionalPrice").html('($) '+st+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+st+'">');
            
            $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
    });

    
});