//Onload click. By default show the form.



$(function(){
  $( document ).on( "click", ".proposal-conclusion", function() {
    var id = $(this).data('id');
    var email = $(this).data('email');
    var projectovid = $(this).data('projectovid');
    var assetid = $(this).data('assetname');
    var formid = $(this).data('formname');
    var owner = $('.owner-name').val();
    var owneremail = $('.owner-email').val();


    var content = " Hi, <br>\
                <br>\
                Asset Name:"+assetid+" <br>\
                Form Name:"+formid+"<br>\
                Owner Name:"+owner+"<br>\
                Owner Email:"+owneremail+"<br>\
                ----------------------------------------------------------------------";

    tinymce.get("texteditor").setContent(content);
    $(".model-to").val(email);
    $(".responseid").val(id);

  });

  $( document ).on( "click", ".add-cc-bcc", function() {
    //var cloned = $(this).closest('.form-group').find('.container-extra:first').clone().prop('glyphicon-plus', 'glyphicon-minus');
    var cloned = '<div class="container-extra row col-md-12">\
      <div class="col-md-11">\
          <input name="model_cc_bcc[]" type="email" placeholder="CC" class="form-control">\
      </div>\
      <div class="col-md-1">\
        <button type="button" class="btn btn-xs remove-cc-bcc"><span class="fa fa-minus"></span></button>\
     </div>\
   </div>';
    $(".adding-extras").append(cloned);
  });

  $( document ).on( "click", ".remove-cc-bcc", function() {
    $(this).closest('.container-extra').remove();
  });

  $( document ).on( "change", ".uploadedfiles", function() {
    var files = $(this)[0].files;
    var lg = files.length;
    var fileSize = 0;
    var items =$(this)[0].files;
    if (lg > 0) {
        for (var i = 0; i < lg; i++) {
            fileSize = fileSize+items[i].size; // get file size
        }
        if(fileSize > 10097152) {
             alert('File size must not be more than 10 MB');
             $(this).val('');

        }
    }
    $(".upload_prev").html('');
    for (var i = 0; i < files.length; i++) {
        var html = "<span class='files-all-uploaded'>"+files[i].name+"</span> <br>"
        $(".upload_prev").append(html);
    }
});


//Removing files


});
