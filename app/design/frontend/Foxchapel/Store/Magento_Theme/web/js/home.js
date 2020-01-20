/**
 * Custom JS
 */

 require([
    'jquery',
    'domReady!',
    'slick'
], function ($) {
    'use strict';
    
    setTimeout(function(){
        $(".full-carousel ol.product-items").each(function() {
            $(this).slick({
                infinite: true,
                speed: 300,
                lazyLoad: true,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });

        $(".best-seller ol.product-items").each(function() {
            $(this).slick({
                infinite: true,
                speed: 300,
                lazyLoad: true,
                slidesToShow: 3,
                slidesToScroll: 2,
                responsive: [
                    {
                        breakpoint: 1300,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 900,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    
                ]
            });
        });

    }, 1000);

    $(".tabs-navigation").click(function() {
        $(".full-carousel ol.product-items").each(function() {
            $(this).slick('refresh');
        });
        
        $('html, body').animate({
            scrollTop: $("h3.featured").offset().top - 50
        }, 200);
    });

    $(window).scroll(function(){
            var sticky_foot = $(".full-carousel.sticky-foot");
            var win = $(window);
            var a = sticky_foot.offset().top;
            var b = sticky_foot.height();
            var c = win.height();
            var d = win.scrollTop();
            var e = d + win.innerHeight();
            var f = a + sticky_foot.outerHeight();
            if ((e > a) && (d < f)){
                $('.tabs-navigation').css('position','fixed').css('bottom','0').css('opacity', '1');
            }
            else {
                $('.tabs-navigation').css('opacity', '0');
            }
            if ((c+d)>(a+b)) {
                $('.tabs-navigation').css('position','absolute');
            }
    });
});