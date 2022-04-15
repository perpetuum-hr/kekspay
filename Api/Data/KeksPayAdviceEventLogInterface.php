<?php

namespace Perpetuum\KeksPay\Api\Data;

interface KeksPayAdviceEventLogInterface
{
    const BILL_ID = 'bill_id';
    const KEKS_ID = 'keks_id';
    const TID = 'tid';
    const STORE = 'store';
    const AMOUNT = 'amount';
    const STATUS = 'status';
    const MESSAGE = 'message';
    const RESPONSE = 'response';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $entityId
     */
    public function setId($entityId);

    /**
     * @return string|null
     */
    public function getBillId(): ?string;

    /**
     * @param string $billId
     * @return KeksPayAdviceEventLogInterface
     */
    public function setBillId(string $billId): KeksPayAdviceEventLogInterface;

    /**
     * @return string|null
     */
    public function getKeksId(): ?string;

    /**
     * @param string $keksId
     * @return KeksPayAdviceEventLogInterface
     */
    public function setKeksId(string $keksId): KeksPayAdviceEventLogInterface;

    /**
     * @return string|null
     */
    public function getTid(): ?string;

    /**
     * @param string $tid
     * @return KeksPayAdviceEventLogInterface
     */
    public function setTid(string $tid): KeksPayAdviceEventLogInterface;

    /**
     * @return string|null
     */
    public function getStore(): ?string;

    /**
     * @param string $store
     * @return KeksPayAdviceEventLogInterface
     */
    public function setStore(string $store): KeksPayAdviceEventLogInterface;

    /**
     * @return float|null
     */
    public function getAmount(): ?float;

    /**
     * @param float $amount
     * @return KeksPayAdviceEventLogInterface
     */
    public function setAmount(float $amount): KeksPayAdviceEventLogInterface;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @param int $status
     * @return KeksPaymentStatusInterface
     */
    public function setStatus(int $status): KeksPayAdviceEventLogInterface;

    /**
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * @param string $message
     * @return KeksPayAdviceEventLogInterface
     */
    public function setMessage(string $message): KeksPayAdviceEventLogInterface;

    /**
     * @return string
     */
    public function getResponse(): ?string;

    /**
     * @param string $response
     * @return KeksPayAdviceEventLogInterface
     */
    public function setResponse(string $response): KeksPayAdviceEventLogInterface;

    /**
     * @return mixed
     */
    public function getCreatedAt();
}
