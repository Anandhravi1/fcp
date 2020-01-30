define([
    "jquery",
    "jquery/ui",
    "Magento_Customer/js/customer-data"
], function ($, ui, customerData) {

    $.widget('mage.amQuickview', {
        options: {},
        imageSelector: '.product-image-photo',
        customerData: customerData,

        _create: function (options) {
            var self = this;
            if (this.checkFancybox()  || window.self !== window.top) {
                return;
            }

            self.element.on(
                {
                    'mouseenter': function() {self.showLen(this)},
                    'mouseleave': function() {self.hideLen(this)},
                    'touchstart': function() {self.showLen(this)},
                    'touchend'  : function(){
                        var event = this;
                        setTimeout(function(){
                            self.hideLen(event);
                        }, 2000);
                    }
                }
            );
        },

        createHover : function(element) {
            var self = this;
            var productId = this.getProductId(element);
            if (!productId) {
                console.log("We didn't find price block with product id");
                return false;
            }

            var hover = $('<div />', {
                class : 'amquickview-hover'
            });
            hover.attr("style", this.options['css']);

            var link = $('<a />', {
                class : 'amquickview-link',
                title: 'Quickview',
                id : 'amquickview-link-' + productId
            });
            link.attr('data-product-id', productId);
            link.html(this.options['text']);

            hover.prependTo(element).hide();
            link.click(function( event ) {
                self.showPopup( event )
            });
            link.appendTo(hover);
            /*
             * set hover block at the bottom of the image
             * */
            var image = element.find(this.imageSelector);
            if (image.length) {
                image = $(image[0]);
                var imageHeight = image.height();
                var itemPadding= parseInt(element.css('paddingTop'));
                var hoverHeight = hover.outerHeight();
                var px = (imageHeight - hoverHeight + itemPadding);
                hover.css({
                    top: px + 'px'
                });

                var imageMargin = parseInt(image.css('marginLeft'));
                if (imageMargin) {
                    hover.css({
                        marginLeft: imageMargin + 'px'
                    });
                }
            }

            return hover;
        },

        showPopup : function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            var element = $(e.target);

            if (undefined == this.options['url']) {
                return false;
            }

            if (element.hasClass('am-quickview-icon')) {
                element = element.parent();
            }

            var productId = element.attr('data-product-id');
            if (!productId) return;

            var url = this.options['url'] +"?id=" + productId;
            var fancyboxVersion = jQuery.fancybox ? parseInt(jQuery.fancybox.version.split('.')[0]) : 0;

            switch (fancyboxVersion) {
                case 2:
                    $.fancybox.open({
                        customerData: this.customerData,
                        padding :0,
                        href    : url,
                        type    : 'iframe',
                        opts   : {
                            iframe : {
                                css : {
                                    width : '800px'
                                }
                            },
                            smallBtn : true,
                            toolbar : false,
                            baseClass : 'amquickview-fancybox-wrapper',
                            afterClose : function() {
                                var sections = ['cart'];
                                this.customerData.reload(sections);
                            }
                        }
                    });
                    break;
                case 3:
                default:
                    $.fancybox.open({
                        customerData: this.customerData,
                        src    : url,
                        type   : 'iframe',
                        opts   : {
                            iframe : {
                                css : {
                                    width : '800px'
                                }
                            },
                            smallBtn : true,
                            baseClass : 'amquickview-fancybox-wrapper',
                            toolbar : false,
                            afterClose : function() {
                                var sections = ['cart'];
                                this.customerData.reload(sections);
                            }
                        }
                    });
                    break;
            }

            this.hideLen(element.parent());
            return false;
        },

        showLen : function(element) {
            if (!element) return false;
            element = $(element);
            var parent = $(element).find('.actions-secondary');
            if (parent.length != 0) {
                element = parent;
            }
            var hover = element.find('.amquickview-hover').first();
            if (!hover.length) {
                hover = this.createHover(element);
            }

            if (hover) {
                hover.css('display','inline-block')
            }
        },

        checkFancybox : function() {
            var fancyboxVersion = jQuery.fancybox ? parseInt(jQuery.fancybox.version.split('.')[0]) : 0;
            if (fancyboxVersion == 0) {
                console.error('Fancybox library is not loaded');
                return true;
            }

            return false;
        },

        hideLen : function(element) {
            if (! element) return false;
            var hover = $(element).find('.amquickview-hover').first();
        },

        getProductId: function(parent) {
            var parentElement = parent.closest('.item, .product-item');
            if (parentElement.length != 0) {
                parent = parentElement;
            }

            var element = parent.find('[data-product-id]');
            if (element[0]) {
                var id = $(element[0]).attr('data-product-id');
                id = parseInt(id);
                if (id) {
                    return id;
                }
            }

            element = parent.find('[data-post]');
            if (element[0]) {
                var dataPost = $(element[0]).attr('data-post');
                dataPost = $.parseJSON(dataPost);
                if (dataPost && dataPost.data && dataPost.data.product) {
                    id = parseInt(dataPost.data.product);
                    return id;
                }
            }

            return false;
        }
    });

    return $.mage.amQuickview;
});
