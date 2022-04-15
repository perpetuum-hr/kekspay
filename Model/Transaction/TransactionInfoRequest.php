<?php

namespace Perpetuum\KeksPay\Model\Transaction;

use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Perpetuum\KeksPay\Api\Data\Transaction\TransactionInfoRequestInterface;

class TransactionInfoRequest implements TransactionInfoRequestInterface
{
    /**
     * @var DataObject
     */
    private $data;

    /**
     * TransactionInfoRequest constructor.
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory
    ) {
        $this->data = $dataObjectFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function setBillId(string $billId): TransactionInfoRequestInterface
    {
        $this->data->setData(self::BILL_ID, $billId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setKeksId(string $keksId): TransactionInfoRequestInterface
    {
        $this->data->setData(self::KEKS_ID, $keksId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTid(string $tid): TransactionInfoRequestInterface
    {
        $this->data->setData(self::TID, $tid);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(): array
    {
        return $this->data->toArray();
    }
}
