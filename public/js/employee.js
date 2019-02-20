function viewEmployee(id) {

    var url = '/employee/view/' + id;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function (xhr) {
            var token = $('#csrf-token').val();
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        success: function (data) {

            $(".form-check-label input.form-check-input").prop( "checked", false );
            var obj = JSON.parse(data['results']);

            //console.log(obj);

           $("#name").val(obj.name);
            $("#email").val(obj.email);
            $("#designation").val(obj.designation);
            $("#employee_id").val(obj.id);
            var permissions;
            if(obj.permissions!=null)
              permissions = JSON.parse(obj.permissions);
            //jcf.customForms.destroyAll();
            var i;
            if(permissions!=null) {
                for (i = 0; i < permissions.length; ++i) {
                    $("#permissions-" + permissions[i]).prop( "checked", true );
                }
            }
            $(".employeePermission").modal("show");
            $(".employeePermission").addClass("show");
            //jcf.customForms.replaceAll();
        }, error: function () {
            alert("error!!!!");
        }
    }); //end of ajax
}

$('#checkall').change(function() {

    $(".check").prop('checked', $(this).prop('checked'));
    //jcf.customForms.destroyAll();

    if($(this).is(':checked')){
        $('input.check').parent().find("div").removeClass('chk-unchecked');
        $('input.check').parent().find("div").addClass('chk-checked');
        $('input.check').attr("checked",true);
    }
    else
    {
        $('input.check').parent().find("div").removeClass('chk-checked');
        $('input.check').parent().find("div").addClass('chk-unchecked');
        $('input.check').attr("checked",false);
    }
    //jcf.customForms.replaceAll();


});

// $("#checkall").click(function() {
//
//     alert(444);
//     var is_checked = $(this).is(":checked");
//     $(this.form).find(checkboxes_sel).prop("checked", is_checked)
//         .parents("tr").toggleClass("marked", is_checked);
// });
$( document ).ready(function () {
    $(".cancel").click(function () {
        $("#addEmployee")[0].reset();

    });


});