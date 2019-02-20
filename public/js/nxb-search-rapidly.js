$(function () {
$.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
  $('.typeahead').keyup(function () {
      var filter = $(this).val().toLowerCase();;
      if ( filter ) {
          var $found = $('.moduleName span:contains("' + filter + '")').closest('.Smodule');
          $('.Smodule').show();
          $('.Smodule').not($found).hide()
      } else {
          $('.Smodule').show();
      }
  });
    $('.typeahead-apps').keyup(function () {
      var filter = $(this).val().toLowerCase();;
      if ( filter ) {
          var $found = $('.app-name:containsIN("' + filter + '")').closest('.list-group a');
          $('div.active .list-group a').show();
          $('div.active .list-group a').not($found).hide()
      } else {
          $('div.active .list-group a').show();
      }
  });
});