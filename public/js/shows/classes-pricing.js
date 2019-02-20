$(function () {
    $(".main-header").removeClass("sticky-top");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Checking trainer checkbox confirmation
    $('input.trainer_checkbox:checkbox').on("change", function () {
         if (!confirm('Are you sure?')) {
          //  jcf.customForms.destroyAll();
            var checkBoxes = $(this);
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
          //  jcf.customForms.replaceAll();
         }
    });


$("body").on('change','.unique-horses', function(){
    var unique = $(this).val();
    $(".numberQty").val(unique).trigger("change");

});

$("body").on('click','.checkout', function(){
   $(".collaps-on-click").find(".hiddenRow").css({ 'display': "table-row" });
});


    $("body").on('click','.collaps-on-click tr td', function(){
       var divId = $(this).closest("tr").attr('id');
       $(".collaps-on-click").find(".division-"+divId).collapse('toggle');
       if ($(this).closest("tr").find(".fa-minus").length == 1){
           $(".collaps-on-click").find(".division-"+divId).css({ 'display': "none" });
            $(this).closest("tr").find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
       }else{
           $(".collaps-on-click").find(".division-"+divId).css({ 'display': "table-row" });
           $(this).closest("tr").find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
       };
    });
    //Required Division checkbox
    $('.collaps-on-click .primary-required input:checkbox').change(function() {
        var divId = $(this).val();
     //   jcf.customForms.destroyAll();
        if($(this).is(':checked')) {
          $(this).closest('tr.tr-row').find('td:first-child').click();
            $(".collaps-on-click").find(".division-"+divId+" input:checkbox").trigger('click').prop("checked", true);
            $(".collaps-on-click").find(".division-"+divId+" select").attr('required', true);
            $(".collaps-on-click").find(".division-"+divId+" .hidden-disabled-val").attr('disabled', false);

        }else{
          $(this).closest('tr.tr-row').find('td:first-child').click();
            $(".collaps-on-click").find(".division-"+divId+" input:checkbox").trigger('click').prop("checked", false);
            $(".collaps-on-click").find(".division-"+divId+" select").attr('required', false);
            $(".collaps-on-click").find(".division-"+divId+" .hidden-disabled-val").attr('disabled', true);

        }


      //  jcf.customForms.replaceAll();
    });

    //Not Required Division checkbox
    $('.collaps-on-click .primary-not-required input:checkbox').change(function() {
        var divId = $(this).val();
       // jcf.customForms.destroyAll();
        if($(this).is(':checked')) {
              $(this).closest('tr.tr-row').find('td:first-child').click();
               
               $(".collaps-on-click").find(".division-"+divId+" input:checkbox").each(function() {
                if($(this).is(':checked')){
                    var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
                    var total = parseFloat($(".totalPrice").val());
                    var assetPrice = parseFloat($(".AssetsPrice").val());
                    if ($.isNumeric(price)) {
                        assetPrice = assetPrice-price;
                        total = total-price;
                        $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
                        $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
             
                    }
                }
               });
            $(".division-"+divId+" .child-price").hide();
            $(".collaps-on-click").find(".division-"+divId+" input:checkbox").prop("checked", true);
            $(".collaps-on-click").find(".division-"+divId+" select").attr('required', true);
        }else{
          //$(this).closest('tr.tr-row').find('td:first-child').click();
            $(".division-"+divId+" .child-price").show();
            $(".collaps-on-click").find(".division-"+divId+" input:checkbox").each(function( index ) {
              if($(this).attr('onclick') =='event.preventDefault()'){
                var assetPrice = parseFloat($(".AssetsPrice").val());
                  //On uncheck. Add values
                  var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
                    var total = parseFloat($(".totalPrice").val());
                    if ($.isNumeric(price)) {
                         assetPrice = assetPrice+price;
                        total = total+price;
                        $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
                        $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
             
                    }
              }else{
                $(".collaps-on-click").find(".division-"+divId+" input:checkbox").prop("checked", false);
                $(".collaps-on-click").find(".division-"+divId+" select").attr('required', false);
              } 
            });
            // if ($(".collaps-on-click").find(".division-"+divId+" input:checkbox").attr('onclick') =='event.preventDefault()') {
            // }; 
            // $(".collaps-on-click").find(".division-"+divId+" input:checkbox").prop("checked", false);
            // $(".collaps-on-click").find(".division-"+divId+" select").attr('required', false);
            

        }

      //  jcf.customForms.replaceAll();
    });
        //Not Required Division checkbox
    $('.collaps-on-click input.primary-not-required-child:checkbox').change(function() {
        var divId = $(this).closest("tr").attr("data-div-id");
        var prices = $(".collaps-on-click").find("tr#"+divId+" .priceSet").val();
        var assetPrice = parseFloat($(".AssetsPrice").val());
        var outertotal = parseFloat($(".totalPrice").val());
        
      //  jcf.customForms.destroyAll();

        if($(this).is(':checked')) {
            
            //If all are checked
            if ($(".division-"+divId+" input.primary-not-required-child:checkbox:checked").length == $(".division-"+divId).length) {
                $(".division-"+divId+" .child-price").hide();
                $(".collaps-on-click").find("tr#"+divId+" input:checkbox").trigger('click').prop("checked", true);
            }else{
                $(".division-"+divId+" .child-price").show();
            }
            // $(".collaps-on-click").find("tr#"+divId+" input:checkbox").trigger('click').prop("checked", true);
            // $(".collaps-on-click").find("tr#"+divId+" select").attr('required', true);
        }else{
             $(".division-"+divId+" .child-price").show();
            if($(".collaps-on-click").find("tr#"+divId+" input:checkbox").is(':checked')){
                assetPrice = assetPrice-prices;
                outertotal = outertotal-prices;
                //Un checking the checkbox
                $(".collaps-on-click").find("tr#"+divId+" input:checkbox").prop("checked", false);
                $(".collaps-on-click").find("tr#"+divId+" select").attr('required', false);

                // add price
                $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
                $(".addTotalPrice").html('($) '+outertotal+'<input name="total_price" type="hidden" class="totalPrice" value="'+outertotal+'">');
   
                //Add each checkbox value to total
                $('.collaps-on-click input.primary-not-required-child:checkbox').each(function() {
                    var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
                    var total = parseFloat($(".totalPrice").val());
                    if ($.isNumeric(price)) {
                         assetPrice = assetPrice+price;
                        total = total+price;
                        $(".addAssetPrice").html('($) '+assetPrice+'<input type="hidden" class="AssetsPrice" name="assets_price" value="'+assetPrice+'">');
                        $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
             
                    }
                });
            } 

        }
      //  jcf.customForms.replaceAll();
     });
    

    $('.additional-c-wraper input:checkbox:checked').each(function() {
        var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
        var total = parseFloat($(".totalPrice").val());
        var additionalPrice = parseFloat($(".additionalPrice").val());
        additionalPrice = additionalPrice+price;
        total = total+price;

        $(".addAdditionalPrice").html('($) '+additionalPrice+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+additionalPrice+'">');
        $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
    });
 $('input:checkbox').change(function(){
        //adding required
        if($(this).is(':checked')) {
          $(this).closest('tr').find(".qualifying-confirm").show();
          $(this).closest('tr').find('.single-class .priceSet, .single-class .orignalPriceSet').removeAttr('disabled');
          $(this).closest('.tr-row').find('select').attr('required', true);
        } else {
          $(this).closest('tr').find(".qualifying-confirm").hide();
            $(this).closest('tr').find('.single-class .priceSet, .single-class .orignalPriceSet').attr('disabled',true);
          $(this).closest('.tr-row').find('select').removeAttr('required');
        }

        var type = $(this).attr("data-attr");
        var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());
        var total = parseFloat($(".totalPrice").val());
        var Qty = parseInt($(this).closest('.tr-row').find('.numberQty').val())
        
        

        //Pricing.
        if ($.isNumeric(price)) {
          if (type == "assets-charges") {
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
            
          };
          
        };
    });

$('body').on("change", "select.qualifing-drpdwn", function(){
     var $selector = $(this).closest(".tr-row");
     var qualifingPrice = parseFloat($selector.find(".qualifing-div .qualifing-price").val());
     var orignalPrice = $selector.find(".orignalPriceSet").val();
    
    $(this).closest(".tr-row").find("input:checkbox").trigger("click");
     if($(this).val() == 1){
        var newPrice = parseFloat(orignalPrice)+parseFloat(qualifingPrice);
        $selector.find(".orignalPriceSet").val(newPrice);
        $selector.find(".actual-price-set").html("($) "+newPrice);
     }else{
        var newPrice = parseFloat(orignalPrice)-parseFloat(qualifingPrice);
        $selector.find(".orignalPriceSet").val(newPrice);
        $selector.find(".actual-price-set").html("($) "+newPrice);
     }
        AddHorseTotal($selector.find("select.selectpicker"));
     $(this).closest(".tr-row").find("input:checkbox").trigger("click");
});


$('body').on("change", "select.selectpicker", function(){
    //Disable check box
    if ($(this).closest(".tr-row").find("input:checkbox").is(':checked')) {
      $(this).closest(".tr-row").find("input:checkbox").trigger("click");
    }
    //Add
    AddHorseTotal($(this));
    //Enable checkbox
    if ($(this).closest(".tr-row").find("input:checkbox").is(':checked')) {
       $(this).closest(".tr-row").find("input:checkbox").trigger("click");
    }

});

 // $('.numberQty').change(function(){
 //    var checkbox = $(this).closest('.tr-row').find('.checkbox-additional').is(':checked');
 //    var qty = $(this).val();
 //    var price = parseFloat($(this).closest('.tr-row').find('.priceSet').val());

 //    if(checkbox){
 //      var total = (price*qty);
 //    };
 //    $(".addAdditionalPrice").html('($) '+additionalPrice+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+additionalPrice+'">');
 // });
        $( "#orderSupplyRequest" ).submit(function( event ) {
            var numberOfChecked = $('input:checkbox:checked').length;
            if(numberOfChecked <= 0)
            {
            alert('You must have to select at least one checkbox');
            return false;
            }
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

    $('.approvedNumberQty').on("change", function () {
        var st = 0;
        var ct = $(this).data('id');

        $('.additiona-charges-row').each(function () {


            var orderStatus = $(this).closest('.tr-row').find('select.orderStatus option:selected').val();
            //if (orderStatus=='approve') {
                var i = $('.approvedNumberQty', this);
                var up = $(i).data('unit-price');
                var q = $(i).val();
                $(this).closest('.tr-row').find(".priceinqty").text("$"+up*q);
                st = st + parseFloat(up * q);


        });
        // Subtotal price
        var assetPrice = parseFloat($(".AssetsPrice").val());
        var total = st+assetPrice;

       // alert(st);

        $(".addAdditionalPrice").html('($) '+st+'<input type="hidden" class="additionalPrice" name="additional_price" value="'+st+'">');

       // $(".addTotalPrice").html('($) '+total+'<input name="total_price" type="hidden" class="totalPrice" value="'+total+'">');
    });

    //If rider is selected. It checks the checkbox.
    $('body').on('change','.rider-drp-select',function(){
        if($(this).find("option:selected").val() >= 1){
            if (!$(this).closest(".tr-row").find("input:checkbox").is(':checked')) {
              $(this).closest(".tr-row").find("input:checkbox").trigger("click");
            }
        }
    });

});

function getSupplyOrder(v) {

    var id = v.val();
    var jsonString = JSON.stringify(id);

    if(jsonString=='null') {
        //jcf.customForms.destroyAll();

        $(".numberQty").val('1');
        $(".checkbox-additional").prop('checked','');
        $(".numberQty").trigger('change');
        //jcf.customForms.replaceAll();

        return false;
    }

    var url = '/shows/trainer/getSplitInvoice';

    $.ajax({
        url: url,
        type: "GET",
        data : { data : jsonString },

        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            var obj=[];
           obj = jQuery.parseJSON(data);
            //jcf.customForms.destroyAll();
            var value;
            $.each( obj, function( key, value ) {
                $(".orderQty-"+value.id).val(value.approveQty);
                $(".orderQty-"+value.id).attr("disabled", false).trigger('change');
                $("input.orderSupply-"+value.id).prop('checked','checked');
            });
            //jcf.customForms.replaceAll();
            }
        , error: function () {
            alert("error!!!!");
        }
    }); //end of ajax




}

function checkRiderAgeRestrcitions(obj,id,horse_id,show_id)
{

    var url = '/shows/riderAgeRestriction/'+id+"/"+horse_id;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            if(data=='fail')
            {

                alertBox('This Rider can not be added due to Age restriction for this class.','TYPE_WARNING');
                obj.prop("selected",false);
                obj.val('');
                return false;
            }

        }
    });



}



function  getRiderFeed(obj,id) {

    var horse_id = obj.val();
    var show_id = $(".show_id").val();

    checkRiderRestriction(obj,id,horse_id,show_id);


    checkRiderAgeRestrcitions(obj,id,horse_id,show_id);


    var url = '/shows/trainerBreeds/'+id+"/"+horse_id;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            if(data=='fail')
            {

              alertBox('This Rider can not be added due to Rider status restriction for this class.','TYPE_WARNING');
                obj.prop("selected",false);
                obj.val('');
                return false;
            }

        }
    });

}

function checkHorseAgeRestrcitions(obj,id,serial,horse_id,clickedIndex,outer)
{

    var brands = $('.Horses-'+id+' option:selected');

    var url = '/shows/horseAgeRestriction/'+id+"/"+horse_id;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            if (data == 'fail') {
                // $(this).find('option:selected')
                //  $('.selectpicker option').eq(clickedIndex).val();
                obj.find('option').eq(clickedIndex).removeAttr("selected");
                // obj.find('option').eq(clickedIndex).attr("selected",false);
                // obj.find('option').eq(clickedIndex).prop("selected",false);

                $('.selectpicker').selectpicker('render');
                alertBox('This horse can not be added due to Age restriction for this class.', 'TYPE_WARNING');
                return false;
            }
            else {
                getTrainers(obj, id, serial, horse_id, clickedIndex, outer);
            }
        }
        });

}

function  getTrainers(obj,id,serial,horse_id,clickedIndex,outer) {

    var brands = $('.Horses-'+id+' option:selected');

    var url = '/shows/horseBreeds/'+id+"/"+horse_id;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            if(data=='fail')
            {
               // $(this).find('option:selected')
              //  $('.selectpicker option').eq(clickedIndex).val();
                obj.find('option').eq(clickedIndex).removeAttr("selected");
                // obj.find('option').eq(clickedIndex).attr("selected",false);
                // obj.find('option').eq(clickedIndex).prop("selected",false);

                $('.selectpicker').selectpicker('render');
                alertBox('This horse can not be added due to breed restriction for this class.','TYPE_WARNING');
                return false;
            }else

            {

                var selected = [];
                $(".selectRiders-"+id).html('');
                $(".selectQty-"+id).html('');

                $(".division-selectRiders-"+id).html('');
                var Html ='';
                if(brands.length==0)
                {

                    $(".selectRiders-"+id).html('<label>Select Riders</label>');

                }
                $(brands).each(function(index, brand){
                    //  jcf.replaceAll();
                    if($(this).val()!='') {
                        var Html ='';
                       // jcf.customForms.destroyAll();

                        var rider = $(".ridersContainer").html();
                        var qtyContainer = $(".qtyContainer").html();


                        var qty = '<div class="form-group qty-'+$(this).val()+'" style="margin-top: 5px;"> <label  for="'+$(this).text()+'">'+$(this).text()+'</label>'+qtyContainer+'</div>';


                        Html = '<div class="form-group riders-'+$(this).val()+'" style="margin-top: 5px;"> <label  for="'+$(this).text()+'">'+$(this).text()+'</label>'+rider+'</div>';
                        Html2 = '<div class="form-group div-riders-'+$(this).val()+'" style="margin-top: 5px;"> <label  for="'+$(this).text()+'">'+$(this).text()+'</label>'+rider+'</div>';
                        if(outer == null) {

                        $(".selectQty-"+id).append(qty);
                        $(".selectQty-"+id).find('.qty-'+$(this).val()+' input').attr("name",'assets['+serial+'][qty]['+$(this).val()+']');

                        $(".selectRiders-"+id).append(Html);
                        $(".selectRiders-"+id).find('select').attr("disabled", false);
                        $(".selectRiders-"+id).find('select').attr("required", true);
                        $(".selectRiders-"+id).find('.riders-'+$(this).val()+' select').attr("name",'assets['+serial+'][riders]['+$(this).val()+']');
                        $(".selectRiders-"+id).find('.riders-'+$(this).val()+' select').attr("onchange",'getRiderFeed($(this),'+id+')');


                        }else{
                        //FOr division

                        $(".division-selectRiders-"+id).append(Html2);
                        $(".division-selectRiders-"+id).find('select').attr("disabled", false);
                        $(".division-selectRiders-"+id).find('select').attr("required", true);
                        $(".division-selectRiders-"+id).find('.div-riders-'+$(this).val()+' select').attr("name",'assets[division]['+outer+'][innerclasses]['+serial+'][riders]['+$(this).val()+']');
                        $(".division-selectRiders-"+id).find('.div-riders-'+$(this).val()+' select').attr("onchange",'getRiderFeed($(this),'+id+')');
                        };
                        
                       

                    }

                });

               // jcf.customForms.replaceAll();

            }


        }
    });
}





function collectHorses(){
      var arr = new Array();
      var newArr;
      $('.asset-participate-form table tbody tr').each(function(i, val){
        /*console.log(i, val);*/
        td = $(this).find('.selectpicker').val();
        if(td!=undefined || td!=null){
          /*console.log(td);*/
           $.merge(arr, td);
        }

      });
      var total = unique(arr).length;
      if ($(".not-scratched-horses").length > 0) {
        total = total+$(".not-scratched-horses").length;
        total = total+$(".scratched-horses").length;
      };
    $('.unique-horses').val(total).trigger("change");
}
function AddHorseTotal(ths){
    var $selector = ths;
    var selectedhorses = parseInt($selector.closest(".tr-row").find(".not-scratched-horses").length);
    var horses = parseInt($selector.find("option:selected").length);
    var price = parseFloat($selector.closest(".tr-row").find(".orignalPriceSet").val());
    if (selectedhorses>0) {
      horses = horses+selectedhorses;
    }
    if (horses == 0) {
        //horses = 1;
    };
    var total = horses*price;

    $selector.closest(".tr-row").find(".priceSet").val(total);
    $selector.closest(".tr-row").find(".horse-assets-select").html("&nbsp;&nbsp; Horse: "+horses+", price: "+total+"($)");
    if (selectedhorses>0) {
      // $selector.find("input:checkbox").trigger("click");
      // $selector.find("input:checkbox").trigger("click");
    }
}

function unique(array){
    return $.grep(array,function(el,index){
        return index == $.inArray(el,array);
    });
}
function checkDivisions(obj,id,divisionId)
{
if(obj.prop('checked') == false)
{
$(".division-"+id).html("");
return false;
}
if(divisionId==0)
{
$(".division-"+id).html("No Division Exist");
return false;
}

var url = '/shows/checkDivisions/'+id;
$.ajax({
url: url,
type: "POST",
data:{divisions:divisionId},
success: function (data) {
$(".division-"+id).html(data);
}
});

}
function  getScoringClasses(obj,asset_id,divisionId,is_required_point_selection,isScoringClasses) {

    if(is_required_point_selection==1) {
        checkDivisions(obj,asset_id,divisionId);
    }
if(isScoringClasses>0) {
    var url = '/shows/getScoringClasses/' + asset_id;

    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            var total = 0;
            $.each(data, function (key, value) {
                $.each(value, function (k, v) {
                   // jcf.customForms.destroyAll();
                    if (obj.is(':checked')) {
                        $(".asset_" + v).parent().parent().parent().find('tr:first-child').find("span.glyphicon-plus").trigger("click");
                        //$(".asset_" + v).attr('checked','checked').prop('checked',true);
                        if ($(".asset_" + v).is(':checked')) {
                           if($(".asset_" + v).attr('data-belong') == "wholedivisions"){
                            var divid = $(".asset_" + v).closest('tr.tr-row').data('div-id');
                            $('tr#'+divid).find('.form-check-label .form-check-input').attr('onclick', 'event.preventDefault()');
                          };
                        }else{
                          
                          $(".asset_" + v).trigger('click');
                          if($(".asset_" + v).attr('data-belong') == "divisions"){
                            var divid = $(".asset_" + v).closest('tr.tr-row').data('div-id');
                            var totalDivs = $(".division-" + divid).length;
                            var checkedones = $(".division-"+divid+" input:checkbox:checked").length;
                            if (checkedones == totalDivs) {
                              $('tr#'+divid).find('.form-check-label .form-check-input').attr('onclick', 'event.preventDefault()');
                            };
                            
                          }
                          if($(".asset_" + v).attr('data-belong') == "wholedivisions"){
                            var divid = $(".asset_" + v).closest('tr.tr-row').data('div-id');
                            if(!$('tr#'+divid).find('.form-check-label .form-check-input').is(':checked')){
                              $('tr#'+divid).find('.form-check-label .form-check-input').trigger('click').attr('onclick', 'event.preventDefault()');
                            }
                            $('tr#'+divid).find('.form-check-label .form-check-input').attr('onclick', 'event.preventDefault()');
                          };
                        }
                        $(".asset_" + v).attr('onclick', 'event.preventDefault()');
                        $(".asset_" + v).parent().css('opacity','.4');
                    } else {
                        //$(".asset_" + v).attr('checked',false).prop('checked',false);
                        if($(".asset_" + v).attr('data-belong') == "divisions"){
                            var divid = $(".asset_" + v).closest('tr.tr-row').data('div-id');
                            var totalDivs = $(".division-" + divid).length;
                            var checkedones = $(".division-"+divid+" input:checkbox:checked").length;
                            if (checkedones != totalDivs) {
                              $('tr#'+divid).find('.form-check-label .form-check-input').removeAttr('onclick');
                            };
                            
                          }
                          if($(".asset_" + v).attr('data-belong') == "wholedivisions"){
                            var divid = $(".asset_" + v).closest('tr.tr-row').data('div-id');
                            $('tr#'+divid).find('.form-check-label .form-check-input').removeAttr('onclick');
                          };
                          
                        $(".asset_" + v).parent().css('opacity','1');
                        $(".asset_" + v).removeAttr('onclick');
                        $(".asset_" + v).trigger('click');
                        
                    }
                   // jcf.customForms.replaceAll();
                });
            });

        }
    });
}

}
function checkShowRestriction(obj,id,serial,horse_id,clickedIndex,outer,show_id) {

// var brands = $('.Horses-'+id+' option:selected');

var url = '/shows/checkShowRestriction/'+id+"/"+horse_id+"/"+show_id;
$.ajax({
url: url,
type: "GET",
beforeSend: function (xhr) {
    var token = $('#csrf-token').val();
    if (token) {
        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
    }
},
success: function (data) {
    var text='<div class="row" style="margin-top: 13px;">';
    if(data['unFilledData'].length > 0) {


        obj.find('option').eq(clickedIndex).removeAttr("selected");
        // obj.find('option').eq(clickedIndex).attr("selected",false);
        $('.selectpicker').selectpicker('render');


        obj.closest(".tr-row").find(".horse-assets-select").html(''); // empty Price value

        for (i = 0; i < data['unFilledData'].length; i++) {
            text += '<div style="border-top: solid 1px #cdcdcd;line-height: 30px;" class="col-sm-12">' + data['unFilledData'][i] + "</div>";
        }
        text += '<div style="line-height: 30px;" class="col-sm-12"> Please click on horse name to update the details ' + data['Name'] + "</div>";

        text += '</div>';
        alertBox("In order to participate in the show you need to add the following details for show type  <h4 style='float: right; line-height: 21px; color: #651e1c'>" + data['showType'] + "</h4>  <span style='font-size:11px;color: #651e1c'>(Please Enter N/A if you don't have detail for below fields)</span>" + text);

        return false;

    }else{

       checkHorseAgeRestrcitions(obj, id, serial, horse_id, clickedIndex, outer);

        collectHorses();
    }
}
});
}
function checkRiderRestriction(obj,id,rider_id,show_id) {

// var brands = $('.Horses-'+id+' option:selected');

    var url = '/shows/checkRiderRestriction/'+id+"/"+rider_id+"/"+show_id;
    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {
            var text='<div class="row" style="margin-top: 13px;">';
            if(data['unFilledData'].length > 0) {

                obj.prop("selected",false);
                obj.val('');
                obj.closest(".tr-row").find("input:checkbox").trigger("click");

                // obj.closest(".tr-row").find(".horse-assets-select").html(''); // empty Price value

                for (i = 0; i < data['unFilledData'].length; i++) {
                    text += '<div style="border-top: solid 1px #cdcdcd;line-height: 30px;" class="col-sm-12">' + data['unFilledData'][i] + "</div>";
                }
                text += '<div style="line-height: 30px;" class="col-sm-12"> Please click on rider name to update the details ' + data['Name'] + "</div>";

                text += '</div>';
                alertBox("In order to participate in the show you need to add the following details for show type  <h4 style='float: right; line-height: 21px; color: #651e1c'>" + data['showType'] + "</h4>  <span style='font-size:11px;color: #651e1c'>(Please Enter N/A if you don't have detail for below fields)</span>" + text);

                return false;

            }
        }
    });
}
