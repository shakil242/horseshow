
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    // Strip $ from salary column to make it numeric
                    return  data.replace( /(&nbsp;|<([^>]+)>)/ig, '' );

                }
            }
        }
    };

    var allTables = $('table.dataTableView2').DataTable({
        "dom": 'Bfrtip',
        "buttons": [

            $.extend(true, {}, buttonCommon, {
                extend: 'excelHtml5',
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells in column `C`
                    $('row c[r^="D"]', sheet).each(function () {
                        // Get the value

                    });
                },
                className: 'btn btn-success',
                text: 'Export in Excel',

                exportOptions: {
                    columns: [0, 1, 2, 3]
                },
            }),

            $.extend(true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 10; //<-- set fontsize to 16 instead of 10
                },
                className: 'btn btn-success',

                text: 'Export in PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                },
            })


        ],
        "pageLength": 10,
        "search": false,

        "language": {
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
            }
        },"fnPreDrawCallback": function( oSettings ) {

        },"fnDrawCallback": function() {
        },
        "columnDefs": [ { type: 'natural', targets: [ 0, 1 ] } ]
    });

 $('table#viewDetail').DataTable({
        "dom": 'Bfrtip',
        "buttons": [

            $.extend(true, {}, buttonCommon, {
                extend: 'excelHtml5',
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells in column `C`
                    $('row c[r^="D"]', sheet).each(function () {
                        // Get the value
                        if ($('is t', this).text().indexOf("(Scratched)") > -1) {
                            $(this).attr('s', '35');
                        }
                    });
                },
                className: 'btn btn-success',
                text: 'Export in Excel',

                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
            }),

            $.extend(true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 10; //<-- set fontsize to 16 instead of 10
                },
                className: 'btn btn-success',

                text: 'Export in PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
            })


        ],
        "pageLength": 25,
        "language": {
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
            },

        },
    });

    bookIndex = 0;
    // Add button click handler



    // $("button").on("click", function() {
    //     $("#section_1")
    //         .clone()
    //         .attr("id", "section_2")
    //         .on("change", function() {
    //             var sec2Val = $(this).val();
    //             var delOption = $("#section_1 > option[value=" + sec2Val + "]").detach();
    //             optionHolder.push(delOption);
    //         })
    //         .insertAfter($("#section_1"));
    //     $(this).attr("disabled", "disabled");
    // });
    //
    // var optionHolder = [];
    // $("#section_1").on("change", function() {
    //     var sec1Val = $(this).val();
    //     if ($("#section_2")) {
    //         var delOption = $("#section_2 > option[value=" + sec1Val + "]").detach();
    //         optionHolder.push(delOption);
    //     }
    // });
    $('.addButton').on('click',function() {

        bookIndex++;
        var totalRows = $("#bookForm").find(".types").length;

        var $template = $('#bookTemplate'),
            $clone    = $template
                .clone()
                .removeClass('hide')
                .addClass('types')
                .removeAttr('id')
                .attr('data-book-index', bookIndex)
                .insertBefore($template);
        // Update the name attributes
        $clone.find('[name="typ"]').attr('placeholder', 'Type ' + parseInt(totalRows+1));
        $clone.find('[name="prc"]').attr('placeholder', '$ Price');
        $clone.find('[name="typ"]').attr('name', 'stallTypes['+totalRows+'][stall_type]');
        $clone.find('[name="prc"]').attr('name', 'stallTypes['+totalRows+'][price]');
    });


    $('.appCheck').change(function() {
        var id = $(this).attr('id');

        if(!$(this).is(':checked'))
        {
            $(".stallContainer-"+id).addClass('hide');
        }else {
            $(".stallFileds").prop('required',true);
            //jcf.customForms.destroyAll();
            $('.reject-' + id).prop('checked', false);
            //jcf.customForms.replaceAll();
            $(".comments-"+id).addClass('hide');
            $(".stallContainer-" + id).removeClass('hide');

        }

    });
    $('.rejCheck').change(function() {
        //jcf.customForms.destroyAll();
        var id = $(this).attr('id');
        $(".stallFileds").prop('required',false);

        $('.approve-'+id).prop('checked', false);
        //jcf.customForms.replaceAll();
        $(".stallContainer-"+id).addClass('hide');
        $(".comments-"+id).removeClass('hide');

    });

    $('.quantity').on('change keyup mousedown',function(){

        if($(this).val()!='')
        {
            var coup = $(this).parent().parent().next('.fieldsContainer');
            coup.find(".assign").prop('required',true);
        }else
        {
            var coup = $(this).parent().parent().next('.fieldsContainer');
            coup.find(".assign").prop('required',false);
        }
    });


    bookIndex1 = 0;
    // Add button click handler
    $('.addButton1').on('click',function() {

        bookIndex1++;

        var $template = $(this).parent().parent();
        $clone    = $template.clone();
        $clone.removeClass('hide');
        $clone.addClass('col-xs-offset-3 marginClass');
        $clone.attr('data-book-index', bookIndex1);
        $clone.children().find('.removeButton').removeClass('hide');
        $clone.children().find('.addButton1').addClass('hide');
        $clone.insertAfter($template);
    });


    window.utilityNo = 0;
    // Add button click handler

    $(document).on('click', '.addUtility',function() {

        var quantity =$(this).data('quantity');

        var totalQuantity =$(this).data('total-quantity');


        var id = $(this).data('id');
        var added = $(".cls-" + id).val();
        if(typeof added!='undefined') {
            var quantity = parseInt(quantity - added);
        }
        utilityNo++;



        if(utilityNo>=quantity)
        {
            utilityNo--;
            alertBox('You can not add stall more than '+totalQuantity);
            return false;
        }


        var $template = $(this).parent().parent();
        $clone    = $template.clone();
        $clone.removeClass('hide');
        $clone.addClass('marginClass');
        $clone.attr('data-book-index', utilityNo);
        $clone.children().find('.removeButton').removeClass('hide');
        $clone.children().find('.addUtility').addClass('hide');
        $clone.insertAfter($template);
    });


    addStallNo = 0;
    // Add button click handler

    $('.addStallNo').on('click',function() {

        addStallNo++;


        var $template = $(this).parent();
        $clone    = $template.clone();
        $clone.removeClass('hide');
        $clone.addClass('col-xs-offset-4 marginClass');
        $clone.attr('data-book-index', addStallNo);
        $clone.find('.removeButton').removeClass('hide');
        $clone.find('.addStallNo').addClass('hide');

        $clone.insertAfter($template);


    });


});


function deleteStable(id,show_id,template_id) {

        if (confirm('Are you sure you want to delete this?')) {
            var redirectUrl = '/shows/'+template_id+"/showStables#"+show_id;
            var url = '/shows/deleteStable/' + id+"/"+show_id;
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
                    location.reload();

                }, error: function () {
                    alert("error!!!!");
                }
            });
        }
    // var exitConfirmDialog = new BootstrapDialog.show({
    //     title: 'Confirmation Box',
    //     message: 'Are you sure you want to Delete this Stable?',
    //     closable: false,
    //     buttons: [
    //         {
    //             label: 'No',
    //             action: function (dialog) {
    //                 dialog.close();
    //             }
    //         },
    //         {
    //             label: 'Yes',
    //             cssClass: 'btn btn-success',
    //             action: function (dialog) {
    //                 var redirectUrl = '/shows/'+template_id+"/showStables#"+show_id;

    //                 var url = '/shows/deleteStable/' + id+"/"+show_id;
    //                 $.ajax({
    //                     url: url,
    //                     type: "GET",
    //                     beforeSend: function (xhr) {

    //                         var token = $('#csrf-token').val();
    //                         if (token) {
    //                             return xhr.setRequestHeader('X-CSRF-TOKEN', token);
    //                         }
    //                     },
    //                     success: function (data) {
    //                         location.reload();

    //                     }, error: function () {
    //                         alert("error!!!!");
    //                     }
    //                 });


    //                 dialog.close();
    //             }
    //         }]
    // });


}

function editStable(id,show_id) {


    var url = '/shows/getEditStable/' + id;
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
            $("#AddStable"+show_id).modal('show');
            $('#AddStable'+show_id).addClass('show');
                $(".modal-backdrop").addClass('show');
            $(".stable_id").val(data['id']);

            $(".stable_name_"+show_id).val(data['name']);
            $.each(data['quantity'], function( index, value ) {
                $(".stable_type_"+show_id+"_"+index).val(value);
            });


        }, error: function () {
            alert("error!!!!");
        }
    });

}

function AddStable(id,obj) {
    $(".show_id").val(id);
    var url = '/shows/StallTypesListing/' + id;
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
            $("#typesContainer").html(data);

        }, error: function () {
            alert("error!!!!");
        }
    });


    $("#AddStable").modal('show');
        $('#AddStable').addClass('show');
        $(".modal-backdrop").addClass('show');
}

function showStallTypes(id,obj) {
    $(".show_id").val(id);
    var url = '/shows/getStallTypes/' + id;

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

            if(data!=1)
                $("#typesContainer").html(data);

        }, error: function () {
            alert("error!!!!");
        }
    });

    $("#stallTypes").modal('show');
    $('#stallTypes').addClass('show');
    $(".modal-backdrop").addClass('show');

}

function removeFileds(obj,id) {


    if(id!=null)
    {
        if (confirm('Are you sure you want to delete this?')) {
            var url = '/shows/removeStallType/' + id;
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
                    if (data == 'success') {
                        var $row = obj.parents('.row'),
                            index = $row.attr('data-book-index');
                        $row.remove();
                    }
                }, error: function () {
                    alert("error!!!!");
                }
            });
        }

        // var exitConfirmDialog = new BootstrapDialog.show({
        //     title: 'Confirmation Box',
        //     message: 'Are you sure you want to Delete Stall Type?',
        //     closable: false,
        //     buttons: [
        //         {
        //             label: 'No',
        //             action: function (dialog) {
        //                 dialog.close();
        //             }
        //         },
        //         {
        //             label: 'Yes',
        //             cssClass: 'btn btn-success',
        //             action: function (dialog) {
        //                 var url = '/shows/removeStallType/' + id;
        //                 $.ajax({
        //                     url: url,
        //                     type: "GET",
        //                     beforeSend: function (xhr) {

        //                         var token = $('#csrf-token').val();
        //                         if (token) {
        //                             return xhr.setRequestHeader('X-CSRF-TOKEN', token);
        //                         }
        //                     },
        //                     success: function (data) {
        //                         if (data == 'success') {
        //                             var $row = obj.parents('.form-group'),
        //                                 index = $row.attr('data-book-index');
        //                             $row.remove();
        //                         }
        //                     }, error: function () {
        //                         alert("error!!!!");
        //                     }
        //                 });
        //                // dialog.close();
        //             }
        //         }]
        // });
    }
    else {
        var $row = obj.parents('.row'),
            index = $row.attr('data-book-index');
        $row.remove();
    }
};

function submitRequestResponse(obj,id) {



    var s = $("#requestResponse-"+id).serializeArray();

    var url = '/shows/'+id+"/stallRequestResponse";
    $.ajax({
        url: url,
        type: "POST",
        data:s,
        success: function (data) {
            if(data.status=='failed'){
               $(".stallsMessage-" + id).html(data.stallNumber+' Stall has already assigned');
               return false;
           }else {
                $(".viewResponseCon-" + id).html(data);
            }
        }, error: function () {
            alert("error!!!!");
        }
    });



}

function removeCurrent(obj) {


    utilityNo = parseInt(utilityNo)-1; //global variable


    obj.parent().parent().remove();
    var $row = obj.parents('.fieldsContainer'),
        index = $row.attr('data-book-index');
    $row.remove();
}

function stallRequest(obj,id) {

  //  var s = $("#requestResponse-"+id).serializeArray();
    var s = $("#requestResponse-"+id+" :input[value!='']").serializeArray();
    var url = '/shows/'+id+"/stallAssociateRiders";
    $.ajax({
        url: url,
        type: "POST",
        data:s,
        success: function (data) {
            $(".asignRiders-"+id).html(data);
        }, error: function () {
            alert("error!!!!");
        }
    });



}
function getHorses(obj,show_id) {

    var trainer_id = obj.val();

    if(trainer_id=='')
    {
        obj.closest('.recordCon').find("select.horseContainer").html("<option value=''>--Horse--</optioin>");
        obj.closest('.assignContainer').find("input.quantity").prop('required',false);

        return false;
    }

    var url = '/shows/getTrainerHorses/' + trainer_id+"/"+show_id;

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
            obj.closest('.recordCon').find("select.horseContainer").html(data);
            obj.closest('.assignContainer').find("input.quantity").prop('required',true);
        }, error: function () {
            alert("error!!!!");
        }
    });

}
function removeStallNumbers(obj) {

    var $row = obj.parents('.stallNumbers'),
        index = $row.attr('data-book-index');
    $row.remove();
}


function getRemainigStalls(id) {

    var url = '/shows/'+id+"/getRemainigStalls";
    $.ajax({
        url: url,
        type: "GET",
        success: function (data) {
            $("#viewRemainingStables").modal('show');
                $('#viewRemainingStables').addClass('show');
                $(".modal-backdrop").addClass('show');
            $(".remainingStalls").html(data);

        }, error: function () {
            alert("error!!!!");
        }
    });


}
function sendNotification(obj,user_id,showTitle) {


    var url = '/shows/'+user_id+"/sendNotification";
    $.ajax({
        url: url,
        type: "POST",
       // data:'stalls='+obj+'&showTitle='+showTitle,
        data:{stalls:obj,showTitle:showTitle},
        success: function (data) {
            $("#ajaxMsg").addClass('show');
            $("#ajaxMsg").show();
            $(".alertMsg").html(data.msg);

        }, error: function () {
            alert("error!!!!");
        }
    });



}


function stallRequestInOffice(obj,user_id,show_id) {

    var s = $("#requestResponse-"+user_id).serializeArray();


    var url = '/shows/'+user_id+"/"+show_id+"/stallRequestInOffice";
    $.ajax({
        url: url,
        type: "POST",
        data:s,
        success: function (data) {
            $("#ajaxMsg").show();
            $("#ajaxMsg").addClass('show');

            $(".alertMsg").html(data.msg);
            window.location.reload();
        }, error: function () {
            alert("error!!!!");
        }
    });
}