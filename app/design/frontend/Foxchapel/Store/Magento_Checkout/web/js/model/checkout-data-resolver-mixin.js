define([
    'underscore',
    'mage/utils/wrapper',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/action/select-payment-method'
],function (_, wrapper, checkoutData, selectShippingMethodAction) {
    'use strict';

    return function (checkoutDataResolver) {

        var resolveShippingRates = wrapper.wrap(
            checkoutDataResolver.resolveShippingRates,
            function (originalResolveShippingRates, ratesData) {
                // select your shipping method here using the ratesData and selectShippingMethodAction
                if (ratesData.length >= 1) {
                    //set shipping rate if we have only one available shipping rate
                    selectShippingMethodAction(ratesData[0]);
                }    
                return originalResolveShippingRates(ratesData);
            }
        );

        return _.extend(checkoutDataResolver, {
            resolveShippingRates: resolveShippingRates
        });
    };
});