define(['jquery', 'domReady!', 'slick'], function($) {

    $(".level1.submenu:has(.category_image)").addClass('has-category-image');

    $('li.level0 li.category-item.parent > a').prepend('<span class="rm-expand close"></span>');

    $('.navigation li.category-item.parent > a').click(function(e) {
        if ($(e.target).hasClass('ui-menu-icon')) {
            e.preventDefault();
            e.stopPropagation();
            $(this).children("span.rm-expand").trigger("click");
            return false;
        }
    });

    $('.rm-expand').click(function() {
        var li = $(this).parent().parent().siblings();
        li.find('ul:first').hide();
        li.find('.rm-expand').removeClass('open').addClass('close');
        li.find('a.ui-state-active').removeClass('ui-state-active')
        if ($(this).hasClass('open')) {
            $(this).parent().parent().find('ul:first').hide();
            $(this).parent().removeClass('ui-state-active');
            $(this).removeClass('open').addClass('close');
        } else {
            $(this).parent().parent().find('ul:first').show();
            $(this).parent().addClass('ui-state-active');
            $(this).addClass('open').removeClass('close');
        }
        return false;
    });

    //collabsible layered nav open only one
    $('body').on('beforeOpen', '.filter-options-item', function () {
        $('.filter-options-item.active').collapsible('deactivate');
    });
    
    //layered navigation mobile close
        $('body').on('click', '.laynav-close', function() {
            $('body').toggleClass('laynav-active');
        });

    //Message close 
    $('body').on('click', '.page.messages .message', function () {
        $(this).css('display', 'none');
    });

    //Identifying if user is on mobile browser or not
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if( isMobile.any() ) $('body').addClass('mobile-device');

    //slick for category description in PLP
    $(".page-with-filter .category-description ol.product-items").slick({
            infinite: true,
            speed: 300,
            lazyLoad: true,
            slidesToShow: 3,
            slidesToScroll: 2,
            responsive: [
                {
                    breakpoint: 1100,
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
    // footer links mobile view
   jQuery(document).ready(function($){
    $('.footer-part h6').on('click', function () {
        var _this = $(this);
        $('.footer-part h6').not(_this).removeClass('active');
        $('.footer-part h6').not(_this).next('.block-content').slideUp('slow');
        if (!_this.hasClass('active')) {
            _this.addClass('active');
            _this.next('.block-content').slideDown('slow');
        } else {
            _this.removeClass('active');
            _this.next('.block-content').slideUp('slow');
        }
    });

    //cart page wishlist click event
      $('.form-cart .split.button.wishlist').click(function(){
          $(this).toggleClass('active');
      });
   });
});