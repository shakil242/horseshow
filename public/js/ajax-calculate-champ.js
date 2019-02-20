//Onload click. By default show the form.
$(function(){

    $('form.ajax-form-submit').on('submit', function(e) {
        e.preventDefault();
        $(".ajax-loading").show();
        var base_url = window.location.protocol + "//" + window.location.host + "/";
        $.ajax({
            url: base_url + "shows/champion/saved",
            type: 'POST',
            dataType: 'html',
            //data: {"form":$(this).serialize() , 'classes':$("#allClasses").val()},
            data: {"form":$(this).serialize() , 'classes':$("#allClasses").val()},
            beforeSend: function (xhr) {
                var token = '{{ csrf_token() }}';
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            success: function(data) {
                //window.location = base_url + "master-template/billing/multipleInvocie";
            }
        });

    });




});
// //Ajax datatable for Assets forms.
// $( document ).on( "click", "#display-assets-tables li a", function() {

//     var asset_id = $(this).attr("data-attr");
//     var base_url = window.location.protocol + "//" + window.location.host + "/";
//     var open_tab_id = $("#form_id_assets").val();
//     if (asset_id == open_tab_id) { return false; }
//     $("#form_id_assets").val(asset_id);
//     $.ajax({
//           "url": base_url+'master-template/get-asset-ajax/'+asset_id,
//           "success": function(json) {
//               var tableHeaders= "";
//               if (json.columns == null) {
//                 $(".ToggleColumb").html('');
//                 $("#tableDiv").empty();
//                 $("#tableDiv").append('<table id="AssetsTable" class="table primary-table dataTable no-footer" cellspacing="0" width="100%" class="table primary-table"><thead><tr> No data fields found</tr></thead><tr><td> No data fields added to this form</td></tr></table>');
//                 $(".add-assetform-btn").hide();
//                 return true;
//               };
//               $(".add-assetform-btn").show();

//                 if(json.assetType !='')
//                  $(".AssetType").html("<h4>"+json.assetType+"</h4>")

//               $(".ToggleColumb").html('<div class="dropdown-list"> <a href="javascript:void(0)" class="linkss-selected"><div class="selected">Show/Hide columns <span class="glyphicon glyphicon-menu-down pull-right" aria-hidden="true"></span> </div></a> <div class="dropdown-values"></div> </div>');

//               $.each(json.columns.name, function(i, val){
//                   tableHeaders += "<th>" + val + "</th>";
//                   $(".dropdown-values").append('<p><label><input type="checkbox" checked="checked" class="toggle-vis" data-column="'+i+'" /> '+val+'</label> </p> ');

//               });
//               $("#tableDiv").empty();
//               $("#tableDiv").append('<table id="AssetsTable" class="table primary-table dataTable no-footer" cellspacing="0" width="100%" class="table primary-table"><thead><tr>' + tableHeaders + '</tr></thead></table>');
//               $('#AssetsTable').DataTable({
//                   data: json.dat,
//                   "stateSave": true,
//                    "columnDefs": [{
//                         "defaultContent": "-",
//                         "targets": "_all"

//                       }],
//                   "language": {
//                        "paginate": {
//                                   "first":      "First",
//                                   "last":       "Last",
//                                   "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
//                                   "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
//                                   },
//                               },
//               });
//               var total = $(".ToggleColumb .toggle-vis").length;
//               $(".ToggleColumb .toggle-vis").each(function( index ) {
//                   if(index > 3 && index < total-2){
//                     $(this).trigger('click');
//                     $(this).attr('checked', false);
//                   }
//                });
//               $('[data-toggle="tooltip"]').tooltip();
//           },
//           "dataType": "json"
//       });
// });



