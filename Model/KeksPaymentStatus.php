<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Model;

use Magento\Framework\Model\AbstractModel;
use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterface;

/**
 * @SuppressWarnings(CamelCase)
 */
class KeksPaymentStatus extends AbstractModel implements KeksPaymentStatusInterface
{
    // phpcs:ignore
    protected function _construct()
    {
        $this->_init(ResourceModel\KeksPaymentStatus::class);
    }

    /**
     * @inheritDoc
     */
    public function getQuoteId(): ?int
    {
        if ($this->checkNullData(self::QUOTE_ID)) {
            return null;
        }
        return (int) $this->_getData(self::QUOTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQuoteId(?int $quoteId): KeksPaymentStatusInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
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
    public function setBillId(?string $billId): KeksPaymentStatusInterface
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
    public function setKeksId(?string $keksId): KeksPaymentStatusInterface
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
    public function setTid(?string $tid): KeksPaymentStatusInterface
    {
        return $this->setData(self::TID, $tid);
    }

    /**
     * @inheritDoc
     */
    public function getCid(): ?string
    {
        return $this->_getData(self::TID);
    }

    /**
     * @inheritDoc
     */
    public function setCid(?string $tid): KeksPaymentStatusInterface
    {
        return $this->setData(self::TID, $tid);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?int
    {
        if ($this->checkNullData(self::STATUS)) {
            return null;
        }
        return (int) $this->_getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(?int $status): KeksPaymentStatusInterface
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
    public function setMessage(?string $message): KeksPaymentStatusInterface
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
    public function setResponse(?string $response): KeksPaymentStatusInterface
    {
        return $this->setData(self::RESPONSE, $response);
    }

    /**
     * @inheritDoc
     */
    public function getAmount(): ?float
    {
        if ($this->checkNullData(self::AMOUNT)) {
            return null;
        }
        return (float) $this->_getData(self::AMOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setAmount(?float $amount): KeksPaymentStatusInterface
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * @inheritDoc
     */
    public function getIsOrderCreated(): ?bool
    {
        $isOrderCreated = $this->_getData(self::IS_ORDER_CREATED);
        return $isOrderCreated === null ? null : (bool) $isOrderCreated;
    }

    /**
     * @inheritDoc
     */
    public function setIsOrderCreated(?bool $isOrderCreated): KeksPaymentStatusInterface
    {
        return $this->setData(self::IS_ORDER_CREATED, $isOrderCreated);
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalDataByKey(string $key, $value)
    {
        $jsonAsStr = $this->getAdditionalData();
        $json = !empty($jsonAsStr) ? json_decode($jsonAsStr, true) : [];
        $json[$key] = $value;

        return $this->setData(self::ADDITIONAL_DATA, json_encode($json));
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalDataByKey(string $key)
    {
        $jsonAsStr = $this->getAdditionalData();
        $json = json_decode($jsonAsStr, true);
        return isset($json[$key]) ? $json[$key] : null;
    }

    /**
     * @inheritDoc
     */
    public function setAdditionalData(?string $additionalData)
    {
        return $this->setData(self::ADDITIONAL_DATA, $additionalData);
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalData(): ?string
    {
        return $this->_getData(self::ADDITIONAL_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setIdentityHash(?string $identityHash): KeksPaymentStatusInterface
    {
        return $this->setData(self::IDENTITY_HASH, $identityHash);
    }

    /**
     * @inheritDoc
     */
    public function getIdentityHash(): ?string
    {
        return $this->_getData(self::IDENTITY_HASH);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * @param $key
     * @return bool
     */
    private function checkNullData($key): bool
    {
        return $this->_getData($key) === null;
    }
}
