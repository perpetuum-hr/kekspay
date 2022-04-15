<?php

namespace Perpetuum\KeksPay\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @SuppressWarnings("camelCase")
 */
class KeksPayAdviceEventLog extends AbstractDb
{
    /**
     * @inheritDoc
     */
    // phpcs:ignore
    protected function _construct()
    {
        $this->_init('kekspay_advice_event_log', 'entity_id');
    }
}
