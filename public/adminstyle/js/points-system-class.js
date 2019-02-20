
  //Add New class
 $(function() {
  
  var base_url = window.location.protocol + "//" + window.location.host + "/";
  $("#add").click(function() {
    $.ajax({
        type: 'post',
        url : base_url+'admin/add/class/points',
        data: {
            '_token': $('input[name=_token]').val(),
            'name': $('input[name=name]').val()
        },
        // beforeSend: function (xhr) {
        //         var token = $('#csrf-token').val();
        //         if (token) {
        //             return xhr.setRequestHeader('X-CSRF-TOKEN', token);
        //         }
        //     },
        success: function(data) {
            if ((data.errors)) {
                $('.error').removeClass('hidden');
                $('.error').text(data.errors);
            } else {
                $('.error').remove();
                $('#crudTable2').append("<tr class='showtype" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-trash'></span> Delete</button><a href='/admin/class/positions/" + data.id + "' class='pull-left btn btn-warning' style='color:white'> Positions Points </a></td></tr>");
            }
        },
    });

    $('#name').val('');
  });

    //edit class
    $("body").on('click',".edit-modal", function(){
        $('#footer_action_button').text(" Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('edit');
        $('.modal-title').text('Edit');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        $('#fid').val($(this).data('id'));
        $('#n').val($(this).data('name'));
        $('#myModal').modal('show');
    });

    //Modal

    $('.modal-footer').on('click', '.edit', function() {
 
        $.ajax({
            type: 'post',
            url: base_url+'admin/edit/class/points',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $("#fid").val(),
                'name': $('#n').val()
            },
            success: function(data) {
                $('.showtype' + data.id).replaceWith("<tr class='showtype" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-trash'></span> Delete</button><a href='/admin/class/positions/" + data.id + "' class='pull-left btn btn-warning' style='color:white'> Positions Points </a></td></tr>");
            }
        });
    });

    //Delete
    $(document).on('click', '.delete-modal', function() {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('delete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('.deleteContent').show();
        $('.form-horizontal').hide();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    });

    //Delete model
    $('.modal-footer').on('click', '.delete', function() {
        $.ajax({
            type: 'post',
            url: base_url+'admin/delete/class/points',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text()
            },
            success: function(data) {
                $('.showtype' + $('.did').text()).remove();
            }
        });
    });


});

