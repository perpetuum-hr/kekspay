define(
    [
        'ko',
        'jquery',
        'mage/translate',
        'Magento_Checkout/js/view/payment/default',
        'Perpetuum_KeksPay/js/model/config',
        'Magento_Ui/js/modal/modal',
        'Perpetuum_KeksPay/js/model/kekspay',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/customer-data',
        'mage/url',
        'Perpetuum_KeksPay/js/action/set-payment-method',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/payment/additional-validators',
    ],
    function (
        ko,
        $,
        $t,
        Component,
        config,
        modal,
        kekspay,
        quote,
        customerData,
        mageUrl,
        setPaymentMethod,
        totals,
        additionalValidators,
    ) {
        'use strict';

        var kekspayPopupOptions = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'KeksPay',
            buttons: [{
                text: $t('Cancel'),
                class: 'action close-popup wide',
                click: function () {
                    // do nothing
                }
            }],
            clickableOverlay: false,
            opened: function ($Event) {
                $('.modal-header button.action-close', $Event.srcElement).hide();
            },
            keyEventHandlers: {
                escapeKey: function () {
                    return;
                }
            }
        };

        return Component.extend({
            defaults: {
                template: 'Perpetuum_KeksPay/payments/kekspay'
            },

            isDesktop: kekspay.platformHasNativeSupport() === null,
            placeOrderHandler: null,
            isVisible: ko.observable(true),
            isLoading: false,
            isPolling: false,
            isAuthFailed: false,
            showButton: ko.observable(false),
            kekspayIsNative: false,
            kekspayIsScanned: ko.observable(false),
            kekspayPopupMessage: ko.observable(),
            kekspayPopupProgressMessage: ko.observable(),
            kekspayPopupCloseButtonEnabled: ko.observable(false),
            kekspayWarningMessage: ko.observable(),
            kekspayCurrentStatus: null,
            pollingTimer: null,
            staticUrl: window.checkoutConfig.staticBaseUrl,
            deepLinkButtonClicked: ko.observable(false),

            getKeksPayPopupOptions: function () {
                var self = this;

                return {
                    modalClass: 'kekspay-popup-window',
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: $t('Payment via KEKS Pay'),
                    buttons: [/*{
                        text: $t('Cancel'),
                        class: 'action close-popup wide',
                        click: function () {
                        }
                    }*/],
                    clickableOverlay: false,
                    opened: function ($Event) {
                        $('.modal-header button.action-close', $Event.srcElement).hide();
                    },
                    keyEventHandlers: {
                        escapeKey: function () {
                            return;
                        }
                    }
                };
            },

            /**
             * @param {Object} handler
             */
            setPlaceOrderHandler: function (handler) {
                this.placeOrderHandler = handler;
            },

            /**
             * @param {Object} handler
             */
            setValidateHandler: function (handler) {
                this.validateHandler = handler;
            },

            selectPaymentMethod: function () {
                this.isLoading = false;
                return this._super();
            },

            /**
             * Get value of instruction field.
             * @returns {String}
             */
            getInstructions: function () {
                if (window.checkoutConfig &&
                    window.checkoutConfig.payment &&
                    window.checkoutConfig.payment.instructions &&
                    window.checkoutConfig.payment.instructions[this.item.method] !== undefined) {
                    return window.checkoutConfig.payment.instructions[this.item.method];
                }

                return $t('Fastest and free of charge via the KEKS Pay application!');
            },

            getLogoUrl: function () {
                return config.getConfig().kekspay_logo;
            },

            getLogoVUrl: function () {
                return config.getConfig().kekspay_logo_v;
            },

            getPopupLogoUrl: function () {
                return config.getConfig().kekspay_logo;
            },

            getAppStoreLogoSvgUrl: function() {
                return config.getConfig().appstore_logo_svg;
            },

            getPlayStoreLogoSvgUrl: function() {
                return config.getConfig().playstore_logo_svg;
            },

            getAppStoreAppUrl: function() {
                return config.getConfig().appstore_app_link;
            },

            getPlayStoreAppUrl: function() {
                return config.getConfig().playstore_app_link;
            },

            getPlusIcon: function() {
                return config.getConfig().plus_icon;
            },

            initialize: function () {
                this._super();

                if (config.getConfig().active) {
                    this.showButton(true);
                } else {
                    this.showButton(false);
                }
            },

            authorize: function () {
                var self = this;

                if (!additionalValidators.validate()) {
                    return;
                }

                self.showButton(false);
                self.kekspayPopupCloseButtonEnabled(true);

                var popupOptions = self.getKeksPayPopupOptions();
                popupOptions.title = "<img src='" + self.getPopupLogoUrl() + "' class='kekspay-popup-logo'/>";

                $("#kekspay-checkout-popup").modal(popupOptions).modal('openModal');

                const grandTotal = totals.getSegment('grand_total').value;
                const totalAmount = grandTotal.toFixed(2);

                if (self.deepLinkButtonClicked()) {
                    kekspay.checkout_native(totalAmount, this.messageContainer, this.getData());
                }

                if (self.isDesktop) {
                    // set payment method - fix for order creation on advice
                    setPaymentMethod(this.messageContainer, this.getData()).done(function () {console.log('payment set')});
                    var qrCode = kekspay.checkout(totalAmount);
                }

                if (qrCode) {
                    $('#kekspay-checkout-qr').html(qrCode);
                } else {
                    self.kekspayIsScanned(true);
                    self.kekspayPopupCloseButtonEnabled(true);
                    // self.kekspayPopupMessage($t("Please authorize the payment in the KEKS Pay application ... "));
                }

                self.waitForPayment();
            },

            finalize: function () {
                var self = this;

                $("#kekspay-checkout-popup").modal('closeModal');

                self.isAuthFailed = false;
                self.kekspayIsScanned(false);
                self.kekspayCurrentStatus = null;

                if (self.pollingTimer) {
                    clearTimeout(self.pollingTimer);
                    self.pollingTimer = null;
                }

                const identityParam = 'identity=' + config.getConfig().identity_hash;

                self.isPolling = false;

                customerData.invalidate(['cart']);
                $.mage.redirect(mageUrl.build('kekspay/checkout/success?' + identityParam));
            },

            cancel: function () {
                var self = this;

                window.location.reload();
            },

            deepLinkClickButton: function () {
                let self = this;

                self.deepLinkButtonClicked(true);
                this.authorize();
            },

            waitForPayment: function () {
                var self = this;

                // request is still running
                if (self.isPolling) {
                    self.pollingTimer = setTimeout(function () {
                        self.waitForPayment()
                    }, 1000);
                    return;
                }

                self.isPolling = true;

                $.get('/kekspay/checkout/poll?order_id=' + config.getConfig().order_id, function (data) {
                    if (data.status !== undefined) {
                        const status = parseInt(data.status);
                        const message = data.message;

                        if (self.kekspayCurrentStatus !== status) {
                            // authorized / captured
                            if (status === 0) {
                                clearTimeout(self.pollingTimer);
                                self.kekspayPopupMessage($t('Payment authorization successful, finalizing ... '));
                                return self.finalize();
                            } else if (status === 404 && message === 'Quote no match') { // check if quote mismatch or not found
                                clearTimeout(self.pollingTimer);
                                self.kekspayPopupMessage($t('Quote mismatch or expired'));
                                return self.failPayment();
                            } else if (status === -6) { // -6 code means failed
                                clearTimeout(self.pollingTimer);
                                self.kekspayPopupMessage($t('Payment Failed'));
                                return self.failPayment();
                            } else if (status === -100) { // allows custom message from backend with redirect to checkout start
                                clearTimeout(self.pollingTimer);
                                self.kekspayIsScanned(true);
                                self.kekspayPopupMessage($t(message));
                                return self.failPayment('redirect');
                            }
                        }
                    }

                    self.isPolling = false;
                    self.pollingTimer = setTimeout(function () {
                        self.waitForPayment()
                    }, 1000);
                }).fail(function () {
                    self.isPolling = false;
                    self.pollingTimer = setTimeout(function () {
                        self.waitForPayment()
                    }, 1000);
                });
            },

            failPayment: function (actionType = 'reload') {
                var self = this;

                self.kekspayPopupCloseButtonEnabled(false);
                self.showButton(false);

                if (actionType === 'redirect') {
                    setTimeout(
                        function () {
                            window.location.replace("/checkout");
                        },
                        5000
                    );
                } else {
                    $.mage.redirect(null, 'reload', 5000, false);
                }
            }
        });
    }
);
