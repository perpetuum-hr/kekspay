<?php

namespace Perpetuum\KeksPay\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @SuppressWarnings("camelCase")
 */
class KeksPaymentStatus extends AbstractDb
{
    //phpcs:ignore
    protected $_isPkAutoIncrement = false;

    /**
     * @inheritDoc
     */
    // phpcs:ignore
    protected function _construct()
    {
        $this->_init('kekspay_payment_status', 'order_id');
    }
}
