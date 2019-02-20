<script src="{{ asset('/js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/datatable/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/datatable/natural.js') }}"></script>

{{--for export button of datatabels--}}
<script src="{{ asset('/js/vender/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('/js/vender/jszip.min.js') }}"></script>
<script src="{{ asset('/js/vender/pdfmake.min.js') }}"></script>
<script src="{{ asset('/js/vender/vfs_fonts.js') }}"></script>
<script src="{{ asset('/js/vender/button.min.js') }}"></script>
{{--end export button of datatabels--}}

 {{--for bootstrap dialogue modal--}}
<link href="{{ asset('/css/vender/bootstrap-dialog.min.css') }}" rel="stylesheet" />
<script src="{{ asset('/js/vender/bootstrap-dialog.min.js') }}"></script>
{{--end bootstrap dialogue modal--}}


<script type="text/javascript">


/************End ajax loader for every ajax request*******************/

$(document).ready(function() {
   //Permissions Table
	$(document).on('click', '.pagination li a', function(){
		//jcf.customForms.replaceAll();
	});
   //End
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





   //Without Pagination
    nnTable = $('.Datatable_nopagination').DataTable({
    	"bPaginate": false,
    	"drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pager');
        },"fnDrawCallback": function() {
        	$(".selectpicker").selectpicker();
        }

    	
    });   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
          nnTable.search($(this).val()).draw() ;
    })


    nnTable = $('.DTB_nopagination').DataTable({
      "bPaginate": false,
      "drawCallback": function () {
            //$('.dataTables_paginate > .pagination').addClass('pager');
        },"fnDrawCallback": function() {
          $(".selectpicker").selectpicker();
        }
    });   //pay 
    $('#myInputTextField').keyup(function(){
          nnTable.search($(this).val()).draw() ;
    })



    //With
    oTable = $('#crudTable2').DataTable({
    	"pageLength": 18,
    	"language": {
    		 "paginate": {
	                  "first":      "First",
	                  "last":       "Last",
	                  "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
	                  "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
	              },

    	},
    	"drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pager');
        },"fnDrawCallback": function() {
        	$(".selectpicker").selectpicker();
        }

    	
    });   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
          oTable.search($(this).val()).draw() ;
    })
    //Class data table
    var allTables = $('table.dataTableView').DataTable({

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
    $('#mySearchTerm').keyup(function(){
          allTables.search($(this).val()).draw() ;
    })
    //Class data table for sorting default
    var defaultsort = $('table.defaultsort_first').DataTable({
    	"pageLength": 8,
    	"language": {
    		 "paginate": {
	                  "first":      "First",
	                  "last":       "Last",
	                  "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
	                  "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
	              },
    	},
    	//aaSorting: [[2, 'desc']],
    });
    $('#mySearchTerm').keyup(function(){
          defaultsort.search($(this).val()).draw() ;
    })


    //Saving values
    $('form.targetvalue').on('submit', function(e){
	   var $form = $(this);

	   // Iterate over all checkboxes in the table
	   allTables.$('input[type="checkbox"]').each(function(){
	      // If checkbox doesn't exist in DOM
	      if(!$.contains(document, this)){
	         // If checkbox is checked
	         if(this.checked){
	            // Create a hidden element
	            $form.append(
	               $('<input>')
	                  .attr('type', 'hidden')
	                  .attr('name', this.name)
	                  .val(this.value)
	            );
	         }
	      }
	   });
	});
    //allTables.column( 0 ).search( 'mySearchTerm' ).draw();
    //Secondary Table
    nTable = $('#crudTable3').DataTable({
    	"pageLength": 25,
    	"language": {
    		 "paginate": {
	                  "first":      "First",
	                  "last":       "Last",
	                  "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
	                  "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
	              },

    	},
        "bLengthChange": false,
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pager');
        }
    	
    });
    $('#searchField').keyup(function(){
        nTable.search($(this).val()).draw() ;
    })

    $( nTable.table().container() ).removeClass( 'form-inline' );


    //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
          nTable.search($(this).val()).draw() ;
    })
    //End


    //Secondary Table
    nTable9 = $('#crudTable9').DataTable({
      "pageLength": 15,
      "language": {
         "paginate": {
                    "first":      "First",
                    "last":       "Last",
                    "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                    "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
                },

      },
        "bLengthChange": false,
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pager');
        }
      
    });
    $('#searchField').keyup(function(){
        nTable9.search($(this).val()).draw() ;
    })

    $( nTable9.table().container() ).removeClass( 'form-inline' );


    //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
          nTable9.search($(this).val()).draw() ;
    })
    //End




    var allTables = $('table.dataViews').DataTable({
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
    });
    $('#myInputTextField').keyup(function(){
          allTables.search($(this).val()).draw() ;
    });
   





    BTable = $('#crudTable4').DataTable({
        "pageLength": 25,
        "language": {
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                "previous":   "<i class='fa fa-angle-left' aria-hidden='true'></i>"
            },

        },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pager');
        }


    });   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#searchMultiField').keyup(function(){
        BTable.search($(this).val()).draw() ;
    })


    
    var crudTable = $('#crudTable').DataTable( {
	        //"paging":   false,
	        //"ordering": false,
	        //"info":     false,
	        "search": false,
	        "pageLength": 5,
	        "language": {
	              "emptyTable":     "No data available in table",
	              "info":           "Showing _START_ to _END_ of _TOTAL_ entries",
	              "infoEmpty":      "Showing 0 to 0 of 0 entries",
	              "infoFiltered":   "(filtered from _MAX_ total entries)",
	              "infoPostFix":    "",
	              "thousands":      ",",
	              "lengthMenu":     "_MENU_ records per page",
	              "loadingRecords": "Loading...",
	              "processing":     "Processing...",
	              "search":         "Search: ",
	              "zeroRecords":    "No matching records found",
	              "paginate": {
	                  "first":      "First",
	                  "last":       "Last",
	                  "next":       "Next",
	                  "previous":   "Previous"
	              },
	              "aria": {
	                  "sortAscending":  ": activate to sort column ascending",
	                  "sortDescending": ": activate to sort column descending"
	              }
	          },
            "processing": true,
          	/*"serverSide": true,
	      	"ajax": {
	              "url": "http://laravel.backpack.com/admin/article/search",
	              "type": "POST"
        	},*/


    });// END of datatable 10.1


    $('table#dataTableSponsorInvoice').DataTable({
        "dom": 'Bfrtip',
        "bPaginate": false,
        "order": [[ 0, "desc" ]],
        "ordering": false,
        "buttons": [
            $.extend(true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                stripHtml: false,
                decodeEntities: true,
                footer: true,
                title: $("#Title").val() + '\n' + $("#sponsorTitle").val()+ '\n' +$("#locExportTitle").val()+ '\n' +$("#ContactExportTitle").val(),
                filename:$("#fileExport").val(),
                className: 'btn btn-success',
                text: 'Export in PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3],
                    stripHtml : true
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

    $.ajaxPrefilter(function(options, originalOptions, xhr) {
    	var token = $('meta[name="csrf_token"]').attr('content');
    	if (token) {
    		return xhr.setRequestHeader('X-XSRF-TOKEN', token);
    	}
  	});

    	// make the delete button work in the first result page
      register_delete_button_action();

      // make the delete button work on subsequent result pages
      $('#crudTable').on( 'draw.dt',   function () {
         register_delete_button_action();

               } ).dataTable();

      function register_delete_button_action() {
        $("[data-button-type=delete]").unbind('click');
        // CRUD Delete
        // ask for confirmation before deleting an item
        $("[data-button-type=delete]").click(function(e) {
          e.preventDefault();
          var delete_button = $(this);
          var delete_url = $(this).attr('href');

          if (confirm("Are you sure you want to delete this item?") == true) {
	              	$.ajax({
	                	url: delete_url,
	                	//data: {csrf_token:'<?php echo csrf_token() ?>'},
	                  	type: 'POST',
	                  	success: function(result) {
	                  			location.reload();
		                      // Show an alert with the result
		                      /*new PNotify({
		                          title: "Item Deleted",
		                          text: "The item has been deleted successfully.",
		                          type: "success"
		                      });*/
		                      // delete the row from the table
		                      //delete_button.parentsUntil('tr').parent().remove();
		                      //delete_button.parents('tr').remove();
	                  	},
	                  	error: function(result) {
	                      // Show an alert with the result
	                      /*new PNotify({
	                          title: "NOT deleted",
	                          text: "There&#039;s been an error. Your item might not have been deleted.",
	                          type: "warning"
	                      	});*/
	                  }
					});
	          	} else {
	              /*new PNotify({
	                  title: "Not deleted",
	                  text: "Nothing happened. Your item is safe.",
	                  type: "info"
	              });*/
          		}
        	});

      }//--- Register Delete Button


} );

// in order to show selcted Tab

var tabIndex = parseInt(window.location.hash.substring(1));
$(".show-"+tabIndex).trigger('click');
$('[data-toggle="tooltip"]').tooltip();


</script>