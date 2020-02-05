define(["jquery"], function($) {

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

        //layered navigation mobile close
        $(document).ready(function() {
            $('.block-title.filter-title').on('click', function() {
                $('body').toggleClass('laynav-active');
            });
            $('.laynav-close').on('click', function() {
                $('body').removeClass('laynav-active');
            });
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

});