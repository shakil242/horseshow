// $(function () {
//     $('body').on("click", ".trainer-div .change-trainer", function(){ 
//     	var trainer = $(this);
//     	var show_id = trainer.attr("show-id");
//     	getShowTrainers(show_id)
//     });

// });


function  getShowTrainers(show_id,MSR_id=0,obj) {

    var url = '/shows/' + show_id +"/get/trainers"+"/"+MSR_id;	
    $.ajax({
        url: url,
        context: this,
        type: "GET",
        beforeSend: function (xhr) {

            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(obj).parent().parent().find('div.trainer-div').html(data);
  			$('.trainer-div .selectpicker').selectpicker('refresh');
        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax





}
