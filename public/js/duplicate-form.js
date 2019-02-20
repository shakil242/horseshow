/*
*@autor: Faran Ahmed Khan (vteams)
*@dated: 28-08-2017
*
* This is the duplicate function for the form. It will check if allowed by admin
* and the use click on duplicate button, the fields allowed to duplicate will 
* be duplicated.
*/
$(function () {
   //Duplicate function on click
   $(document).on("click",".btn-duplicate-form", function(e){

    //Add title after cloning and changing its name
    var titleform = $(".form-title").first().text();
    var title_add= $(".form-title-holder").eq(0).clone();
    var Length = $(".duplicate-fields-add").find(".times-duplicated").length+2;
    title_add.find('.form-title').text(titleform+" ("+Length+")");
    title_add.find('.form-title').append('<input name="fields[duplication_'+Length+'][DF_title]" type="hidden" value="'+titleform+" ("+Length+")"+'" /> <input name="fields[duplication_'+Length+'][duplication_batch]" type="hidden" value="'+Length+'" />');
    //$('.RepairId', $clone).text(repairIdValue);

    $(".duplicate-fields-add").append(title_add);
    var allowed = false;
    //jcf.customForms.destroyAll();
  
    var oldDivider = "";
    $("form .form-fields-holder div.form-group").each(function(index,element) { 
        var allowedTime = $(this).find(".duplicate-permission-time").val();
        var pannelDivider = $(this).closest(".panel").find(".panel-heading").attr("data-class");
        
        if(allowedTime == "00"){
          allowedTime = "unlimited";
        }
        //Check if is allowed or not
        if (allowedTime != null && allowedTime != ""){
          //Check, Not allowed to duplicate more then admin allowed.
          var currentLen = $(".duplicate-fields-add").find(".times-duplicated").length+1;
          
          if ( allowedTime == "unlimited" || currentLen <= parseInt(allowedTime)){
 

              //Field ID and Curren date and time.
              var fieldID = $(this).find(".duplicate-permission-fieldId").val();
              var now = new Date();
              timestamp = now.getFullYear().toString(); // 2011
              timestamp += currentLen+Math.floor((Math.random()*100000)+1); 
              timestamp += now.getUTCMilliseconds().toString(); // JS months are 0-based, so +1 and pad with 0's
              var clone= $(this).eq(0).clone();
              var fieldType = $(this).find(".form-field-type").val();
              
              //Adding duplicate Divider pannels
              if (pannelDivider != null) {
                if (oldDivider != pannelDivider) {
                    var pannelHeading = $(this).closest(".panel").find(".panel-heading").text();
                    pannelHeading = $.trim(pannelHeading);

                    // if (oldDivider == "") {
                    //   var dividerHTML = "";
                    //   $(".duplicate-fields-add").append(dividerHTML);
                    // };

                    var dividerHTML ='<div class="panel slide-holder">\
                    <h5 class="card-header">\
                      <a class="d-block title collapsed panel-heading" data-toggle="collapse" href="#'+pannelDivider+'_'+Length+'" data-class="'+pannelDivider+'_'+Length+'" aria-expanded="true" aria-controls="collapse-example">\
                      '+pannelHeading+'\
                      </a>\
                      </h5>\
                    <div id="'+pannelDivider+'_'+Length+'" class="form-fields-in-panel panel-collapse collapse" aria-labelledby="heading-example">\
                        <div class="card-body">\
                          <input type="hidden" name="fields[uid_'+timestamp+'_1][form_field_type]" value="100">\
                          <input type="hidden" name="fields[uid_'+timestamp+'_1][DF_Divider_Name]" value="'+pannelHeading+'">\
                          <input type="hidden" name="fields[uid_'+timestamp+'_1][unique_id]" value="'+pannelDivider+'_'+Length+'">\
                          <input name="fields[uid_'+timestamp+'_1][duplication_batch]" type="hidden" value="'+Length+'" />\
                        </div>\
                    </div>';
                       $(".duplicate-fields-add").append(dividerHTML);
                    oldDivider = pannelDivider;
                };
              };

              if(fieldType != 20 /*Sign*/ ){
                //input
                clone.find('input').each(function() {
                    if (this.name=='fields['+fieldID+'][form_field_id]') {
                      this.value= this.value.replace(fieldID, 'uid_'+timestamp);
                    };
                    this.name= this.name.replace('fields['+fieldID+']', 'fields[uid_'+timestamp+']');
                    if ($(this).attr("class") == "duplicate-permission-time" || $(this).attr("class") == "duplicate-permission-fieldId") {
                      this.remove();
                    };
                });
                //select
                clone.find('select').each(function() {
                    this.name= this.name.replace('fields['+fieldID+']', 'fields[uid_'+timestamp+']');
                    if ($(this).attr("class") == "duplicate-permission-time" || $(this).attr("class") == "duplicate-permission-fieldId") {
                      this.remove();
                    };
                });
                //Textarea
                clone.find('textarea').each(function() {
                    this.name= this.name.replace('fields['+fieldID+']', 'fields[uid_'+timestamp+']');
                    if ($(this).attr("class") == "duplicate-permission-time" || $(this).attr("class") == "duplicate-permission-fieldId") {
                      this.remove();
                    };
                });
                //add duplication field batch
                clone.find('.required-fields-hidden').append("<input name='fields[uid_"+timestamp+"][duplication_batch]' type='hidden' value='"+Length+"' />");
                clone.find('.required-fields-hidden').append("<input name='fields[uid_"+timestamp+"][duplicated_from]' type='hidden' value='"+fieldID+"' />");

                //Check if the fields have dividers.
                if (pannelDivider != null) {
                  //Add cloned object to HTML div
                  //var varible = ".duplicate-fields-add .panel [data-class='"+pannelDivider+'_'+Length+"']";
                  var varible = ".duplicate-fields-add .panel #"+pannelDivider+'_'+Length+"";
                   $(varible).append(clone).slideDown(500);
                }else{
                  $(".duplicate-fields-add").append(clone).slideDown(500);
                };

                //JS init
                if (fieldType == 19) {
                  clone.find('.location').attr("id","search_input_"+timestamp); 
                  clone.find('.map-canvas').attr("id","map_canvas_"+timestamp); 

                  $('.map-location').attr('initialize', "true");
                  clone.find('.map-location').attr('initialize', "false");
                  initialize();
                };
                if(fieldType == 9/*OPTION_DATE_PICKER*/){
                  //Date
                  $('.datetimepickerDate').datetimepicker({
                    format: 'YYYY/MM/DD'
                  });
                }
                //Time
                if(fieldType == 10/*OPTION_TIME_PICKER*/){
                  $('.datetimepickerTime').datetimepicker({
                    format: 'LT' 
                    //format: 'HH:mm'
                  });
                }
                //rating stars
                if(fieldType == 4 || fieldType == 21 || fieldType == 22 || fieldType ==23 || fieldType ==24 /*OPTION_AUTO_POPULATE*/){
                  $(".select2-container:last").remove();
                  $(".autopopulate-basic-multiple").select2();
                  $(".autopopulate-basic-multiple").select2();
                }
                //rating stars
                if(fieldType == 16/*OPTION_RATINGS*/){
                  //$(".duplicate-fields-add").find(".rating-container:last-child").html('<input name="fields[uid_'+timestamp+'][answer]" id="input-7-xs" class="rating rating-loading" value="" data-max="5" data-step="0.1" data-size="xss">');
                  clone.find('.rating-container').each(function() {
                    $(this).html('<input name="fields[uid_'+timestamp+'][answer]" id="input-7-xs" class="rating rating-loading" style="display:none" value="" data-max="5" data-step="0.1" data-size="xss">');
                  });
                  $('input[name="fields[uid_'+timestamp+'][answer]"]').rating();
                }
                //Image 
                if(fieldType == 7/*OPTION_IMAGE*/){
                  //$(".duplicate-fields-add").find(".rating-container:last-child").html('<input name="fields[uid_'+timestamp+'][answer]" id="input-7-xs" class="rating rating-loading" value="" data-max="5" data-step="0.1" data-size="xss">');
                  clone.find('.uploadimage').each(function() {
                    $(this).html('<input type="file" multiple class="file image-uploader-browser" data-overwrite-initial="false">\
                        <input type="hidden" class="fileuploaderid" value="uid_'+timestamp+'">\
                        <div class="fileholders">\
                        </div>');
                  });
                  //Image upload init again.
                  $(".image-uploader-browser").fileinput({
                    uploadUrl: '#', // you must set a valid URL here else you will get an error
                    allowedFileExtensions: ['jpg','svg', 'png', 'gif'],
                    overwriteInitial: false,
                    maxFileCount: 10,
                    minFileCount: 1,
                    maxFileSize: 6500,
                    //allowedFileTypes: ['image', 'video', 'flash'],
                    slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_');
                    }
                  });
                }
                //Image 
                if(fieldType == 8/*OPTION_VIDEO*/){
                  //$(".duplicate-fields-add").find(".rating-container:last-child").html('<input name="fields[uid_'+timestamp+'][answer]" id="input-7-xs" class="rating rating-loading" value="" data-max="5" data-step="0.1" data-size="xss">');
                  clone.find('.uploadimage').each(function() {
                    $(this).html('<input type="file" name="fields[uid_'+timestamp+'][upload_files]" class="file video-uploader-browser" data-overwrite-initial="false">');
                  });
                  //Video upload init again.
                  $(".video-uploader-browser").fileinput({
                          uploadUrl: '#', // you must set a valid URL here else you will get an error
                          allowedFileExtensions: ['avi','mpg','mkv','mov','mp4','3gp','webm','wmv'],
                          overwriteInitial: true,
                          //maxFileCount: 1,
                          minFileCount: 1,
                          maxFileSize: 31000,
                          //allowedFileTypes: ['video', 'flash'],
                          slugCallback: function (filename) {
                              return filename.replace('(', '_').replace(']', '_');
                          }
                  });
                }

                //Count and Allowed True if added some fields.
                allowed = true;
              }
          }

        }

    });

    if(!allowed){
      $(".form-title-holder:last").remove();
      alert('Not Allowed ');
    }else{

      //Count for number of times loop has worked.
      $(".duplicate-fields-add").append("<div class='times-duplicated'></div>");
    }

    //JCF initilize again
      $(".duplicate-fields-add .panel .panel-collapse.collapse").each(function() {
        $(this).slideUp();
      });
      //jcf.customForms.replaceAll();
    
  });

$("body").on('click',".duplicate-fields-add .panel-heading",function(){
  $(this).closest(".panel").find(".panel-collapse").slideToggle();
});


});