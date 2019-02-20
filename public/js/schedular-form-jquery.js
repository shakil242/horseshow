$(function () {
	//Date
	$('.datetimepickerDate').datetimepicker({
		format: 'YYYY/MM/DD'
	});
	//Time
	$('.datetimepickerTime').datetimepicker({
		format: 'LT',
		//format: 'HH:mm'
	});
	//Range 
	$('.daterange').daterangepicker({
        timePicker: true,
        controlType: 'select',
        timePickerSeconds:false,
        timePickerIncrement: 1,
        defaultDate: "",
        autoUpdateInput: false,
        icons: {
        time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
		locale: {
            format: 'MM/DD/YYYY h:mm:ss A'
        }
	}).on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY h:mm:ss A') + ' - ' + picker.endDate.format('MM/DD/YYYY h:mm:ss A'));
	});


    $('.daterange2222').daterangepicker({
        timePicker: true,
        controlType: 'select',
        timePickerSeconds:false,
        timePickerIncrement: 1,
        defaultDate: "",
        autoUpdateInput: false,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        locale: {
            format: 'MM/DD/YYYY h:mm:ss A'
        }
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY h:mm:ss A') + ' - ' + picker.endDate.format('MM/DD/YYYY h:mm:ss A'));
    });

  //Remove Row
  $(document).on('click','.call-to-add-class,.edit-show-popup',function () {
    if($(this).data('show-type') =="Dressage" || $(this).data('show-type') =="Eventing" ){
      $(".show-qualifying-at-initiate").show();
    }else{
      $(".show-qualifying-at-initiate").hide();
    }
  });

  
	$('.reminder-checkbox').change(function () {

        var c = $(this).prop('checked');
        if(c==false)
        {
            $(this).prev().removeClass('chk-checked');
        }else
        {
            $(this).prev().addClass('chk-checked');
        }
        var coup = $(this).closest('.create-form');
		coup.find('.makeRestriction').toggle(this.checked);
	}).change(); //ensure visible state matches initially


	//Remove Row
  	$(document).on('click','.deleterowschedual',function () {
        $(this).closest('.row').remove();
        $(".error").removeClass("error");

    });


  //Hide or show text box for 
    $(document).on('change','.qualifingcheckbox',function () {
        $selector = $(this).closest('.row');
        if($(this).prop('checked')){
          $selector.find('.text-box-qprice').show();
        }else{
          $selector.find('.text-box-qprice').hide();
        }
    });

  	//Select past participants
	$(document).on('change','.select-past-participant',function () {	
	  $('.btn-invite-parti').click();
	});
  	
  	
  	//Add More restriction options
  	$(document).on('click','.add-more-restriction',function () {
        var id=$(this).attr('data-id');

		var totalNumb=parseInt($(".dateCon").length+2);

        var assets=$(this).prev().val();

		var ClassHtml = getClasses(assets,id,totalNumb);

        var getScoreCla = getScoreClasses(assets,id,totalNumb);


        //jcf.customForms.destroyAll();

        var appendFieldSelector ='<div class="row dateCon">'+ClassHtml+getScoreCla+'\
                                <div class="col-sm-8" style="margin-top:10px;">\
                                  <label><span>Select Date and Time</span> <input type="text" class="daterange form-control datetime-control" required="required" placeholder="Add Date" name="datetimeschedual['+id+']['+totalNumb+']" value="" /></label> \
                                  <label><span>Select Block Time</span> <input type="text" class="daterange form-control datetime-control"  placeholder="Add Date" name="blockTime['+id+']['+totalNumb+']" value="" /></label> \
                                  <label><span>Block Time Titlle</span> <input type="text" class="form-control datetime-control"  placeholder="Add Title Of Block Time"` name="blockTimeTitle['+id+']['+totalNumb+']" value="" /></label> \
                                  <label><span style="padding-top: 0px;">Multiple Time Selection</span> <input type="checkbox" class="form-control"  name="multipleSelection['+id+']['+totalNumb+']" value="" /></label>\
                                  <div class="col-sm-12" style="padding-left: 0px;margin-top: 12px;"><label><span style="padding-top: 0px;">Restrict Riders to Book Rides</span>\
                                  <input type="checkbox" class="form-control" name="restrictRiders['+id+']['+totalNumb+']" value="" /> </label></div></div>\
                                  <div class="col-sm-1" style="margin-top:10px;"><a href="javascript:void(0)" class="deleterowschedual"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>\
                              </div>';
  		$(this).closest('.create-form').find('.schedual-restrictions .adding-restrictions-options').append(appendFieldSelector).slideDown("slow");

         $('.selectpicker').selectpicker('refresh');
  		//Re Initilize the range field.
		$('.daterange').daterangepicker({
			timePicker: true,
            timePickerSeconds:true,
			timePickerIncrement: 1,
			autoUpdateInput: false,
			locale: {
			format: 'MM/DD/YYYY h:mm:ss A'
			}
		}).on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY h:mm:[00] A') + ' - ' + picker.endDate.format('MM/DD/YYYY h:mm:[00] A'));
		});
        //jcf.customForms.replaceAll();


        $(".allAssets").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
            if(oldValue==true)
                return false;
            var selectedD = $(this).find('option').eq(clickedIndex).val();
            var form_id = $(this).data('id');

            var tableElement = $(this).closest('.schedual-restrictions').parent().next().children().find('.display').find('tbody');

            var url = '/master-template/getTimeSLots/'+selectedD+"/"+form_id;
            $.ajax({
                url: url,
                type: "get",
                success: function (data) {
                    if ($(tableElement).find('.asset_'+selectedD).length == 0) {
                        $(tableElement).prepend(data);
                    }
                    else
                    {
                        return false;
                    }
                }, error: function () {
                    alert("error!!!!");
                }
            }); //end of ajax

        });


  	});
});

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}
function getClasses(assets,id,totalNumb) {

    var obj = JSON.parse(assets);

    var HTML = '<div class="col-sm-12 ClassAssets">\
            <label style="padding-top:5px"><span>Select Class:</span></label>\
        <select required multiple name="assets['+id+']['+totalNumb+'][]" style="width: 75%" title=" --- Select Classes --- " data-id="'+id+'" class="selectpicker show-tick form-control allAssets" multiple data-size="8" data-selected-text-format="count>6"  data-live-search="true">';

    $.each(obj, function(index, value) {
		 HTML += '<option value="'+value['id']+'">'+value['name']+'</option>';
    });

     HTML += '</select></div>';

	return HTML;
}


function getScoreClasses(assets,id,totalNumb) {

    var obj = JSON.parse(assets);

    var HTML = '<div class="col-sm-12">\
            <label style="padding-top:5px"><span>Score From:</span></label>\
        <select multiple name="score_from['+id+']['+totalNumb+'][]" style="width: 75%" title=" --- Select Classes --- " data-id="'+id+'" class="selectpicker show-tick form-control scoreAssets" multiple data-size="8" data-selected-text-format="count>6"  data-live-search="true">';

    $.each(obj, function(index, value) {
        HTML += '<option value="'+value['id']+'">'+value['name']+'</option>';
    });

    HTML += '</select></div>';

    return HTML;
}

function checkValidation(ob) {

	if($(ob).val()!='')
	{
        var coup = $(ob).closest('.fields');
        coup.find(".allAssets").prop('required',true);
        coup.find(".datetime-control").prop('required',true);

	}else
	{
        var coup = $(ob).closest('.fields');

        coup.find(".allAssets").prop('required',false);
        coup.find(".datetime-control").prop('required',false);

	}

}