/****
* Created on : 22-08-2017.
* @Author: Faran Ahmed Khan.
* Vteams
****/
$(function () {

  var myId;
  $('ul.pagination').hide();

  //Filters active
  var loc = window.location.href;
  //var loc = window.location.pathname;
  $('.nav-filters-added').find('li').each(function() {
     $(this).toggleClass('active', $(this).find('a').attr('href') == loc);
  });

  //Edit Post
  $(document).on("click","a.edit-post", divClicked);

  //Delete Post
   $(document).on("click","a.delete-post", deletePost);

   //Delete Comment
   $(document).on("click","a.delete-comment", deleteComment);
  
  //Edit comment
   $(document).on("click","a.edit-comment", commentdivClicked);
   //Like/unlike post
   $(document).on("click","a.like-post", likePost);
  
  //Like view users liked
   $(document).on("click","a.like-count-viewer", likePostUsers);

    loadGallery(true, 'a.thumbnail');
   
   //Easy loading of posts
    $('.infinite-scroll').jscroll({
        autoTrigger: true,
        loadingHtml: '<img  height="120" class="center-block" src="/img/ajax-loading.gif" alt="Loading..." />',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.infinite-scroll',
        callback: function() {
            $('ul.pagination').remove();
        }
    });

   //Modal image
   $(document).on('click', 'img', function () {
        var image = $(this).attr('src');
        $("#responsivess").attr("src", image);
        // $('#myModal').on('show.bs.modal', function () {
            
        // });
    });

   //Video clicked
   $(document).on("click",".video-camera-click", function(){
      $selector = $(this).closest('.timeline-share');
      $selector.find(".videoLink").toggle();// Define focus handler
      $selector.find(".videoLink").focus().attr('required', function(_, attr){ return !attr});
      $selector.find(".video_msg").toggle();
   });

   //Keyup on video
    $(document).on("keyup",".videoLink", function(){
        var myUrl = $(this).val();
        myId = getId(myUrl);
        if (myId !== 'error') {
          $('.tab-pane.active div.video-preview').html('<iframe width="560" height="315" src="//www.youtube.com/embed/' + myId + '" frameborder="0" allowfullscreen></iframe>');
        };
    });
    //View comment textarea.
    // $(document).on("click",".add-comment", function(){
    //     $(this).closest('.history-holder').find('.reply-comment-holder').toggle();
    //     $(this).closest('.history-holder').find('.reply-comment-holder textarea').focus();
    // });
    //Commenting on the post.
    $(document).on("click",".btn-comment", comment);
    
   
});

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current){
        $('#show-previous-image, #show-next-image').show();
        if(counter_max == counter_current){
            $('#show-next-image').hide();
        } else if (counter_current == 1){
            $('#show-previous-image').hide();
        }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr){
        var current_image,
            selector,
            counter = 0;


        $('#show-next-image, #show-previous-image').click(function(){
            if($(this).attr('id') == 'show-previous-image'){
                current_image--;
            } else {
                current_image++;
            }

            selector = $('[data-image-id="' + current_image + '"]');
            updateGallery(selector);
        });

        function updateGallery(selector) {
            var $sel = selector;
            current_image = $sel.data('image-id');
            var count = $sel.closest('.row').find('.share-image-holder').length;
            //alert(count);
            $('#image-gallery-caption').text($sel.data('caption'));
            $('#image-gallery-title').text($sel.data('title'));
            $('#image-gallery-image').attr('src', $sel.data('image'));
            disableButtons(counter, $sel.data('image-id'));
        }

        if(setIDs == true){
            $('[data-image-id]').each(function(){
                counter++;
                $(this).attr('data-image-id',counter);
            });
        }
        $(setClickAttr).on('click',function(){
            updateGallery($(this));
        });
    }
    

function getId(url) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);

    if (match && match[2].length == 11) {
        return match[2];
    } else {
        return 'error';
    }
}

function comment(){

       var post_id = $(this).closest('.reply-comment-holder').find('.post_id').val();
       var comment = $(this).closest('.reply-comment-holder').find('.commentable').val();
       var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url: "/timeline/add/comments",
          type:"ADD",
          method:"POST",
          data: {'post_id':post_id , 'comment':comment ,"_token": token,},
          context: this,
          beforeSend: function()
                {
                    $('#ajax-loading').show();
                },
          success: function(json) {
            if(json.status == 'true'){
                $(this).closest('.media-body').find(".history-inner .comment-list").append(json.data);
                $(this).closest('.media-body').find("textarea.commentable").val('');
            };
            if (json.status == 'false') {
              $(this).closest('.media-body').find(".error-msg").html(json.data);
            };
            $('#ajax-loading').hide();
          },
           error: function( _response ){
                    $('#ajax-loading').hide();
          }
        });
    return false;  
}
//Like Post
function likePost(){
      var id = $(this).closest('.history-holder').find('.post-id').val();
      var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url: "/timeline/likeUnlike/posts",
          method:"POST",
          data: {'id':id , "_token": token,},
          context: this,
          "success": function(json) {
            if(json.status == 'true'){
              $(this).closest('.history-actions-holder').find('.like-count-viewer .counts').html(json.likes);
              $(this).toggleClass("clicked");
              return true;
            };
          }
        });
}
//Users who liked the post
function likePostUsers(){
      var id = $(this).closest('.history-holder').find('.post-id').val();
      var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
          url: "/timeline/likeUsers/posts",
          method:"POST",
          data: {'id':id , "_token": token,},
          context: this,
          "success": function(json) {
            if(json.status == 'true'){
              $('#likeModal .modal-body').html(json.html);
              $('#likeModal').modal('show')
              $('#likeModal').addClass('show');
              $(".modal-backdrop").addClass('show');
              return true;
            };
          }
        });
}

//Delete Post
function deletePost(){
    if (confirm("Are you sure you want to delete this post?")) {
       var id = $(this).closest('.history-holder').find('.post-id').val();
       var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url: "/timeline/delete/posts",
          type:"DELETE",
          method:"POST",
          data: {'id':id , "_token": token,},
          context: this,
          "success": function(json) {
            if (json == "true") {
              $(this).closest('.history-holder').fadeOut(400, function() { $(this).remove(); });
              return true;
            };
          }
        });
    }
    return false;  
}

//Delete Comment

function deleteComment(){
      if (confirm("Are you sure you want to delete this post?")) {
       var id = $(this).closest('.comment-single').find('.comment-id').val();
       var token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url: "/timeline/delete/comment",
          type:"DELETE",
          method:"POST",
          data: {'id':id , "_token": token,},
          context: this,
          "success": function(json) {
            if (json == "true") {
              $(this).closest('.comment-single').fadeOut(400, function() { $(this).remove(); });
              return true;
            };
          }
        });
    }
    return false;  
}
//Edit Post
function divClicked() {
    var pText = $(this).closest('.history-holder').find('.post_contents');
    var divHtml = pText.html();
    var editableText = $("<textarea rows='4' cols='80' class='post_contentss' />");
    editableText.val(divHtml);
    pText.replaceWith(editableText);
    editableText.focus();
    // setup the blur event for this new textarea
    editableText.blur(editableTextBlurred);
}
function editableTextBlurred() {
    var pText = $(this).closest('.history-holder').find('.post_contentss');
    var html = pText.val();
    var id = $(this).closest('.history-holder').find('.post-id').val();
    var viewableText = $('<p class="post_contents">');
    viewableText.html(html);
    pText.replaceWith(viewableText);
    var token = $('meta[name="csrf-token"]').attr('content'); 
    // setup the click event for this new div
    //viewableText.click(divClicked);
     var base_url = window.location.protocol + "//" + window.location.host + "/";
     $.ajax({
           url: "/timeline/edit/posts",
          type:"DELETE",
          method:"POST",
          data: {'post': html, 'id':id , "_token": token,},
          "success": function(json) {

          }
        });
}

//Edit comment on post
function commentdivClicked() {
    var pText = $(this).closest('.comment-single').find('.comment_contents');
    var divHtml = pText.html();
    var editableText = $("<textarea class='comment_contentss' />");
    editableText.val(divHtml);
    pText.replaceWith(editableText);
    editableText.focus();
    // setup the blur event for this new textarea
    editableText.blur(ceditableTextBlurred);
}
function ceditableTextBlurred() {
    var pText = $(this).closest('.comment-single').find('.comment_contentss');
    var html = pText.val();
    if ($.trim(html) !== "") {
      var id = $(this).closest('.comment-single').find('.comment-id').val();
      var viewableText = $('<p class="comment_contents">');
      viewableText.html(html);
      pText.replaceWith(viewableText);
      var token = $('meta[name="csrf-token"]').attr('content'); 
      // setup the click event for this new div
      //viewableText.click(divClicked);
       var base_url = window.location.protocol + "//" + window.location.host + "/";
       $.ajax({
             url: "/timeline/edit/comment",
            type:"DELETE",
            method:"POST",
            data: {'post': html, 'id':id , "_token": token,},
            "success": function(json) {

            }
          }); 
    }else{
      alert("Comment cannot be empty. Please add some values.");
    };
}

//View uploaded file
window.onload = function(){
        
    //Check File API support
    if(window.File && window.FileList && window.FileReader)
    {
        var filesInput = document.getElementById("FileID");
        var filesInput2 = document.getElementById("FileID2");
        
        filesInput.addEventListener("change", function(event){
            
            var files = event.target.files; //FileList object
            var output = document.getElementById("result");
            while (output.hasChildNodes()) {
                output.removeChild(output.firstChild);
            }
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                
                //Only pics
                if(!file.type.match('image'))
                  continue;
                
                var picReader = new FileReader();
                
                picReader.addEventListener("load",function(event){
                    
                    var picFile = event.target;
                    
                    var div = document.createElement("div");
                    div.className = "imagediv";
                    div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +
                            "title='" + picFile.name + " width='180' height='125'/>";
                    
                    output.insertBefore(div,null);            
                
                });
                
                 //Read the image
                picReader.readAsDataURL(file);
            }                               
           
        });

        filesInput2.addEventListener("change", function(event){
            
            var files = event.target.files; //FileList object
            var output = document.getElementById("result2");
            while (output.hasChildNodes()) {
                output.removeChild(output.firstChild);
            }
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                
                //Only pics
                if(!file.type.match('image'))
                  continue;
                
                var picReader = new FileReader();
                
                picReader.addEventListener("load",function(event){
                    
                    var picFile = event.target;
                    
                    var div = document.createElement("div");
                    div.className = "imagediv";
                    div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +
                            "title='" + picFile.name + " width='180' height='125'/>";
                    
                    output.insertBefore(div,null);            
                
                });
                
                 //Read the image
                picReader.readAsDataURL(file);
            }                               
           
        });
    }
    else
    {
        console.log("Your browser does not support File API");
    }
}
