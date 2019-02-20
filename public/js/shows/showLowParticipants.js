$(document).ready(function() {
    var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    // Strip $ from salary column to make it numeric
                    var temp = column ==3  ? data.replace( /(&nbsp;|<([^>]+)>)/ig, '' ) : data.replace(/(&nbsp;|<([^>]+)>)/ig, "");

                    var temp2 = column ==3  ? temp.replace(/(Un-Scratch|<([^>]+)>)/ig, '(scratched)' ) : temp.replace("Un-Scratch", "");

                    var temp3 = column ==3  ? temp2.replace(/(Scratch|<([^>]+)>)/ig, '' ) : temp2.replace("Scratch", "");

                    return column ==3  ? temp3.replace(/(ed|<([^>]+)>)/ig, 'Scratched' ) : temp3.replace("Scratch", "");
                }
            }
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
                    columns: [ 0, 1, 2]
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
                    columns: [ 0, 1, 2]
                },
            })


        ],
        initComplete: function () {
            this.api().columns([1]).every( function () {
                var column = this;

                var title = $('.dataView thead th').eq($(this)[0][0]).text();

                if(title=='Class') {

                    var select = $('<select name="' + title + '" ><option value=""> -- ' + title + ' -- </option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {

                            if($(this).val()!='')
                                var myVal = $(this).find('option:selected').text();
                            else
                                var myVal = $(this).val();
                            var val = $.fn.dataTable.util.escapeRegex(myVal);

                            var show_id = column.context[0].oInit.id;

                            if($(this).val()>0)
                                var class_id =$(this).val();
                            else
                                var class_id = 0;

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.cells('', column[0]).render('display').sort().unique().each(function (d, j) {

                        var val = $('<div/>').html(d).text();

                        $(d).each(function () {
                            select.append('<option value="' + $(this).val() + '">' + val + '</option>')
                        })
                    });
                }else
                {
                    var select = $('<select name="' + title + '" ><option value=""> -- ' + title + ' -- </option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.cells('', column[0]).render('display').sort().unique().each(function (d, j) {

                        var val = $('<div/>').html(d).text();
                        select.append('<option value="' + val + '">' + val + '</option>')
                    });


                }

            } );
        },

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
            // alert(55);
            //  $("select").blur();
            //  $(".selectpicker").selectpicker();
        },
        "columnDefs": [ { type: 'natural', targets: [ 0, 1 ] } ]
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
    $('[data-toggle="tooltip"]').tooltip();
});

