<?php

namespace Perpetuum\KeksPay\Api\Data\Transaction;

interface TransactionInfoRequestInterface
{
    const BILL_ID = 'bill_id';
    const KEKS_ID = 'keks_id';
    const TID = 'tid';

    /**
     * @param string $billId
     * @return TransactionInfoRequestInterface
     */
    public function setBillId(string $billId): TransactionInfoRequestInterface;

    /**
     * @param string $keksId
     * @return TransactionInfoRequestInterface
     */
    public function setKeksId(string $keksId): TransactionInfoRequestInterface;

    /**
     * @param string $tid
     * @return TransactionInfoRequestInterface
     */
    public function setTid(string $tid): TransactionInfoRequestInterface;

    /**
     * @return array
     */
    public function createRequest(): array;
}
