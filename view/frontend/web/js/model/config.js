define(
    [
        'ko'
    ],
    function (ko) {
        'use strict';
        var hasErrors = ko.observable(false);
        var enabled = true;

        return {
            hasErrors: hasErrors,
            enabled: enabled,
            getConfig: function () {
                try {
                    return {
                        // general data
                        'active': window.checkoutConfig.payment.kekspay.active,
                        'is_sandbox': window.checkoutConfig.payment.kekspay.is_sandbox,
                        // data for internal use
                        'order_id': window.checkoutConfig.payment.kekspay.order_id,
                        'identity_hash': window.checkoutConfig.payment.kekspay.identity_hash,
                        // sell request needed data
                        'qr_type': window.checkoutConfig.payment.kekspay.qr_type,
                        'bill_id': window.checkoutConfig.payment.kekspay.bill_id,
                        'cid': window.checkoutConfig.payment.kekspay.cid,
                        'tid': window.checkoutConfig.payment.kekspay.tid,
                        'store_description': window.checkoutConfig.payment.kekspay.store_description,
                        'currency': window.checkoutConfig.payment.kekspay.currency,
                        // logos and links
                        'appstore_logo_svg': window.checkoutConfig.payment.kekspay.appstore_logo_svg,
                        'playstore_logo_svg': window.checkoutConfig.payment.kekspay.playstore_logo_svg,
                        'appstore_app_link': window.checkoutConfig.payment.kekspay.appstore_app_link,
                        'playstore_app_link': window.checkoutConfig.payment.kekspay.playstore_app_link,
                        'kekspay_logo': window.checkoutConfig.payment.kekspay.kekspay_logo,
                        'kekspay_logo_v': window.checkoutConfig.payment.kekspay.kekspay_logo_v,
                        'plus_icon': window.checkoutConfig.payment.kekspay.plus_icon
                    }
                } catch (e) {
                    return {
                        'active' : false
                    }
                }
            }
        };
    }
);
