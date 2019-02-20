$(document).ready(function(){


    $(".section-link-list li").on("click",function(e){
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(".section-link-list").offset().top - 40
        }, 800);

        $(".section-link-list li").removeClass("active");
        $(this).addClass("active");
        meshSwiper.slideTo($(this).index(),1000,true);
    })

    
    $(".navbar .navbar-nav .dropdown").on("mouseenter",function(){
        if($(window).width() >= 1200){
            $(this).addClass("open");
            $(this).children(".dropdown-toggle").attr("aria-expanded","true");
        }
    })
    $(".navbar .navbar-nav .dropdown").on("mouseleave",function(){
        if($(window).width() >= 1200){
            $(this).removeClass("open");
            $(this).children(".dropdown-toggle").attr("aria-expanded","false");
        }
    });

    $(".login-section .links-box a[data-rel]").on("click",function(){
        var this_link = $(this).attr("data-rel");
        $(".login-section .toggleable-form").removeClass("active");
        $("#"+this_link).addClass("active");
    });

    var wow = new WOW();
    wow.init();
    
    //select intailize
    if($('.selectpicker').length != 0){
        $('.selectpicker').selectpicker({
            size: 8
        });
    }

    //widgets toggle
    $('.widgets .w-title').on("click", function(e){
        var self = $(this);
        $(this).parents('.widgets').find('.w-content').slideToggle('slow', function(){
            if($(this).is(':hidden')){
                $(self).addClass('hide-content');
            }else{
                $(self).removeClass('hide-content');
            }
        });
    });               

    $(".views-list a[data-view]").on("click",function(e){
        e.preventDefault();
        $(".views-list a").removeClass("active");
        $(this).addClass("active");
        var current_view = $(this).attr("data-view");
        $(".views-box").removeClass("active");
        $("#"+current_view).addClass("active");
    })

    $(".filter-btn").on("click",function(e){
        e.preventDefault();
        $(".sidebar").toggleClass("active");
        $("body").addClass("overlay-open");
    });
    $(".overlay").on("click",function(){
        $(".sidebar").toggleClass("active");
        $("body").removeClass("overlay-open");
    });




    
});