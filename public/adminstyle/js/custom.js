$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
$(".bnt-menu").click(function(){
  $(".main-menu").slideToggle();
});
$(function(){

var url = window.location.pathname, 
    urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
    // now grab every link from the navigation
    $('.main-menu li').each(function(){
    $(this).removeClass('active');
        // and test its normalized href against the url pathname regexp
        if(urlRegExp.test($(this).find('a').attr('href').replace(/\/$/,''))){
            $(this).addClass('active');
        }
    });

});


  $('input[name=module_launch_radio]:radio').on('change', function() {
    var checkedvalue = $('input[name=module_launch_radio]:checked').val();
    $("#module_launch_hidden").val(checkedvalue);
  });
  $('#storeandlist').on('click', function(event){
        $("#afterstore").val("storeandlist");
  });
  $('#storeonly').on('click', function(event){
        $("#afterstore").val("storeonly");
  });

//Add more email options for user
$(document).on("click",".btn-invite-more", function(e){ 
  //var cloneData = $(".invite-wrapper .invite-holder:first-child").clone();
  OptionLength =$(this).closest('div.modal-body').find('.invite-holder').length+1;
  var royalty = $(this).closest('.modal-body').find('.invite-wrapper:first-child .add-royalty').val();
  var cloneData = '<div class="invite-holder"><a class="btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>\
              <div class="row">\
                <div class="col-sm-12">\
                  <div class="form-group">\
                    <input name="template['+OptionLength+'][name]" required type="text" class="form-control" id="user-n" placeholder="Enter Name">\
                  </div>\
                </div>\
                <div class="col-sm-12">\
                  <div class="form-group">\
                    <input name="template['+OptionLength+'][email]" required type="email" data-error="Please enter a valid email address." class="form-control" id="user-em" placeholder="Enter Email">\
                  </div>\
                </div>\
                <div class="col-sm-12">\
                  <div class="form-group">\
                    <input name="template['+OptionLength+'][royalty]" type="text" class="form-control add-royalty" id="user-em" placeholder="Royalty %" value="'+royalty+'">\
                  </div>\
                </div>\
              </div></div>';
  $(".invite-wrapper").append(cloneData);
});
$(document).on("click",".btn-remove", function(e){ 
  $(this).parent(".invite-holder").remove();
});
$(document).on("click",".duplicate_template", function(e){
  var template_id = $(this).data('id');
    var template_name = $(this).data('title');
    $(".template_name").val(template_name);
   $(".invite-wrapper .invite-holder .addtemplateid").val(template_id);
});
$(document).on("click",".invite-users", function(e){
    $(this).removeData();
    var royalty = $(this).closest('td').find('.royalty').val();
    var template_id = $(this).closest('td').find('.heretemplateid').val();
    $(".invite-wrapper .invite-holder .add-royalty").val(royalty);
    $(".invite-wrapper .invite-holder .addtemplateid").val(template_id);
});

//empty all records in invite-users
$(function () {
 $('.modal').on('hidden.bs.modal', function(e)
    { 
        $(this).removeData();
    }) ;
});
///