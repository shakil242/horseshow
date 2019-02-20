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

//         } else {
//             control.selectpicker('val', []);
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

// $('#allAssets').selectpicker().change(function(){toggleSelectAll($(this));}).trigger('change');