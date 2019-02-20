$(function () {
  $('.add-more').on("click", function () {
        var len = parseInt($(".position-listing .duplicator").length);
        lengt = len+1;
        $(".position-listing .duplicators").before("<div class='duplicator'>\
                <div class='row'>\
                <div class='col-sm-2' style='text-align: center;'>\
                    <label>"+lengt+"</label>\
                </div>\
                <div class='col-sm-3'>\
                   <div class='col-sm-2' style='padding-top: 5px;'></div> <div class='col-sm-10'><input name='placingprice["+lengt+"][price]' type='number' class='form-control' placeholder='Add Points' min='0' step='0.5' max='1000' value='0' ></div>\
                </div>\
                <input name='placingprice["+lengt+"][position]' type='hidden' value='"+lengt+"'>\
                <a href='#' class='delete-position'>x</a>\
                </div></div>");
  });

    $('body').on("click", '.delete-position', function () {
        if (confirm('Are you sure?')) {
          $(this).closest(".duplicator").remove();     
        }
  });


  

});