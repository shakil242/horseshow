/****
* Created on : 05-03-2017.
* @Author: Faran Ahmed Khan.
* Vteams
****/
$(function () {
    //Numaric field ristricton
    $(".NumaricRistriction").keypress(function (event) { 
        if(event.which == 8 || event.which == 0){
            return true;
        }
        if(event.which < 46 || event.which > 59) {
            return false;
            //event.preventDefault();
        } // prevent if not number/dot

        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            return false;
            //event.preventDefault();
        } // prevent if already dot
    });
    //rating stars
	$('.ratings').rating();
	//autopopulate
	$(".autopopulate-basic-multiple").select2();
	//Date
	$('.datetimepickerDate').datetimepicker({
		format: 'MM/DD/YYYY'
	});
	//Time
	$('.datetimepickerTime').datetimepicker({
		format: 'LT' 
		//format: 'HH:mm'
	});

    $('body').on('keydown keyup','.percentage-check', function(e){
        var max = $(this).attr('max');
        if ($(this).val() > 100
            && e.keyCode !== 46 // keycode for delete
            && e.keyCode !== 8 // keycode for backspace
        ) {
            e.preventDefault();
            $(this).val(100);
        }
    });

    $("body").on('keyup','.add-percent-to-total',function(event) {
        var getAllTax=0;
        $('.add-percent-to-total').each(function () {
            if ($(this).val() !== "") {
                getAllTax= parseFloat($(this).val())+parseFloat(getAllTax);
            }
        });
        $('.total-calculation-show').each(function () {
            var totalAmount = $(this).closest('.input-container').find(".orignal-total-field").val();
            var percent = getAllTax;

            var totalPrize = (totalAmount * percent / 100).toFixed(3);
            var totl = (parseFloat(totalPrize)+parseFloat(totalAmount));
            $(this).val(totl);
        });
    });



    //By passing the image/video if already uploaded after drafting
    // $('.uploaded-image-req').each(function(){
    //         if($(this).prop('required')){
    //             var haveFile = $(this).closest(".file-input").find(".file-preview .file-drop-zone .file-preview-frame").length;
    //             alert(haveFile);
    //             if(haveFile != 0){
    //                 //alert("Please upload the image / video that are required!");
    //                 $(this).removeAttr('required');
    //             }
                
    //         } 
    //      });
//By passing the image/video if already uploaded after drafting
    $("body").on('click','.clicked-submit',function(event) { 
            //image
            $('.uploaded-image-req').each(function(){
                if($(this).hasClass('required')){
                    var haveFile = $(this).closest(".file-input").find(".file-preview .file-drop-zone .file-preview-frame").length;
                    if(haveFile == 0){
                        alert("Please upload the image / video that are required!");
                        event.preventDefault();
                        return false;
                    } 
                } 
             });
            //validator
            $('.form-group').removeClass("has-error");
            var $validationSelector = $('form.targetvalue :invalid').filter(":first");
            var $closest = $validationSelector.closest('.panel.panel-default').find(".accordion-toggle");
            var $collapable = $validationSelector.closest('.panel.panel-default').find(".panel-collapse.collapse");
            var $formgroup = $validationSelector.closest('.form-group');
            if($collapable.is(":hidden")){
                console.log($collapable.is(":hidden"));
                $closest.trigger('click'); 
                $('html, body').animate({
                        scrollTop: $validationSelector.offset().top
                    }, 2000);               
            } 
            $formgroup.addClass("has-error");

            //Radio button validation
            // $(".check-valid.Required").each(function(){
            //     if(!$(this).find("input[type=radio]").is(':checked')){
            //         alert("You are missing some required Fields");
            //         event.preventDefault();
            //         return false;
            //     } 
            // });
            
    });

    //calculating total for Calculate fields
    $('.total-calculation-show').each(function(){
        var selector = "";
        var eqSelector;
        var $self = $(this);
        $self.closest('.input-container').find('.total-calculation-option.fields').each(function(index,vals) {
            if(index != 0){
                selector+= ',';
            }
            selector+= '[name="fields['+$(this).val()+'][answer]"]';
        });
        $("body").on('keyup',selector,function(event) {
            var total=parseFloat(0);
            $self.closest('.input-container').find('.total-calculation-option').each(function() {
                eqSelector = '[name="fields['+$(this).val()+'][answer]"]';
                var currentValue = parseFloat($(eqSelector).val());
                currentValue = getNum(currentValue);
                if($(this).data('operator') == 1){
                    total = parseFloat(total+currentValue);
                }
                if($(this).data('operator') == 2){
                    total = parseFloat(total-currentValue);
                }
            });
            var totalWithTax =total;
            $self.closest('.input-container').find(".orignal-total-field").val(total);
            var getAllTax=0;
            $('.add-percent-to-total').each(function () {
                if ($(this).val() !== "") {
                    getAllTax= parseFloat($(this).val())+parseFloat(getAllTax);
                }
            });
            var totalPrize = (total * getAllTax / 100).toFixed(3);
            totalWithTax = parseFloat(totalPrize)+parseFloat(total);
            $self.val(totalWithTax);
        });
    });

	//Image
	$(".targetvalue").submit(function(event) {
		 var valid = true;
		$('.file-drop-zone .file-preview-frame.kv-preview-thumb').each(function(){
			if($(this).hasClass("file-preview-error")){ 
				$('html,body').animate({ scrollTop: $(this).offset().top}, 'slow');
				$(this).closest('.file-drop-zone').find('.file-error-message').show().append('<ul><li> Please fix the issue to proceed saving this form.</li></ul>')
				event.preventDefault();
				valid = false;
				return false;
			}
		});
		if(valid){ 
			$('.file-drop-zone .file-preview-frame.kv-preview-thumb .kv-preview-data').each(function(){
				var srcs = $(this).attr('src');
				if (!srcs) {
					var srcs = $(this).attr('data');
				};
				var nameid = $(this).closest('.uploadimage').find('.fileuploaderid').val();
				$(this).closest('.uploadimage').find('.fileholders').append('<textarea style="display:none" name="fields['+nameid+'][images_blob][]">'+srcs+'</textarea>')
			});
		}
		return true;
	});

    //Attachment
    $('.fileupload input:file').each(function(){
        if($(this).prop('required')){
            var total = $(this).closest('.input-container')
              .find('.fileholders .file-preview-frame').length;
          if(total > 0){
            $(this).attr('required',false);
          }
        }
    });

    // in order to select auto popolate for edit asset forms

   var autoPopulate = $(".autopopulate-basic-multiple");

    autoPopulate.each(function (e) {
    if($(this).val()!=null)
    {
        $(this).parent().find('.selectAllPop').attr('checked',true);
    }
    });


});

//File delete from s3 storage on delete click

$(document).on("click",".kv-file-remove-attachment", function(e){

    if (confirm("Are you sure to delete the File?")) {


        var imagePath = $(this).closest('.file-preview-frame').find('.col-sm-4 a').attr("href");
        var token = $("input[name=_token]").val();
        $.ajax(
            {
                url: "/settings/s3/delete/File",
                type: 'DELETE',
                method: "POST",
                data: {
                    "Path": imagePath,
                    "_token": token,
                },
                beforeSend: function () {
                    $('#ajax-loading').show();
                },
                context: this,
                success: function (data) {
                    if (data.status) {
                        $(this).closest('.file-preview-frame').fadeOut(300, function () {
                            $(this).remove();
                        });
                        $('input[value="' + data.path + '"]').remove();
                        $('#ajax-loading').hide();
                    }
                    ;
                },
                error: function (_response) {
                    $('#ajax-loading').hide();
                }
            });
    }else{

        return false;
    }
    //e.preventDefault();
});
//Remove Video from storage

$(document).on("click",".kv-file-remove-video", function(e){ 
    var imagePath = $(this).closest('.file-preview-frame').find('video source').attr("src");
    var token = $(".targetvalue").find("input[name=_token]").val();   
    $.ajax(
            {
                url: "/settings/s3/delete/File",
                type: 'DELETE',
                method:"POST",
                data: {
                    "Path": imagePath,
                    "_token": token,
                },
                beforeSend: function()
                {
                    $('#ajax-loading').show();
                },
                context: this,
                success: function (data)
                {
                    if (data.status){
                        $(this).closest('.file-preview-frame').fadeOut(300, function() { $(this).remove(); });
                        $('input[value="'+data.path+'"]').remove();
                        $('#ajax-loading').hide();
                    };
                },
                error: function( _response ){
                    $('#ajax-loading').hide();
                }
            });
});
//Image Delete form server on delete click
$(document).on("click",".kv-file-remove", function(e){ 
    var imagePath = $(this).closest('.file-preview-frame').find('.kv-file-content img').attr("src");
    var token = $(".targetvalue").find("input[name=_token]").val();   
    $.ajax(
            {
                url: "/settings/s3/delete/File",
                type: 'DELETE',
                method:"POST",
                data: {
                    "Path": imagePath,
                    "_token": token,
                },
                beforeSend: function()
                {
                    $('#ajax-loading').show();
                },
                context: this,
                success: function (data)
                {
                    if (data.status){
                        $(this).closest('.file-preview-frame').fadeOut(300, function() { $(this).remove(); });
                        $('input[value="'+data.path+'"]').remove();
                        $('#ajax-loading').hide();
                    };
                },
                error: function( _response ){
                    $('#ajax-loading').hide();
                }
            });
    //e.preventDefault();
});

$(document).on("click","input[type='radio']", function(e){ 
    var attribute = $(this).attr("data-attr");
    $("input[data-attr="+attribute+"]:radio").not(this).prop('checked', false);
});

$(document).on("click",".chekRestriction", function(e) {

    e.preventDefault();
    var url = '/shows/checkTrainerRestrictions';

    $.ajax({
        url: url,
        type: "POST",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        data: $(".targetvalue").serialize(),
        success: function (data) {

            var valid = true;

            var text = '<div class="row" style="margin-top: 13px;">';
            if (data['unFilledData'].length > 0) {

                for (i = 0; i < data['unFilledData'].length; i++) {
                    text += '<div style="border-top: solid 1px #cdcdcd;line-height: 30px;" class="col-sm-12">' + data['unFilledData'][i] + "</div>";
                }

                text += '</div>';
                alertBox("In order to register as Trainer in the show you need to add The details for show type  <h4 style='float: right; line-height: 21px; color: #651e1c'>" + data['showType'] + "</h4>  <span style='font-size:11px;color: #651e1c'>(Please Enter N/A if you don't have detail for below fields)</span>" + text);
                return false;

            } else {

                 $(".targetvalue").submit();
            }

        }
    });

});



//JS for Images init
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
//JS for video init
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
$(".file-uploader-browser").fileinput({
        uploadUrl: '#', // you must set a valid URL here else you will get an error
        allowedFileExtensions: ["pdf","txt","ini","md","doc","docx","xls","xlsx","ppt","pptx","zip","rar","tar","gzip","gz","7z"],
        overwriteInitial: true,
        //maxFileCount: 10,
        minFileCount: 1,
        maxFileSize: 5000,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
});

function getNum(val) {
    if (isNaN(val)) {
        return 0;
    }
    return val;
}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	var name = $(input).attr('name');
        	$('.fileholders').append('  <input name="'+name+'" type="file" multiple class="thefiles">')
            alert(e.target.result);
            $('.outputresult').append('<div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">\
            	<img src="'+e.target.result+'" alt="your image" width="175" height="110" />\
            	<div>');
        }

        reader.readAsDataURL(input.files[0]);

    }
}


function selectAllPop(obj) {

    if(obj.prop('checked')==true) {
        var assignedRoleId = new Array();
        obj.parent().parent().find('select option').each(function () {
            assignedRoleId.push(this.value);
        });
        obj.parent().parent().find('select').select2().val(assignedRoleId).trigger("change");
    }else{
        obj.parent().parent().find('select').select2().val([]).trigger("change");
    }
}