<script src="{{ asset('/js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/datatable/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/datatable/natural.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {


    oTable = $('#crudTable2').DataTable({
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

    	
    });   
    //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
          oTable.search($(this).val()).draw() ;
    })

    oTable1 = $('#crudTable_modals').DataTable({
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


    });
    //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
        oTable1.search($(this).val()).draw() ;
    })

    crudTable_forms = $('#crudTable_forms').DataTable({
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


    });
    //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#myInputTextField').keyup(function(){
        crudTable_forms.search($(this).val()).draw() ;
    })
    //Start: Assets Dynamic Datatable
	$('#AssetsSearchField').keyup(function(){
        var oTable = $('#AssetsTable').DataTable();
        oTable.search($(this).val()).draw() ;
  	});
  	$( document ).on( "change", "input.toggle-vis", function(e) {
	      // Get the column API object
	      var table = $('#AssetsTable').DataTable();
	      var column = table.column( $(this).attr('data-column') );
	      // Toggle the visibility
	      if($(this).is(':checked')){
	      	column.visible(true);
	      }else{
	      	column.visible(false);
	      }
	});
    //END: Assets Dynamic Datatable
    
    var crudTable = $('#crudTable').DataTable( {
	        //"paging":   false,
	        //"ordering": false,
	        //"info":     false,
	        "search": false,
	        "pageLength": 25,
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

    $(window).load(function(){
        $(document).ready(function() {
            $('.tooltip').tooltip();
        });
    });//]]>



</script>


