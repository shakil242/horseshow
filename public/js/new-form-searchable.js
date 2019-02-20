$(function() {

  // the input field
  var $input = $("input.find-form-search"),
    // clear button
    $clearBtn = $("button[data-search='clear']"),
    // prev button
    $prevBtn = $("button[data-search='prev']"),
    // next button
    $nextBtn = $("button[data-search='next']"),
    // the context where to search
    $content = $(".searchable-contents");
    //$button_click = $(".search-for-value"),

  /**
   * Searches for the entered keyword in the
   * specified context on input
   */
  $("body").on("click",".search-for-value", function() {
    $(".searchable-contents .form-group").removeClass("has-success");
    var searchVal = $("input.find-form-search").val();
    $(".searchable-contents :input.form-control").each(function(index)  {
       var str1 = $(this).val().toLowerCase();
        if(str1.indexOf(searchVal) != -1){
            $results = 1;
            $(this).focus();
            currentIndex = $(this).closest(".form-group");
            $(this).closest(".form-group").addClass("has-success");
            return false;
        }
    });
  });

    /**
   * Searches for the entered keyword in the
   * specified context on input
   */
  $("body").on("click","button[data-search='next']", function() {
    $(".searchable-contents .form-group").removeClass("has-success");
    var searchVal = $("input.find-form-search").val();
    $(".searchable-contents :input.form-control").each(function(index)  {
       var str1 = $(this).val().toLowerCase();
        if(str1.indexOf(searchVal) != -1){
            console.log(currentIndex);
        }
    });
  });

$nextBtn.add($prevBtn).on("click", function() {
    if ($results.length) {
      currentIndex += $(this).is($prevBtn) ? -1 : 1;
      if (currentIndex < 0) {
        currentIndex = $results.length - 1;
      }
      if (currentIndex > $results.length - 1) {
        currentIndex = 0;
      }
      jumpTo();
    }
  });


window.onscroll = function() {myStickyFunction()};

var header = document.getElementById("floatable-search-header");
var sticky = header.offsetTop;

function myStickyFunction() {
  if (window.pageYOffset >= sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

});