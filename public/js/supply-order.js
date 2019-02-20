// function toggleSelectAll(control) {
//     var allOptionIsSelected = (control.val() || []).indexOf("All") > -1;

//     var buttonText =control.parent().find("button.dropdown-toggle").children('span.filter-option');

//     function valuesOf(elements) {
//         return $.map(elements, function(element) {
//             return element.value;
//         });
//     }

//     if (control.data('allOptionIsSelected') != allOptionIsSelected) {
//         // User clicked 'All' option
//         if (allOptionIsSelected) {
//             control.selectpicker('val', valuesOf(control.find('option')));

//             var html =buttonText .html();
//             var Stringval = html.replace("Select All,", "");
//             buttonText.html(Stringval);
//             getSupplyOrder(control);


//         } else {
//             control.selectpicker('val', []);
//             setTimeout(function () {
//                 getSupplyOrder(control);
//             },500);

//         }
//     } else {
//         // User clicked other option
//         if (allOptionIsSelected && control.val().length != control.find('option').length) {
//             // All options were selected, user deselected one option
//             // => unselect 'All' option
//             control.selectpicker('val', valuesOf(control.find('option:selected[value!=All]')));
//             allOptionIsSelected = false;
//         } else if (!allOptionIsSelected && control.val().length == control.find('option').length - 1) {
//             // Not all options were selected, user selected all options except 'All' option
//             // => select 'All' option too
//             control.selectpicker('val', valuesOf(control.find('option')));
//             allOptionIsSelected = true;

//             var html =buttonText .html();
//             var Stringval = html.replace("Select All,", "");
//             buttonText.html(Stringval);

//         }
//     }
//     control.data('allOptionIsSelected', allOptionIsSelected);
// }
    $( "#splitClassForm" ).submit(function( event ) {
            var additionalCharges = $('#crudTable3 input:checkbox:checked').length;
            var horseCharges = $('#crudTable2 input:checkbox:checked').length;
            if(additionalCharges <= 0 || horseCharges <=0)
            {
                alert('You must have to select at least one additional charges and one horse.');
                event.preventDefault();
                return false;
            }else{
                return confirm('Do you really want to split the invoice?');
            }
    });

//$('#allOrders').selectpicker().change(function(){toggleSelectAll($(this));}).trigger('change');


$('body').on("change", ".checkbox-additional", function(){
     if($(this).is(':checked')){
        $(this).closest(".tr-row").find(".numberQty").attr("disabled", false);
     }else{
        $(this).closest(".tr-row").find(".numberQty").attr("disabled", true);
     }
});
