<?php

namespace Perpetuum\KeksPay\Api\Data\Transaction;

interface AllTransactionInfoRequestInterface
{
    const TID = 'tid';
    const BILL_ID_FROM = 'bill_id_from';
    const BILL_ID_TO = 'bill_id_to';
    const KEKS_ID_FROM = 'keks_id_from';
    const KEKS_ID_TO = 'keks_id_to';
    const TIMESTAMP_FROM = 'timestamp_from';
    const TIMESTAMP_TO = 'timestamp_to';

    /**
     * @param string $tid
     * @return AllTransactionInfoRequestInterface
     */
    public function setTid(string $tid): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $billIdFrom
     * @return AllTransactionInfoRequestInterface
     */
    public function setBillIdFrom(?string $billIdFrom): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $billIdTo
     * @return AllTransactionInfoRequestInterface
     */
    public function setBillIdTo(?string $billIdTo): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $keksIdFrom
     * @return AllTransactionInfoRequestInterface
     */
    public function setKeksIdFrom(?string $keksIdFrom): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $keksIdTo
     * @return AllTransactionInfoRequestInterface
     */
    public function setKeksIdTo(?string $keksIdTo): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $timestampFrom
     * @return AllTransactionInfoRequestInterface
     */
    public function setTimestampFrom(?string $timestampFrom): AllTransactionInfoRequestInterface;

    /**
     * @param string|null $timestampTo
     * @return AllTransactionInfoRequestInterface
     */
    public function setTimestampTo(?string $timestampTo): AllTransactionInfoRequestInterface;

    /**
     * @return array
     */
    public function createRequest(): array;
}
