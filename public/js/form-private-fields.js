$(document).ready(function () {
	

	$(".participants-responses .remove-private-field").each(function(){
		var val = $(this).val();
		if (val == 1) {
			var $selector = $(this).closest(".fields-container-div");
			$selector.remove();
		};
	});
	
	
});