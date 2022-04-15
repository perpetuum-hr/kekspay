<?php

namespace Perpetuum\KeksPay\Model\Payment;

use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Perpetuum\KeksPay\Api\Data\Payment\RefundRequestInterface;
use Perpetuum\KeksPay\Model\HashGenerator;

class RefundRequest implements RefundRequestInterface
{
    /**
     * @var DataObject
     */
    private $data;
    /**
     * @var HashGenerator
     */
    private $hashGenerator;

    /**
     * RefundRequest constructor.
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        HashGenerator $hashGenerator
    ) {
        $this->data = $dataObjectFactory->create();
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @inheritDoc
     */
    public function setBillId(string $billId): RefundRequestInterface
    {
        $this->data->setData(self::BILL_ID, $billId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setKeksId(string $keksId): RefundRequestInterface
    {
        $this->data->setData(self::KEKS_ID, $keksId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTid(string $tid): RefundRequestInterface
    {
        $this->data->setData(self::TID, $tid);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAmount(?string $amount): RefundRequestInterface
    {
        ($amount === null)
            ? $this->data->unsetData(self::AMOUNT)
            : $this->data->setData(self::AMOUNT, $amount);

        return $this;
    }

    private function setHash(string $hash): RefundRequestInterface
    {
        $this->data->setData(self::HASH, $hash);
        return $this;
    }

    private function setEpochTime(int $epochTime): RefundRequestInterface
    {
        $this->data->setData(self::EPOCHTIME, $epochTime);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(): array
    {
        // generate and set hash and epochtime
        $epochTime = time();
        $hash = $this->hashGenerator->generateHashForRefundRequest($epochTime, $this->data);
        $this->setHash($hash);
        $this->setEpochTime($epochTime);

        return $this->data->toArray();
    }
}
