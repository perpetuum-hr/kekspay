define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'Perpetuum_KeksPay/js/model/config'
    ],
    function (
        Component,
        rendererList,
        config
    ) {
        'use strict';

        const configuration = config.getConfig();

        if (configuration !== null && configuration.active === true) {
            rendererList.push(
                {
                    type: 'kekspay', // payment method code
                    component: 'Perpetuum_KeksPay/js/view/kekspay'
                }
            );
        } else {
            console.log('KeksPay payment no available.');
        }

        // Add view logic here if needed
        return Component.extend({});
    }
);
