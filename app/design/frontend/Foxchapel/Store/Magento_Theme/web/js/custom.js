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

});