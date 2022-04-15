<?php

namespace Perpetuum\KeksPay\Model\Advice;

use Perpetuum\KeksPay\Api\Data\Advice\AdvicePayloadInterface;
use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterface;
use Perpetuum\KeksPay\Model\KeksPaymentStatus;

class AdvicePayload implements AdvicePayloadInterface
{
    private $data = [];

    /**
     * AdvicePayload constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data[self::BILL_ID] = $data[self::BILL_ID];
        $this->data[self::KEKS_ID] = $data[self::KEKS_ID];
        $this->data[self::TID] = $data[self::TID];
        $this->data[self::STORE] = $data[self::STORE];
        $this->data[self::AMOUNT] = $data[self::AMOUNT];
        $this->data[self::STATUS] = $data[self::STATUS];
        if (isset($data[self::MESSAGE])) {
            $this->data[self::MESSAGE] = $data[self::MESSAGE];
        }
    }

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->data[self::BILL_ID];
    }

    /**
     * @return string
     */
    public function getKeksId(): string
    {
        return $this->data[self::KEKS_ID];
    }

    /**
     * @return string
     */
    public function getTid(): string
    {
        return $this->data[self::TID];
    }

    /**
     * @return string
     */
    public function getStore(): string
    {
        return $this->data[self::STORE];
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->data[self::AMOUNT];
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->data[self::STATUS];
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (!isset($this->data[self::MESSAGE]) || empty($this->data[self::MESSAGE])) {
            return '';
        }

        return $this->data[self::MESSAGE];
    }

    /**
     * This method should return array data for KeksPaymentStatus
     * @see KeksPaymentStatusInterface
     * @see KeksPaymentStatus
     * @return array
     */
    public function toModelArray(): array
    {
        return [
            KeksPaymentStatusInterface::BILL_ID => $this->getBillId(),
            KeksPaymentStatusInterface::KEKS_ID => $this->getKeksId(),
            KeksPaymentStatusInterface::TID => $this->getTid(),
            KeksPaymentStatusInterface::MESSAGE => $this->getMessage(),
            KeksPaymentStatusInterface::STATUS => $this->getStatus(),
            KeksPaymentStatusInterface::AMOUNT => $this->getAmount()
        ];
    }
}
