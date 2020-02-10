define([
    'jquery',
    'jquery/ui'
], function($) {
    "use strict";
 
    $.widget('qty.incdec', {
        _create: function() {
            this.increaseQty();
            this.decreaseQty();
            this.validateQty();
        },
        
        increaseQty: function() {
            $(".increaseQty").unbind().click(function(){
                var qty = 1;
                $(this).siblings('input').val(
                    parseInt($(this).siblings('input').val()) + qty
                );
            });
        },
        decreaseQty: function() {
            $(".decreaseQty").unbind().click(function(){
                var qty = 1;           
                if(parseInt($(this).next().val()) > qty) {
                    $(this).next().val(
                        parseInt($(this).next().val()) - qty
                    );                    
                } else {
                    $(this).next().val(1);
                }
            });
        },
        validateQty: function() {
            $(".qty").unbind().change(function(){
                    if($(this).val() < 0) {
                       $(this).val(1);
                    } 
            });
        }
    });
 
    return $.qty.incdec;
});
