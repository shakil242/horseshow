$(function() { 
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('.app-listing a.list-group-item').on('click', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        //alert($(this).index());
        localStorage.setItem('verticleTab', $(this).index());
    });

    // go to the latest tab, if it exists:
    var lastTabV = localStorage.getItem('verticleTab');
    if (lastTabV) {
        $('.app-listing a.list-group-item').eq( lastTabV ).trigger('click');
    }
});