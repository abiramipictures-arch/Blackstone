; (function ($) {
    "use strict";

    var quotes = [];
    var sub_quotes = [];

    quotes[0] = "Comprehensive App Documentation";
    sub_quotes[0] = "Your Ultimate Guide to Mastering Our Applications";

    quotes[1] = "DivineTechs Application Documentation";
    sub_quotes[1] = "Navigate, Understand, Implement with Ease";

    quotes[2] = "All-in-One App Documentation Hub";
    sub_quotes[2] = "Detailed Guides and Resources for Seamless Integration";

    quotes[3] = "Welcome to DivineTechs Documentation";
    sub_quotes[3] = "Empowering Your Journey with Clear and Concise Guides";

    quotes[4] = "Expert App Documentation Center";
    sub_quotes[4] = "Step-by-Step Instructions for Optimal Performance";

    quotes[5] = "Your Guide to DivineTechs Apps";
    sub_quotes[5] = "Simplifying Usage and Implementation One Step at a Time";

    quotes[6] = "Ultimate Documentation for DivineTechs";
    sub_quotes[6] = "Clear, Comprehensive, and User-Friendly Guides";

    quotes[7] = " Master Your Apps with Our Documentation";
    sub_quotes[7] = "In-Depth Guides for Every Feature and Function";

    var rad_num = Math.floor(Math.random() * quotes.length);
    var quote = quotes[rad_num];
    var sub_quote = sub_quotes[rad_num];

    /*============= preloader js css =============*/
    $('#preloader h2').text(quote);
    $('#preloader p').text(sub_quote);
    
    $('#preloader').addClass('loading');
    $(window).on('load', function () {
        setTimeout(function () {
            $('#preloader').fadeOut(500, function () {
                $('#preloader').removeClass('loading');
            });
        }, 500);
    })

    /*============= Banner js css =============*/

    $('.main_banner_title').text(quote);
    $('.main_banner_subtitle').text(sub_quote);
})(jQuery)