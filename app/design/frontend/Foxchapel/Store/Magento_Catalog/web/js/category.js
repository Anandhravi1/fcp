/**
 * Custom JS
 */

require([
    'jquery',
    'domReady!',
    'slick'
], function ($) {
    'use strict';

    if($('.category-slider')) {
        $(".category-slider .items").each(function () {
            $(this).slick({
                infinite: true,
                speed: 300,
                lazyLoad: true,
                slidesToShow: 4,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1300,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 1025,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 868,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }

                ]
            });
        });
    }

    if($('.category-products-slider .product-items')) {
        $(".category-products-slider .product-items").each(function () {
            $(this).slick({
                infinite: true,
                speed: 300,
                lazyLoad: true,
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
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }

                ]
            });
        });
    }


});