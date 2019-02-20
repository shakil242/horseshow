//Start: On ready function like sortable
$(function() {
  $(".create-form-fields-dynamic").sortable({
    placeholder: "ui-state-highlight"
  });
});
//END : On ready function like sortable

//Jquery for uploading excel
$(function() {
  $("body").on('click',".btn-upload-excel", function(){
      $(this).closest(".file-excel-uploader").find(".excel-upload-file").click();
  });
  //file upload trigger
  $("body").on('change','.excel-upload-file', prepareUpload);

  function prepareUpload(event)
  {
    files = event.target.files;
    var base_url = window.location.protocol + "//" + window.location.host + "/";

    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    var buttons= $(this);
    $.ajax({
        url: base_url + "admin/file/upload/form",
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        beforeSend: function (xhr) {
              $('#ajax-loading').show();
              return xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success: function(data, textStatus, jqXHR)
        {
          if (data.status == 1) {
            var htmlOptions = "";
            $.each(data.dataExcel, function(index, itemData) {
              OptionLength= buttons.closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement= buttons.closest('div.sort-fieldtype').find('.field-placement').val();
              if (itemData.weight == null) {
                indexWeight = "";
              }else{
                indexWeight =itemData.weight;
              };
              if (itemData.options != null) {
                  htmlOptions = OptionDropdownHTML(fieldplacement,OptionLength,itemData.options,indexWeight);
                  buttons.closest(".options-choices.add-fields-options").find(".adder").append(htmlOptions);
                  buttons.closest(".options-choices.add-fields-options").find(".adder .Optionslidedown:last").show();
              }
            });
            
          }else{

          };
          $('#ajax-loading').hide();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           $('#ajax-loading').hide();
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });

  }
});

//Jquery Add more option on click function
$(".addfields-form").click(function(){
  len = $('.create-form-fields-dynamic div.sort-fieldtype').length+1;
  //Date and time
  var now = new Date();
  timestamp = now.getFullYear().toString(); // 2011
  timestamp += len+Math.floor((Math.random()*100000)+1); 
  timestamp += now.getUTCMilliseconds().toString(); // JS months are 0-based, so +1 and pad with 0's
  //
  var base_url = window.location.protocol + "//" + window.location.host + "/";
  //End date And time
  var appendFieldSelector = '<div class="add-form-field sort-fieldtype">\
  <input type="hidden" name="fields['+len+'][unique_id]" value="uid_'+timestamp+'">\
  <div class="row">\
   <div class="col-sm-9"><i class="glyphicon glyphicon-move" aria-hidden="true"></i></div>\
   <br>\
                <div class="col-sm-4">\
                  <div class="form-group">\
                    <label>Field Name *</label>\
                    <input name="fields['+len+'][form_name]" type="text" class="form-control" placeholder="Name of the field" Required/>\
                  </div>\
                </div>\
                <div class="col-sm-4">\
                  <div class="form-group">\
                    <label>Field Type *</label>\
                    <select data-select="" class="option-selector-drp" name="fields['+len+'][form_field_type]" Required>\
                    <option value="">Please select</option>\
                      <option value="100">Divider</option>\
                      <option value="1">Dropdown</option>\
                      <option value="2">Radio Button</option>\
                      <option value="3">Checkbox</option>\
                      <option value="4">Auto Populate</option>\
                      <option value="5">Text</option>\
                      <option value="15">Monetary</option>\
                      <option value="18">Numeric</option>\
                      <option value="7">Image</option>\
                      <option value="8">Video</option>\
                      <option value="9">Date</option>\
                      <option value="10">Time</option>\
                      <option value="11">Label</option>\
                      <option value="12">Hyperlink</option>\
                      <option value="13">Attachment</option>\
                      <option value="14">Email</option>\
                      <option value="16">Rating</option>\
                      <option value="17">Text Area</option>\
                      <option value="19">Address Maps</option>\
                      <option value="20">Signature</option>\
                      <option value="21">Breeds Auto Populate</option>\
                      <option value="22">Breeds Rider Auto Populate</option>\
                      <option value="23">Horse Age Auto Populate</option>\
                      <option value="24">Rider Age Auto Populate</option>\
                      <option value="25">Calculate Total</option>\
                      <option value="26">State Tax</option>\
                      <option value="27">Federal Tax</option>\
                    </select>\
                  </div>\
                </div>\
                <div class="col-sm-4 add-changeable-opt">\
                  <div class="form-group">\
                    <label><input name="fields['+len+'][form_field_required]" type="hidden" value="0"/><input name="fields['+len+'][form_field_required]" type="checkbox" value="1"/>Required</label>\
                  </div>\
                  <div class="form-group">\
                    <label><input name="fields['+len+'][form_field_private]" type="hidden" value="0"/><input name="fields['+len+'][form_field_private]" type="checkbox" value="1"/>Private</label>\
                  </div>\
                  <div class="form-group">\
                      <select class="custom-permission-admin" name="fields['+len+'][form_field_duplicate_times]" ><option value="">Set Duplicate Permission if applicable</option><option value="00">Unlimited</option><option value="1" >1 times</option><option value="2" >2 times</option><option value="3" >3 times</option><option value="4" >4 times</option><option value="5" >5 times</option><option value="6" >6 times</option><option value="7" >7 times</option><option value="8" >8 times</option><option value="9" >9 times</option><option value="10" >10 times</option><option value="11" >11 times</option><option value="12" >12 times</option><option value="13" >13 times</option><option value="14" >14 times</option><option value="15" >15 times</option><option value="16" >16 times</option><option value="17" >17 times</option><option value="18" >18 times</option><option value="19" >19 times</option><option value="20" >20 times</option></select>\
                  </div>\
                </div>\
              </div>\
              <br />\
              <input type="hidden" class="field-placement" value="'+len+'">\
              <div class="options-choices add-fields-options">\
                <div class="adder">\
                </div>\
                <input class="btn btn-sm btn-warning btn-add-options" value="Add Options" type="button">\
                <div class="file-excel-uploader">\
                  <input class="btn btn-sm btn-success btn-upload-excel" type="button" value="upload excel"> <small><a href="'+base_url+'uploads/excel/option-sample.xlsx">View</a> sample file for excel file formate. </small> \
                  <input class="excel-upload-file" name="excel_upload_file" type="file" style="display:none" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />\
                </div>\
              </div>\
              <div class="col-4">\
                   <input type="button" class="btn btn-sm btn-defualt deleteRow" value="Delete" />\
              </div>\
            </div>';
     $(".create-form-fields-dynamic").append(appendFieldSelector).find('.add-form-field').slideDown("slow");
     jcf.customForms.replaceAll();
});
//Start: delete Field
$(document).on("click",".deleteRow", function(e){ 
  $(this).closest('.add-form-field').slideUp("normal", function() { $(this).remove(); } );
});
//END: delete Field

//Start: delete Field
$(document).on("click",".delete-new-numeric-value", function(e){
  $(this).closest('.native-operators-select').slideUp("normal", function() { $(this).remove(); } );
});
//END: delete Field

//Start: delete Options
$(document).on("click",".delete-options-row", function(e){ 
  $(this).closest('.Optionslidedown').slideUp("normal", function() { $(this).remove(); } );
});
//END: delete Options

//Start: Add new option for form
$(document).on("click",".add-new-numeric-value", function(e){
  jcf.customForms.destroyAll();
  var indexer = $(this).closest('.add-form-field.sort-fieldtype').find('.field-placement').val();
  var len = $(this).closest('.operators-holder-div').find('.form-group.native-operators-select').length+1;
  var clondata = $(this).closest('.col-sm-12 .form-group')
                  .clone();
  var className = "fields["+indexer+"][form_field_options]["+len+"][additionfield]";
  var classOperator = "fields["+indexer+"][form_field_options]["+len+"][operator]";

  clondata.find('select.options-fields-drp').attr('name', className);
  clondata.find('select.set-operator').attr('name', classOperator);

  $(this).closest('.col-sm-12').append(clondata);

  jcf.customForms.replaceAll();
})

//Start: Radio Options default
$(document).on("click","input[type='radio']", function(e){ 
    var attribute = $(this).attr("data-attr");
    $("input[data-attr="+attribute+"]:radio").not(this).prop('checked', false);
});

// $(document).on("click",".jcf-label-active", function(e){ 
//     $(this).find(".rad-area").removeClass("rad-unchecked").addClass("rad-checked");
//     $(this).parents(".add-fields-options").find(".rad-area").removeClass("rad-checked");
// });

//End: Radio Options default


//Start: Button to add more options
$(document).on("click",".btn-add-options", function(e){
  var OptionLength = 0;
  var fieldplacement = 0;
  selVal = $(this).closest('.sort-fieldtype').find(".option-selector-drp").val();
      switch (selVal) {
        case "1":
        case "2":
        case "3":
        case "21":
        case "22":
        case "23":
        case "24":
            case "4":
            OptionLength= $(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
            fieldplacement= $(this).closest('div.sort-fieldtype').find('.field-placement').val();
            Options = OptionDropdownHTML(fieldplacement,OptionLength);
            break;
        case "5":  
      } 
  
      $(this).closest('.sort-fieldtype').find('.options-choices .adder').append(Options);
      $(this).closest('.sort-fieldtype').find(".Optionslidedown").slideDown("slow");
      jcf.customForms.replaceAll();
});
//End: Buttion to add more options

//Start: Duplicate.
$(document).on("change",".duplicateCheckbox", function(e){
  alert("duplicateCheckbox");
});


//Start: Option Selection
$(document).on("change",".option-selector-drp", function(e){ 
      var Options = null;
      var OptionLength = 0;
      var fieldplacement = 0;
      $(this).closest('.sort-fieldtype').find('.select-custom-permission-admin').show();

      selVal = $(this).val();
      cur = $(this).data('select');
      if(cur==selVal){
        return false;
      }
      $(this).data('select', selVal);

      $(this).closest('div.sort-fieldtype').find(".btn-add-options").show();
      $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').show();

      $(this).closest('.sort-fieldtype').find('.add-changeable-opt .delete-changeable-field').remove();
      

      valInt = parseInt($(this).val());
      switch (selVal) {
        case "":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = "";
              $(this).closest('.sort-fieldtype').find('.options-choices .adder').html(Options);
              $(this).closest('.sort-fieldtype').find('.add-fields-options').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.file-excel-uploader').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.options-choices').slideUp("slow");
              return false;
            break;
        case "100":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = "";
              $(this).closest('.sort-fieldtype').find('.options-choices .adder').html(Options);
              $(this).closest('.sort-fieldtype').find('.add-fields-options').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.options-choices').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.file-excel-uploader').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.select-custom-permission-admin').hide();
            return false;
            break;
        case "1":
        case "2":
        case "3":
        case "21":
        case "22":
        case "23":
        case "24":

        case "4":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = OptionDropdownHTML(fieldplacement,OptionLength); 
            break;
        case "5":
        case "9":
        case "10":
        case "14":
        case "15":
        case "16":
        case "17":
        case "18":
        case "19":
        case "26":
        case "27":
        case "20":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = "";
              $(this).closest('.sort-fieldtype').find('.options-choices .adder').html(Options);
              $(this).closest('.sort-fieldtype').find('.add-fields-options').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.file-excel-uploader').slideUp("slow");
              $(this).closest('.sort-fieldtype').find('.options-choices').slideUp("slow");
            return false;
            break;
        case "7":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();  
              var OPThtml = AddChangeableOption(fieldplacement);
              var fieldlabel = "Upload Image";
              var accept_parameters = "image/*";
              $(this).closest('.sort-fieldtype').find('.add-changeable-opt').append(OPThtml);
              Options = OptionUploadHTML(fieldplacement,fieldlabel,OptionLength,accept_parameters) 
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();

              break;
        case "8":
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();  
              var OPThtml = AddChangeableOption(fieldplacement);
              var fieldlabel = "Upload Video";
              var accept_parameters = "video/*";
              Options = OptionUploadHTML(fieldplacement,fieldlabel,OptionLength,accept_parameters) 
              $(this).closest('.sort-fieldtype').find('.add-changeable-opt').append(OPThtml);
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();
              
            break;
        case "13":
              OptionLength =1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();  
              var OPThtml = AddChangeableOption(fieldplacement);
              var fieldlabel = "Upload File";
              var accept_parameters = "/*";
              Options = OptionUploadHTML(fieldplacement,fieldlabel,OptionLength,accept_parameters) 
              $(this).closest('.sort-fieldtype').find('.add-changeable-opt').append(OPThtml);
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();
              

              break;
        case "11":
              var fieldname = "opt_label";
              var fieldlabel = "Enter Text";
              var placehold = "Label Text Here";
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = OptionHyperlinkHTML(fieldplacement,fieldname,fieldlabel,placehold,OptionLength);
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
            break;
        case "12":
              var fieldname = "opt_hyperlink";
              var fieldlabel = "Hyperlink";
              var placehold = "Example: www.google.com";
              OptionLength =$(this).closest('div.sort-fieldtype').find('.options-choices .Optionslidedown').length+1;
              fieldplacement =$(this).closest('div.sort-fieldtype').find('.field-placement').val();
              Options = OptionHyperlinkHTML(fieldplacement,fieldname,fieldlabel,placehold,OptionLength);
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
            break;
        case "25":
              Options = "<div class='adder'></div><p> Please save the form and then edit this field for options.</p>"
              $(this).closest('div.sort-fieldtype').find('.file-excel-uploader').hide();
              $(this).closest('div.sort-fieldtype').find(".btn-add-options").hide();
            break;
      }
      $(this).closest('.sort-fieldtype').find('.options-choices .adder').html(Options);
      $(this).closest('.sort-fieldtype').find('.add-fields-options').slideDown("slow");
      $(this).closest('.sort-fieldtype').find(".Optionslidedown").slideDown("slow");
      jcf.customForms.replaceAll();
});
//End: Option Selection

//JS reused Functions:

//Checkboxes, Radio buttons , Autofill.
function OptionDropdownHTML(fieldplacement,OptionLength,opt_name="",opt_weight=""){
  if (opt_name != "") {
      var htmlreturn = '<div class="Optionslidedown"> <div class="row">\
                  <div class="col-sm-9">\
                    <div class="row">\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <label>Option </label>\
                          <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_name]" class="form-control" placeholder="Enter Option" value="'+opt_name+'" required />\
                        </div>\
                      </div>\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <br />\
                          <label><input name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_default]" data-attr="field'+fieldplacement+'" type="radio" value="1" />Default </label>\
                        </div>\
                      </div>\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <label>Weightage PT(S)</label>\
                          <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_weightage]" class="form-control" value="'+opt_weight+'" placeholder="Add Weight" />\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
                  <div class="col-sm-3">\
                    <br />\
                    <input type="button" class="btn btn-sm btn-defualt delete-options-row" value="Delete" />\
                  </div>\
                </div></div>';
              }else{
                var htmlreturn = '<div class="Optionslidedown"> <div class="row">\
                  <div class="col-sm-9">\
                    <div class="row">\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <label>Option </label>\
                          <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_name]" class="form-control" placeholder="Enter Option" required />\
                        </div>\
                      </div>\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <br />\
                          <label><input name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_default]" data-attr="field'+fieldplacement+'" type="radio" value="1" />Default </label>\
                        </div>\
                      </div>\
                      <div class="col-sm-4">\
                        <div class="form-group">\
                          <label>Weightage PT(S)</label>\
                          <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][opt_weightage]" class="form-control" placeholder="Add Weight" />\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
                  <div class="col-sm-3">\
                    <br />\
                    <input type="button" class="btn btn-sm btn-defualt delete-options-row" value="Delete" />\
                  </div>\
                </div></div>';
              }
    return htmlreturn;
}

//HyperLink, label
function OptionHyperlinkHTML(fieldplacement,fieldname,OptionLable,placehold,OptionLength,req=1){
  var htmlreturn = '<div class="Optionslidedown"> \
                  <div class="row">\
                  <div class="col-sm-9">\
                    <div class="row">\
                      <div class="col-sm-12">\
                        <div class="form-group">'

                         if (req == 1) {
                          htmlreturn += '<div class="col-sm-3"><label>'+OptionLable+' :</label></div><div class="col-sm-8"> <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+']['+fieldname+']" class="form-control" placeholder="'+placehold+'" required /></div>'
                         }else{
                          htmlreturn +='<div class="col-sm-3"><label>'+OptionLable+' :</label></div><div class="col-sm-8"> <input type="text" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+']['+fieldname+']" class="form-control" placeholder="'+placehold+'"/></div>'
                         }
                         htmlreturn += '</div>\
                        </div>\
                      </div>\
                      </div>\
                      </div>\
                    </div>';
  return htmlreturn;
}
//End Hyperlink, label


//Upload
function OptionUploadHTML(fieldplacement,OptionLable,OptionLength,accept_parameters){
  var htmlreturn = '<div class="Optionslidedown"> \
                  <div class="row">\
                  <div class="col-sm-9">\
                    <div class="row">\
                      <div class="col-sm-12">\
                        <div class="form-group">\
                          <input type="hidden" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][old_upload_files]" value="">\
                          <div class="col-sm-3"><label>'+OptionLable+' :</label></div><div class="col-sm-8"><input type="file" class="validate-upload-file" name="fields['+fieldplacement+'][form_field_options]['+OptionLength+'][upload_files]"  accept="'+accept_parameters+'"> </div>\
                          </div>\
                        </div>\
                      </div>\
                      </div>\
                      </div>\
                    </div>';
  return htmlreturn;
}
// Add changeable checkboxto field 
function AddChangeableOption(fieldplacement){
  var htmlreturn = '<div class="form-group delete-changeable-field">\
                    <label><input name="fields['+fieldplacement+'][form_field_ischangeable]" type="hidden" value="0"/><input name="fields['+fieldplacement+'][form_field_ischangeable]" type="checkbox" value="1"/>Changeable</label>\
                  </div>';
    return htmlreturn;
}
//End Add changeable checkbox to fields


//JS reused Functions