<?php

namespace Perpetuum\KeksPay\Model\Transaction;

use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Perpetuum\KeksPay\Api\Data\Transaction\AllTransactionInfoRequestInterface;

class AllTransactionInfoRequest implements AllTransactionInfoRequestInterface
{
    /**
     * @var DataObject
     */
    private $data;

    /**
     * AllTransactionInfoRequest constructor.
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
    public function setTid(string $tid): AllTransactionInfoRequestInterface
    {
        $this->data->setData(self::TID, $tid);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBillIdFrom(?string $billIdFrom): AllTransactionInfoRequestInterface
    {
        ($billIdFrom === null)
            ? $this->data->unsetData(self::BILL_ID_FROM)
            : $this->data->setData(self::BILL_ID_FROM, $billIdFrom);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBillIdTo(?string $billIdTo): AllTransactionInfoRequestInterface
    {
        ($billIdTo === null)
            ? $this->data->unsetData(self::BILL_ID_TO)
            :$this->data->setData(self::BILL_ID_TO, $billIdTo);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setKeksIdFrom(?string $keksIdFrom): AllTransactionInfoRequestInterface
    {
        ($keksIdFrom === null)
            ? $this->data->unsetData(self::KEKS_ID_FROM)
            : $this->data->setData(self::KEKS_ID_FROM, $keksIdFrom);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setKeksIdTo(?string $keksIdTo): AllTransactionInfoRequestInterface
    {
        ($keksIdTo === null)
            ? $this->data->unsetData(self::KEKS_ID_TO)
            : $this->data->setData(self::KEKS_ID_TO, $keksIdTo);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTimestampFrom(?string $timestampFrom): AllTransactionInfoRequestInterface
    {
        ($timestampFrom === null)
            ? $this->data->unsetData(self::TIMESTAMP_FROM)
            : $this->data->setData(self::TIMESTAMP_FROM, $timestampFrom);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTimestampTo(?string $timestampTo): AllTransactionInfoRequestInterface
    {
        ($timestampTo === null)
            ? $this->data->unsetData(self::TIMESTAMP_TO)
            : $this->data->setData(self::TIMESTAMP_TO, $timestampTo);

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
