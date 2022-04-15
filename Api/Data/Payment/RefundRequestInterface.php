<?php

namespace Perpetuum\KeksPay\Api\Data\Payment;

interface RefundRequestInterface
{
    const BILL_ID = 'bill_id';
    const KEKS_ID = 'keks_id';
    const TID = 'tid';
    const AMOUNT = 'amount';
    const HASH = 'hash';
    const EPOCHTIME = 'epochtime';

    /**
     * @param string $billId
     * @return RefundRequestInterface
     */
    public function setBillId(string $billId): RefundRequestInterface;

    /**
     * @param string $keksId
     * @return RefundRequestInterface
     */
    public function setKeksId(string $keksId): RefundRequestInterface;

    /**
     * @param string $tid
     * @return RefundRequestInterface
     */
    public function setTid(string $tid): RefundRequestInterface;

    /**
     * @param string|null $amount
     * @return RefundRequestInterface
     */
    public function setAmount(?string $amount): RefundRequestInterface;

    /**
     * @return array
     */
    public function createRequest(): array;
}
