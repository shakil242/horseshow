$(function () {
    $('body').on('click',"div.active div.bhoechie-tab-menu>div.list-group>a", function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.active div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.active div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });

    $('div.active div.bhoechie-tab-menu>div.list-group>a:first').click();

    var hash = location.hash.substr(1);
    if(hash)
    $("."+hash+" a").trigger('click');


});

function loadTemplateApps(template_id) {

    $(".secondaryMenu").find('li').removeClass('active');
    $(".secondaryMenu").find('li.myApp').addClass('active');

    $.ajax({
        url: "/ajax-request/loadTemplateApps/" + template_id,
        method: "get",
        "success": function (data) {
            $("#innerViewCon").html('');
            $('#collapseExample').collapse('hide');
            $("#innerViewCon").html(data);
        }
    });


}

function getActivityView(id,pageNo,asset_id) {

    $(".secondaryMenu").find('li').removeClass('active');
    $(".secondaryMenu").find('li.activity').addClass('active');

    if(asset_id=='undefined' ||asset_id==null)
        asset_id = null;

    $.ajax({
        url: "/ajax-request/loadActivityView/"+id+"/"+pageNo+"/"+asset_id,
        method: "get",
        "success": function (data) {
            $('#collapseExample').collapse('hide');
            $("#innerViewCon").html(data);
        }
    });


}

function loadSubParticipantView(id) {

    $(".secondaryMenu").find('li').removeClass('active');
    $(".secondaryMenu").find('li.subParticipants').addClass('active');

    $.ajax({
        url: "/ajax-request/loadSubParticipantView/"+id,
        method: "get",
        "success": function (data) {
            $('#collapseExample').collapse('hide');
            $("#innerViewCon").html(data);
        }
    });


}
function loadEmployeeView(template_id) {

    $(".secondaryMenu").find('li').removeClass('active');
    $(".secondaryMenu").find('li.employee').addClass('active');

    if(template_id=='undefined' ||template_id==null)
        template_id = null;
    $.ajax({
        url: "/ajax-request/loadEmployeeView/" + template_id,
        method: "get",
        "success": function (data) {
            $('#collapseExample').collapse('hide');
            $("#innerViewCon").html(data);
        }
    });


}


$(document).ready(function(){
    $(document).on('click','#btn-more',function(){
        var id = $(this).data('id');
        $("#btn-more").html("Loading....");
        $.ajax({
            url : '/ajax-request/loadActivityDataAjax',
            type : "POST",
            data : {id:id},
            dataType : "text",
            success : function (data)
            {
                if(data != '')
                {
                    $('#remove-row').remove();
                    $('#load-data').append(data);
                }
                else
                {
                    $('#btn-more').html("No Data");
                }
            }
        });
    });
});

 $(document).ready(function(){
    // $(document).on('click','#btn-more',function(){
    //     var url = $(this).data('url');
    //     var page = $(this).data('page');
    //     console.log(url);
    //
    //     if(url=='')
    //     {
    //         $("#btn-more").html("End of page").attr('disabled',true);
    //         return false;
    //     }
    //     $("#btn-more").html("Loading....");
    //     $.ajax({
    //         url : url,
    //         method : "POST",
    //         data : {page:page,_token:"{{csrf_token()}}"},
    //         dataType : "text",
    //         success : function (data)
    //         {
    //             if(data != '')
    //             {
    //                 $('#remove-row').remove();
    //                 $('#load-data').append(data);
    //             }
    //             else
    //             {
    //                 $('#btn-more').html("No Data");
    //             }
    //         }
    //     });
    // });

    $('#typeahead-App').typeahead( {
        source: function( query, process ) {
            $.post("/ajax-request/getAppsData", {
                    query: query
                },
                function( data ) {
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


});

$('#typeahead-activity').typeahead( {
    source: function( query, process ) {
        $.post("/ajax-request/getActivityData", {
                query: query
            },
            function( data ) {

                process(data);
            } );
    },
    afterSelect: function (item) {
        getActivityView(item.id)
    }
} );