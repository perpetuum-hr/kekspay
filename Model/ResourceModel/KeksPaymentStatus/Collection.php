<?php

namespace Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @SuppressWarnings("camelCase")
 */
class Collection extends AbstractCollection
{
    // phpcs:ignore
    protected $_idFieldName = 'order_id';

    // phpcs:ignore
    protected function _construct()
    {
        $this->_init(
            \Perpetuum\KeksPay\Model\KeksPaymentStatus::class,
            \Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatus::class
        );
    }
}
