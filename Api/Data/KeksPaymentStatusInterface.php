<?php declare(strict_types=1);

namespace Perpetuum\KeksPay\Api\Data;

interface KeksPaymentStatusInterface
{
    const ID = 'order_id';
    const QUOTE_ID = 'quote_id';
    const BILL_ID = 'bill_id';
    const KEKS_ID = 'keks_id';
    const TID = 'tid';
    const STATUS = 'status';
    const MESSAGE = 'message';
    const RESPONSE = 'response';
    const AMOUNT = 'amount';
    const IS_ORDER_CREATED = 'is_order_created';
    const ADDITIONAL_DATA = 'additional_data';
    const IDENTITY_HASH = 'identity_hash';
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
     * @return int|null
     */
    public function getQuoteId(): ?int;

    /**
     * @param int|null $quoteId
     * @return KeksPaymentStatusInterface
     */
    public function setQuoteId(?int $quoteId): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getBillId(): ?string;

    /**
     * @param string|null $billId
     * @return KeksPaymentStatusInterface
     */
    public function setBillId(?string $billId): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getKeksId(): ?string;

    /**
     * @param string|null $keksId
     * @return KeksPaymentStatusInterface
     */
    public function setKeksId(?string $keksId): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getTid(): ?string;

    /**
     * @param string|null $tid
     * @return KeksPaymentStatusInterface
     */
    public function setTid(?string $tid): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getCid(): ?string;

    /**
     * @param string|null $cid
     * @return KeksPaymentStatusInterface
     */
    public function setCid(?string $cid): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * @param string|null $message
     * @return KeksPaymentStatusInterface
     */
    public function setMessage(?string $message): KeksPaymentStatusInterface;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @param int|null $status
     * @return KeksPaymentStatusInterface
     */
    public function setStatus(?int $status): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getResponse(): ?string;

    /**
     * @param string|null $response
     * @return KeksPaymentStatusInterface
     */
    public function setResponse(?string $response): KeksPaymentStatusInterface;

    /**
     * @return float|null
     */
    public function getAmount(): ?float;

    /**
     * @return bool|null
     */
    public function getIsOrderCreated(): ?bool;

    /**
     * @param bool|null $isOrderCreated
     * @return KeksPaymentStatusInterface
     */
    public function setIsOrderCreated(?bool $isOrderCreated): KeksPaymentStatusInterface;

    /**
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function setAdditionalDataByKey(string $key, $value);

    /**
     * @param string $key
     * @return mixed
     */
    public function getAdditionalDataByKey(string $key);

    /**
     * @param string|null $identityHash
     * @return KeksPaymentStatusInterface
     */
    public function setIdentityHash(?string $identityHash): KeksPaymentStatusInterface;

    /**
     * @return string|null
     */
    public function getIdentityHash(): ?string;

    /**
     * @param string|null $additionalData
     * @return mixed
     */
    public function setAdditionalData(?string $additionalData);

    /**
     * @return string|null
     */
    public function getAdditionalData(): ?string;

    /**
     * @param float|null $amount
     * @return KeksPaymentStatusInterface
     */
    public function setAmount(?float $amount): KeksPaymentStatusInterface;

    /**
     * @return mixed
     */
    public function getCreatedAt();
}
