<?php

namespace Perpetuum\KeksPay\Model;

use Magento\Framework\Model\AbstractModel;
use Perpetuum\KeksPay\Api\Data\KeksPayAdviceEventLogInterface;

/**
 * @SuppressWarnings("camelCase")
 */
class KeksPayAdviceEventLog extends AbstractModel implements KeksPayAdviceEventLogInterface
{
    // phpcs:ignore
    protected function _construct()
    {
        $this->_init(ResourceModel\KeksPayAdviceEventLog::class);
    }

    /**
     * @inheritDoc
     */
    public function getBillId(): ?string
    {
        return $this->_getData(self::BILL_ID);
    }

    /**
     * @inheritDoc
     */
    public function setBillId(string $billId): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::BILL_ID, $billId);
    }

    /**
     * @inheritDoc
     */
    public function getKeksId(): ?string
    {
        return $this->_getData(self::KEKS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setKeksId(string $keksId): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::KEKS_ID, $keksId);
    }

    /**
     * @inheritDoc
     */
    public function getTid(): ?string
    {
        return $this->_getData(self::TID);
    }

    /**
     * @inheritDoc
     */
    public function setTid(string $tid): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::TID, $tid);
    }

    /**
     * @inheritDoc
     */
    public function getStore(): ?string
    {
        return $this->_getData(self::STORE);
    }

    /**
     * @inheritDoc
     */
    public function setStore(string $store): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::STORE, $store);
    }

    /**
     * @inheritDoc
     */
    public function getAmount(): ?float
    {
        return $this->_getData(self::AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setAmount(float $amount): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?int
    {
        return $this->_getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->_getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): ?string
    {
        return $this->_getData(self::RESPONSE);
    }

    /**
     * @inheritDoc
     */
    public function setResponse(string $response): KeksPayAdviceEventLogInterface
    {
        return $this->setData(self::RESPONSE, $response);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }
}
