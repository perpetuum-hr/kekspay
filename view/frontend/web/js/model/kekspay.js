define(
    [
        'jquery',
        'Perpetuum_KeksPay/js/model/config',
        'Perpetuum_KeksPay/js/model/qrcode',
        'mage/url',
        'Perpetuum_KeksPay/js/action/set-payment-method',
    ],
    function (
        $,
        config,
        qrcode,
        mageUrl,
        setPaymentMethod,
    ) {
        'use strict';

        var exports = {};
        var _timeout;
        var _isSandbox = config.getConfig().is_sandbox;

        var _demoConfig = {
            env: 'kekspay-demo',
            baseUrl: 'kekspay.hr/galebpay'
        };

        var _prodConfig = {
            env: 'kekspay',
            baseUrl: 'kekspay.hr/pay'
        };

        exports.redirect_to = function (url) {
            location.href = url;
        };

        exports.kill_redirect = function () {
            if (typeof _timeout === "number") {
                _timeout = clearTimeout(_timeout);
            }
        };

        exports.platformHasNativeSupport = function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod|Android|Dalvik/);
        };

        var scan = function (payAmount, messageContainer, paymentMethodData) {
            var locConfig;

            if (_isSandbox) {
                locConfig = _demoConfig;
            } else {
                locConfig = _prodConfig;
            }

            const mainConfig = config.getConfig();
            const identityParam = 'identity=' + mainConfig.identity_hash;

            let additional_data = {};
            additional_data['device'] = 'Mobile';

            var url = [
                'https://',
                locConfig.baseUrl,
                '?cid=' + mainConfig.cid,
                '&qr_type=' + mainConfig.qr_type,
                '&tid=' + mainConfig.tid,
                '&bill_id=' + mainConfig.bill_id,
                '&currency=' + mainConfig.currency,
                '&amount=' + payAmount,
                // '&fail_url=' + encodeURI(mageUrl.build('kekspay/checkout/returnfail?' + transactionIdParam)),
                '&success_url=' + encodeURI(mageUrl.build('kekspay/checkout/success?' +  identityParam))
            ].join('');

            // set 'device' to Mobile so that we know the request is from mobile devices.
            paymentMethodData.additional_data = additional_data;

            setPaymentMethod(messageContainer, paymentMethodData).done(function () {
                $.mage.redirect(url);
            });
        }

        var getQRcode = function (content) {
            var qrCode;

            qrCode = document.createElement('div');
            qrCode.className = 'kekspay-qr-image';

            // Create QR Code
            new qrcode.QRCode(qrCode, {
                text: content,
                width: 180,
                height: 180,
                correctLevel: qrcode.QRCode.CorrectLevel.L
            });

            // remove qrCode content hints when hovering
            qrCode.removeAttribute("title");
            qrCode.getElementsByTagName("img")[0].removeAttribute("alt");

            return qrCode;
        };

        exports.checkout_native = function(payAmount, messageContainer, paymentMethodData) {
            scan(payAmount, messageContainer, paymentMethodData);
        };

        exports.checkout = function (payAmount) {
            const configs = config.getConfig();

            _isSandbox = configs.is_sandbox;

            return getQRcode(JSON.stringify(
                {
                    qr_type: configs.qr_type,
                    bill_id: configs.bill_id,
                    cid: configs.cid,
                    tid: configs.tid,
                    amount: payAmount,
                    currency: configs.currency,
                    store: configs.store_description,
                }
            ));
        };

        return exports;
    }
)
