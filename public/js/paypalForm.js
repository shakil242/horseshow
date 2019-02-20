$(function(){
    jQuery('#display-assets-tables li:first a').click();

    $('#dateOfBirth').datepicker({

        changeYear: true, yearRange : 'c-65:c-10'

    });

});

$(document).ready(function(){
    /***phone number format***/
    $(".phone-format").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
        var curchr = this.value.length;
        var curval = $(this).val();
        if (curchr == 3 && curval.indexOf("(") <= -1) {
            $(this).val("(" + curval + ")" + "-");
        } else if (curchr == 4 && curval.indexOf("(") > -1) {
            $(this).val(curval + ")-");
        } else if (curchr == 5 && curval.indexOf(")") > -1) {
            $(this).val(curval + "-");
        } else if (curchr == 9) {
            $(this).val(curval + "-");
            $(this).attr('maxlength', '14');
        }
    });

    $('#state').selectpicker({
        liveSearch: true,
        maxOptions: 1
    });

});