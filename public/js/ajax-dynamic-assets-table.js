//Onload click. By default show the form.
$(document).ready(function(){

  $(".form_id_assets").val('');
  $('#display-assets-tables li a.active').trigger('click');
  $("#myInputTextField").val("").trigger('keyup');

    $('input.singleCheck').on('change', function() {
        var source = $(this);
        var target = $('.' + source.attr('data-target'));
        if ($('input[data-target='+source.attr('data-target')+']:checked').length) target.show();
        else target.hide();
    });


    $(".allCheck").on('change', function() {

        if($(this).is(':checked')){
            $('input.singleCheck').parent().find("div").removeClass('chk-unchecked');
            $('input.singleCheck').parent().find("div").addClass('chk-checked');
            $('input.singleCheck').attr("checked",true).prop("checked",true);

            $(".commulativeInvoice").show();
        }
        else
        {
            $('input.singleCheck').parent().find("div").removeClass('chk-checked');
            $('input.singleCheck').parent().find("div").addClass('chk-unchecked');
            $('input.singleCheck').attr("checked",false).prop("checked",false);
            $(".commulativeInvoice").hide();
        }


        });


    $('#commulativeInvoice').click(function() {
        var base_url = window.location.protocol + "//" + window.location.host + "/";

        $.ajax({

            url: base_url + "master-template/billing/setMultiInvoice",
            type: "GET",
            beforeSend: function (xhr) {
                var token = '{{ csrf_token() }}';
                $("#ajax-loading").show();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },

            data: $('.singleCheck:checked').serialize(),
            success: function(data) {
                $("#ajax-loading").hide();
                
                window.location = base_url + "master-template/billing/multipleInvocie";
            }
        });


    });


    // $(".selectAll").click(function () {
    //     $('input:checkbox').not(this).checkbox({checked: this.checked});
    // });

    // $('input.allCheck').toggle(function(){
    //   alert(444);
    //     $('input.singleCheck').attr('checked','checked');
    // },function(){
    //     $('input.singleCheck').removeAttr('checked');
    // })

 
});

 //Ajax datatable for Assets forms.
  $( document ).on( "click", "#display-assets-tables li a", function() {
      $(".asset-position-export-excel").attr('href',"#");
      $("#myInputTextField").val("").trigger('keyup');
      var asset_id = $(this).attr("data-attr");
      var base_url = window.location.protocol + "//" + window.location.host + "/";
      var open_tab_id = $(".form_id_assets").val();
     // $("#ajax-loading").show();

      if (asset_id == open_tab_id) { return false; }
      $(".form_id_assets").val(asset_id);
      $.ajax({
            "url": base_url+'master-template/get-asset-ajax/'+asset_id,
            beforeSend: function (xhr) {
                  $("#ajax-loading").show();
            },
            "success": function(json) {
                var tableHeaders= "";
                if (json.columns == null) {
                  $(".ToggleColumb").html('');
                  $("#tableDiv").empty();
                  $("#tableDiv").append('<table id="AssetsTable" class="table table-line-braker mt-10 custom-responsive-md"><thead><tr> No data fields found</tr></thead><tr><td> No data fields added to this form</td></tr></table>');
                  $(".add-assetform-btn").hide();
                  return true;
                };
                $(".add-assetform-btn").show();

                  if(json.assetType !=''){
                   $(".AssetType").html("<h4>"+json.assetType+"</h4>")
                      if (json.assetType == "Primary Asset") {
                        $('.add-assetform-default-template').hide();
                        $('.asset-position-export-excel').hide();
                      }else{
                        $('.add-assetform-default-template').show();
                        $('.asset-position-export-excel').show();
                      }
                  }

                $(".ToggleColumb").html('<ul class="dropdown-values dropdown-menu values"></ul>');
                $.each(json.columns.name, function(i, val){
                    var is_last_item = (i == (json.columns.name.length - 1));
                    if(is_last_item){
                      tableHeaders += '<th scope="col" class="action">Action</th>';
                    }else{                    
                      tableHeaders += "<th>" + val + "</th>";
                    }
                    $(".dropdown-values").append('<li><label><input type="checkbox" checked="checked" class="toggle-vis" data-column="'+i+'" /><span>'+val.substr(0, 15)+'</span></label> </li>');
                });
                $("#tableDiv").empty();
                $("#tableDiv").append('<table id="AssetsTable" class="table table-line-braker mt-10 custom-responsive-md"><thead><tr>' + tableHeaders + '</tr></thead></table>');
                if(json.dat!=null) {

                    nTable = $('#AssetsTable').DataTable({
                        data: json.dat,
                        "stateSave": true,
                        "columnDefs": [{
                            "defaultContent": "-",
                            "targets": "_all"

                        }],
                        "language": {
                            "paginate": {
                                "first": "First",
                                "last": "Last",
                                "next": "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                                "previous": "<i class='fa fa-angle-left' aria-hidden='true'></i>"
                            },
                        },
                    });
                     //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
                      $('#myInputTextField').keyup(function(){
                            nTable.search($(this).val()).draw() ;
                      });

                }

                $('.toggle-vis').on('click', function (e) {
                  //e.preventDefault();
                  // Get the column API object
                  var column = nTable.column( $(this).attr('data-column') );
                  // Toggle the visibility
                  column.visible( ! column.visible() );
                } );
                
                var total = $(".ToggleColumb .toggle-vis").length;

                $(".ToggleColumb .toggle-vis").each(function( index ) {
                var column = nTable.column( $(this).attr('data-column') );
                    if(index > 3 && index < total-2){
                      $(this).attr('checked', false);
                      if(column.visible()){
                        $(this).trigger('click');
                      }
                      
                    }
                 });

                
                
                $('[data-toggle="tooltip"]').tooltip();
                $("#ajax-loading").hide();
                $('#AssetsTable_wrapper').removeClass("form-inline");

            },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
              $("#ajax-loading").hide();
          },
              "dataType": "json"
        });
  });
$( document ).on( "click", "#SubmitFeedBackRequest", function() {

        var checkValues = $('.checkFeedBack:checkbox:checked').map(function()
    {
        return $(this).val();
    }).get();



    $.ajax({
        "url": '/master-template/feedback/submitFeedBackRequest',
        beforeSend: function (xhr) {
            $("#ajax-loading").show();
        },
        type: 'POST',
        data: { ids: checkValues },
        "success": function(data) {
            $('input.checkFeedBack:checked').attr('checked',false);
            $("#SubmitFeedBackRequest").hide();
            location.reload();
            },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#ajax-loading").hide();
        },
    });
});


$('.check').change(function() {
   var price = $(this).parent().parent().parent().find('span.totalPrice').text();
    var total = $('.totalSum').html();
    var searchIDs = $("input:checkbox:checked").map(function(){
       if($(this).val()!='on')
        return $(this).val();
    }).get();


    if($(this).prop('checked')==true)
   {
        total = parseFloat(total) + parseFloat(price);
   }else {
       total = total - price;
   }
    $(".totalSum").html(total);
    $(".totalSum").val(total);

        $.ajax({
            url: '/Billing/stripAjax/'+total+'/'+searchIDs,
            type: 'POST',
            dataType: 'html',
            success:function(data){
                $('#stripeee').html(data);
            }
        });


    $.ajax({
        url: '/Billing/paypalCharges/'+total,
        dataType: 'html',
        success:function(data){
            $(".paypalCharges").html(data);
        }
    });


});

$('#checkall').change(function() {
    //jcf.customForms.destroyAll();
    $("input.check").prop('checked', $(this).prop('checked'));
    //jcf.customForms.replaceAll();

    if($(this).prop('checked')==true)
    {
        var totalSum =  $(".T_sum").val();
        $(".totalSum").html(totalSum);
        var paypalCharges = $("#paypalCharges").val();
        var stripeCharges = $("#stripeCharges").val();

        $(".paypalCharges").html('Paypal Charges('+paypalCharges+")");
        $("#stripeFees").html('Stripe charges('+stripeCharges+")");

    }else
    {

        $(".totalSum").html(0);
        $(".totalSum").val(0);
        $(".paypalCharges").html('');
        $("#stripeee").children('span').html('');

    }


});
$(document).ready(function(){
    $(document).on('click','#btn-more',function(){
        var url = window.location.href;
        url = url.match(/([^\/]*)\/*$/)[1];
        var page = 2;
        $("#btn-more").html("Loading....");
        $.ajax({
            url : "/ajax-request/getShowData/"+url,
            method : "POST",
            data : {page:page,_token:"{{csrf_token()}}"},
            dataType : "text",
            success : function (data)
            {
                if(data != '')
                {
                    $('#remove-row').remove();
                    $('#loadMore').append(data);
                }
                else
                {
                    $('#btn-more').html("No Data");
                }
            }
        });
    });

    $('#typeahead-App').typeahead( {
        source: function( query, process ) {
            $.post("/ajax-request/getAppsData", {
                    query: query
                },
                function( data ) {
                    // console.log(data);
                    // return false;
                    process(data);
                } );
        },
        afterSelect: function (item) {
            loadTemplateApps(item.id)
        }
    } );

    $('#typeahead-activity').typeahead( {
        source: function( query, process ) {
            $.get("/ajax-request/getActivityData", {
                    query: query
                },
                function( data ) {
                    console.log(data);
                    process(data);
                } );
        },
        afterSelect: function (item) {
            //  var Str =  item.split("....");
            // console.log(Str[2]);
            //getActivityView(1,Str[2])
        }
    } );
    var searchRequest = null;
    var url = window.location.href;
    var minlength = 3;
    $('#shows-search').keyup(function () {
        query = $(this).val();
        if (query.length >= minlength ) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: url,
                data: {
                    query: query
                },
                success: function (data) {
                    $("#show_search_view").html(data);
                }
            })
        }
        });


    $('#trainer-search').keyup(function () {
        query = $(this).val();
        if (query.length >= minlength ) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: url,
                data: {
                    query: query
                },
                success: function (data) {
                    $("#show_search_view").html(data);
                }
            })
        }
    });

});


$('#checkallFeedBacks').change(function() {
    if($(this).prop('checked')==true) {
        $("#SubmitFeedBackRequest").show();
    }else
    {
        $("#SubmitFeedBackRequest").hide();
    }
    $("input.checkFeedBack").prop('checked', $(this).prop('checked'));

});

$('input.checkFeedBack').change(function() {
    if($('input.checkFeedBack:checked').length >0)
    {
        $("#SubmitFeedBackRequest").show();
    }else
    {
        $("#SubmitFeedBackRequest").hide();
    }
});
