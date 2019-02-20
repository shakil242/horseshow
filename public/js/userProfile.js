
    function toDataURL(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
        var reader = new FileReader();
        reader.onloadend = function() {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
};
    $uploadCrop = $('#upload-demo').croppie({
    enableExif: true,
        enableOrientation:true,
    viewport: {
        width: 200,
        height: 200,
        type: 'circle'
    },
    boundary: {
        width: 300,
        height: 300
    }
});

    $(function() {
        $('.rotate').on('click', function(ev) {
            $uploadCrop.croppie('rotate',90);
        });
    });


function editImageUrl(url) {

    $("#ajax-loading").show();
    $(".edit_image").hide();
    $(".cancel_edit").show();
    $(".userDetails").hide();

    $(".userEditImage").show();

    toDataURL(url, function(dataUrl) {
        $("#userOrignalImage").val(dataUrl);
        $uploadCrop.croppie('bind', {
            url: dataUrl
        }).then(function(){
            $("#ajax-loading").hide();
        });

    });

}


$('#upload').on('change', function () {
    var reader = new FileReader();
    reader.onload = function (e) {

        $("#userOrignalImage").val(e.target.result);
        $uploadCrop.croppie('bind', {

            url: e.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });

    }

    reader.readAsDataURL(this.files[0]);

});


$('.upload-result').on('click', function (ev) {
    ev.preventDefault();
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        $("#userCroppedImage").val(resp);
        $("#imageUplaod").submit();
    });

});
    
    
    function  cancelEdit() {
        $(".userEditImage").hide();
        $(".edit_image").show();
        $(".userDetails").show();

    }
    
