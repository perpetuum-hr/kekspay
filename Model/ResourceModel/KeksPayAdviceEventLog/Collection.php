<?php

namespace Perpetuum\KeksPay\Model\ResourceModel\KeksPayAdviceEventLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @SuppressWarnings("camelCase")
 */
class Collection extends AbstractCollection
{
    // phpcs:ignore
    protected $_idFieldName = 'entity_id';

    // phpcs:ignore
    protected function _construct()
    {
        $this->_init(
            \Perpetuum\KeksPay\Model\KeksPayAdviceEventLog::class,
            \Perpetuum\KeksPay\Model\ResourceModel\KeksPayAdviceEventLog::class
        );
    }
}
