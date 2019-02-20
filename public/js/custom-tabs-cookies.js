$(function() {
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('.secondaryMenu li a').on('click', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        //$('[href="' + lastTab + '"]').tab('show').trigger('click');
        $('[href="' + lastTab + '"]').tab('show').click();

    }
});