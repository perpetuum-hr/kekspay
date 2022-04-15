define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_CheckoutAgreements/js/model/agreements-assigner',
    ],
    function (
        $,
        quote,
        urlBuilder,
        storage,
        errorProcessor,
        customer,
        fullScreenLoader,
        agreementsAssigner
    ) {
        'use strict';

        return function (messageContainer, paymentData) {
            var serviceUrl, payload, method = 'post';

            agreementsAssigner(paymentData);
            payload = {
                cartId: quote.getQuoteId(),
                billingAddress: quote.billingAddress(),
                paymentMethod: paymentData
            };

            if (customer.isLoggedIn()) {
                serviceUrl = urlBuilder.createUrl('/carts/mine/set-payment-information', {});
            } else {
                serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/set-payment-information', {
                    quoteId: quote.getQuoteId()
                });
                payload.email = quote.guestEmail;
            }

            return storage[method](
                serviceUrl, JSON.stringify(payload)
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                }
            );
        };
    }
);
