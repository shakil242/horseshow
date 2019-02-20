$(document).ready(function () {
	
	//Remove select
	$(".select-form-control").remove();
	//Disable rating
	$(".rating").rating("refresh", {disabled:true, showClear:false});
	//Disable signature
	//$('.sigWrapper').signaturePad({displayOnly:true});
	setTimeout( function(){ 
   		$(".file-drop-zone-title").text(' ');
		$(".file-caption-main").hide();
  	}  , 1100 );
	//$(".file-caption-main").hide();
	

	$(".participants-responses .uploadimage").each(function(){
		var $selector = $(this).closest(".input-container");
		var type = $selector.find('.required-fields-hidden .form-field-type').val();
		if (type == 8/*Type:Select*/) { 
			$(this).find('.file-input').hide();

		}
	});
	$(".participants-responses :input.form-control").each(function(){
	

		var $selector = $(this).closest(".input-container");
		var vals = $(this).val();
		var type = $selector.find('.required-fields-hidden .form-field-type').val();
		//alert(type);
		if (type == 1/*Type:Select*/) {
			vals = vals.split("|||", 1);
			$selector.prepend('<p style="padding-top:5px">'+vals+'</p>');
			$(this).hide();
		}else if(type == 4|| type == 21 || type == 22 || type == 23 || type == 24 /*Select 2, Breeds. Multi select*/){
			//do nothing
		}
		else if(type == 12/* Link */){
			$(this).closest(".Linker_div").find(".btn-small").hide();
			//$selector.prepend('<p style="padding-top:5px">'+vals+'</p>');
			$(this).closest('.check-valid').removeClass('form-mr-15');
			$(this).hide();
		}
		else if(type == 15/* Money */){
			$(this).closest(".input-group").find(".input-group-addon").hide();
			$selector.prepend('<p style="padding-top:5px">'+vals+'</p>');
			$(this).hide();
		}
		else if(type == 19/* MAP */){
			//No value
			if($(this).val() == "" || $(this).val() == null){
				$(this).closest(".map-location").find("#map_wrapper").hide()
				$(this).closest('.map-location').find('.input-group').hide();
			}else{
				$(this).hide();
			}
			$selector.prepend('<p style="padding-top:5px">'+vals+'</p>');
			$(this).closest('.map-location').find('.input-group').hide();

		}
		else{
			$selector.prepend('<p style="padding-top:5px">'+vals+'</p>');
			$(this).hide();
		};
	});
	
	$("input.rating").hide();

	$(".participants-responses .sigWrapper .outputCanvi").each(function(){ 
		if($(this).val() == "" || $(this).val() == null){
			$(this).closest(".input-group").find(".col-sm-6").hide()
		}
	});

	$(".participants-responses :input").attr("disabled", true);
	$(".participants-responses :input:file").hide();
	
	$(".kv-file-remove-attachment").remove();
	$(".kv-file-remove-video").remove();
	$(".kv-file-remove").remove();
	
});