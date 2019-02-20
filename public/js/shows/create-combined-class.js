/****
* Created on : 05-04-2018.
* @Author: Faran Ahmed Khan.
* Vteams
****/
$(function () {

	//Ready check

	$(document).ready(function(){
		if($('.is-req-check').is(":checked") == 1){
			$(".division-div").hide();
		}
	});
    //Numaric field ristricton

	$("body").on('change','.combined-class',function(){
		if ($(this).is(":checked") == 1){
			$( ".combining-classes-div .combined-input-class" ).removeClass('hidden');
			$( ".combining-classes-div" ).slideDown( "slow", function() {});
			$(".division-div").hide();
			$(".is-required-point-selection").hide();
			$(".is-split-class-div").hide();

		}else{
			$( ".combining-classes-div" ).slideUp( "slow", function() {
				$( ".combining-classes-div .combined-input-class" ).addClass('hidden');
			});
			$(".division-div").show();
			$(".is-required-point-selection").show();
			$(".is-split-class-div").show();


		}
	});

    //Numaric field ristricton

	$("body").on('change','.is-req-check',function(){
		if ($(this).is(":checked") == 1){
			$( ".combining-classes-div" ).slideUp( "slow", function() {
				$( ".combining-classes-div .combined-input-class" ).addClass('hidden');
				$( ".combined-class-holder" ).addClass('hidden');

			});
			$(".division-div").hide();
			$(".is-split-class-div").hide();

			//$(".is-required-point-selection").hide();
		}else{
			$( ".combined-class-holder" ).removeClass('hidden');
			$( ".combining-classes-div" ).slideDown( "slow", function() {});
			$(".division-div").show();
			$(".is-split-class-div").show();

			//$(".is-required-point-selection").show();

		}
	});



	// $("body").on('change','.is-split-class',function(){
	// 	if ($(this).is(":checked") == 1){
	// 		$( ".combining-classes-div" ).slideUp( "slow", function() {
	// 			$( ".combining-classes-div .combined-input-class" ).addClass('hidden');
	// 			$( ".combined-class-holder" ).addClass('hidden');
	// 		});
	// 		$(".division-div").hide();
	// 		$(".is-required-point-selection").hide();

	// 	}else{
	// 		$( ".combined-class-holder" ).removeClass('hidden');
	// 		$( ".combining-classes-div" ).slideDown( "slow", function() {});
	// 		$(".division-div").show();
	// 		$(".is-required-point-selection").show();

	// 	}
	// });

});