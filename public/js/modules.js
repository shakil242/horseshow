
function getLogomodel(id,title) {


    $("#module_id").val(id);
    var actualImage = $("#actual_"+id).val();

    $("#title").val(title);
   if(typeof(actualImage)!='undefined')
   {
       $("#upload-demo").show();
   }else
   {

       $("#upload-demo").hide();
   }

    editImageUrl(actualImage);
    $('#myModal').modal('show');
    $('#myModal').addClass('show');
    $(".modal-backdrop").addClass('show');
}

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
    enableZoom:false,
    enableOrientation:false,
    viewport:false,
    enforceBoundary:false,
    boundary: {
        width: 400,
        height: 400
    }
});


function editImageUrl(url) {

    //$("#ajax-loading").show();
    $(".edit_image").hide();
    $(".cancel_edit").show();
    $(".userDetails").hide();

    $(".userEditImage").show();

    toDataURL(url, function(dataUrl) {
        $("#userOrignalImage").val(dataUrl);
        $uploadCrop.croppie('bind', {
            url: dataUrl
        }).then(function(){
           // $("#ajax-loading").hide();
            $uploadCrop.croppie('result', {
                enableExif: true,
                enableZoom:false,
                enableOrientation:false,
                viewport:false,
                enforceBoundary:false,
                boundary: {
                    width: 400,
                    height: 400
                }
            }).then(function (resp) {
                $("#userCroppedImage").val(resp);
            });

        });

    });

}


$('#upload').on('change', function () {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#userOrignalImage").val(e.target.result);
        $("#upload-demo").show();
        $uploadCrop.croppie('bind', {
            url: e.target.result
        }).then(function(){
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                console.log(resp);
                $("#userCroppedImage").val(resp);
            });

        });

    }

    reader.readAsDataURL(this.files[0]);

});

