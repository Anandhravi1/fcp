/**
 * Custom JS
 */

 require([
    'jquery',
    'domReady!',
    'slick'
], function ($) {
    'use strict';

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
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                
            ]
        });
    });


    let slickGreen = false;
    function greenSlider(){
        if($(window).width() < 577){
            if(!slickGreen){
                $(".full-carousel .tabs-navigation").slick({
                    dots: false,
                    arrows: false,
                    slidesToShow: 1,
                    centerMode: true,
                    slidesToScroll: 1
                });
                slickGreen = true;
            }
        } else if($(window).width() > 576){
            if(slickGreen){
                $('.full-carousel .tabs-navigation').slick('unslick');
                slickGreen = false;
            }
        }
    }

    $(document).ready(function(){
        greenSlider();
    });
    $(window).on('resize', function(){
        greenSlider();
    });


    if($('.full-carousel.sticky-foot').length) {
        $(".full-carousel ol.product-items").each(function() {
            $(this).slick({
                infinite: true,
                speed: 300,
                lazyLoad: false,
                slidesToShow: 4,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1025,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
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

         //set position refresh for slick as tab makes slick unresposive
         var Slickcount = 0;
         function myFunction() {
            Slickcount++;
             if(Slickcount > 2) clearInterval(timeout);
             $(".full-carousel ol.product-items").each(function() {
                 $(this).slick('setPosition');
             });
         }
         var timeout = setInterval(myFunction, 3000);

        $("h3.featured").one('mouseover', function() {
            $(".full-carousel ol.product-items").each(function() {
                $(this).slick('setPosition');
            });
        });

        $(".tabs-navigation").click(function() {

            $(".full-carousel ol.product-items").each(function() {
                $(this).slick('setPosition');
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
    }
});