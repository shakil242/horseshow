$(function () {
   $('.formfields-submit').submit(function(event) {
      $(".validate-upload-file").each(function() { 
         if ($(this)[0].files[0]) {

          var file = $(this)[0].files[0];
          var type = file.type.split("/");
          switch(type[0]) {
            // case 'image':
            //   if(file.size >2617154){
            //      $(this).focus();
            //       alert('Image size should be less then 2.5 MB');
            //       event.preventDefault();
            //       break;
            //   }
               // break;
              case 'application':
                if(file.size >15228948){
                   $(this).focus();
                    alert('File size should be less then 15 MB');
                    event.preventDefault();
                    break;
                }
                break;
              case 'video':
                if(file.size >24254671){
                   $(this).focus();
                    alert('Video size should be less then 20 MB');
                    event.preventDefault();
                    break;
                }
                break;
            }
         }
      });
   }); 
});