//Storing the scroll feature.
$(window).scroll(function () {
    //set scroll position in session storage
    sessionStorage.scrollPos = $(window).scrollTop();
});

var init = function () {
    //get scroll position in session storage
    $(window).scrollTop(sessionStorage.scrollPos || 0)
};
//Calling the function
window.onload = init;