$(document).ready(function() {
    var buttonCommon = {
        exportOptions: {
            // format: {
            //     body: function ( data, row, column, node ) {
            //         // Strip $ from salary column to make it numeric
            //         var temp = column ==2  ? data.replace( /(&nbsp;|<([^>]+)>)/ig, '' ) : data.replace(/(&nbsp;|<([^>]+)>)/ig, "");

            //         var temp2 = column ==2  ? temp.replace(/(Un-Scratch|<([^>]+)>)/ig, '(scratched)' ) : temp.replace("Un-Scratch", "");

            //         var temp3 = column ==2  ? temp2.replace(/(Scratch|<([^>]+)>)/ig, '' ) : temp2.replace("Scratch", "");

            //         return column ==2  ? temp3.replace(/(ed|<([^>]+)>)/ig, 'Scratched' ) : temp3.replace("Scratch", "");
            //     }
            // }
        }
    };


    var allTables = $('table.dataView').DataTable({
        "dom": 'Bfrtip',
        "buttons": [

            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells in column `C`
                    $('row c[r^="D"]', sheet).each( function () {
                        // Get the value
                        if ( $('is t', this).text().indexOf("(Scratched)") > -1 ) {
                            $(this).attr( 's', '35' );
                        }
                    });
                },
                className: 'btn btn-success',
                text: 'Export Participants in Excel',

                exportOptions: {
                    columns: [0,1,2,3,4,5 ]
                },
            } ),

            $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 12; //<-- set fontsize to 16 instead of 10
                },
                className: 'btn btn-success',

                text: 'Export Participants in PDF',
                exportOptions: {
                    columns: [ 0,1,2,3,4,5 ]
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
          //$('[data-toggle="tooltip"]').tooltip();
        },
        // "columnDefs": [ { type: 'natural', targets: [ 0, 1 ] } ]
        //  	 "columnDefs": [
        // 		{
        // 			'responsivePriority': 1,
        // 			'className': 'checkboxTh',
        // 			"targets": 0,
        // 			"width":"2%",

        // 		},

        // ]
    });
    allTables .on( 'page.dt', function () {
        $("select").val('');
    });
    $('#myInputTextField').keyup(function(){
          allTables.search($(this).val()).draw() ;
    });

    var allTab = $('table.dataViewSponsor').DataTable({
        "dom": 'Bfrtip',
        "buttons": [

            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells in column `C`
                    $('row c[r^="D"]', sheet).each( function () {
                        // Get the value
                        if ( $('is t', this).text().indexOf("(Scratched)") > -1 ) {
                            $(this).attr( 's', '35' );
                        }
                    });
                },
                className: 'btn btn-success',
                text: 'Export Participants in Excel',

                exportOptions: {
                    columns: [0,1,2,3,4]
                },
            } ),

            $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 12; //<-- set fontsize to 16 instead of 10
                },
                className: 'btn btn-success',

                text: 'Export Participants in PDF',
                exportOptions: {
                    columns: [ 0,1,2,3,4]
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
            $('.dataTables_paginate > .pagination').addClass('pager');

            //$('[data-toggle="tooltip"]').tooltip();
        },
        // "columnDefs": [ { type: 'natural', targets: [ 0, 1 ] } ]
        //  	 "columnDefs": [
        // 		{
        // 			'responsivePriority': 1,
        // 			'className': 'checkboxTh',
        // 			"targets": 0,
        // 			"width":"2%",

        // 		},

        // ]
    });
    allTab .on( 'page.dt', function () {
        $("select").val('');
    });
    $('#myInputTextField').keyup(function(){
        allTab.search($(this).val()).draw() ;
    });


    $('[data-toggle="tooltip"]').tooltip();
});

function  getScratchCount(class_id,show_id) {

    var url ="/shows/GetScratchCount/"+class_id+"/"+show_id;
    $.ajax({
        url:url ,
        method:"GET",
        context: this,
        "success": function(data) {
            $(".scratchHorses").html(data.scratch);
            $(".unScratchHorses").html(data.unScratch);
        }
    });
}