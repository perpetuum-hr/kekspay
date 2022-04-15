<?php

namespace Perpetuum\KeksPay\Api\Payment;

use Perpetuum\KeksPay\Api\Data\Payment\RefundRequestInterface;
use Perpetuum\KeksPay\Api\Data\Transaction\AllTransactionInfoRequestInterface;
use Perpetuum\KeksPay\Api\Data\Transaction\TransactionInfoRequestInterface;
use Perpetuum\KeksPay\Model\Client\Response\ResponseInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface PaymentInterface
{
    /**
     * @param RefundRequestInterface $refundRequest
     * @return ResponseInterface
     */
    public function refund(RefundRequestInterface $refundRequest): ResponseInterface;

    /**
     * @param TransactionInfoRequestInterface $transactionInfoRequest
     * @return ResponseInterface
     */
    public function transactionInfo(TransactionInfoRequestInterface $transactionInfoRequest): ResponseInterface;

    /**
     * @param AllTransactionInfoRequestInterface $transactionInfoRequest
     * @return ResponseInterface
     */
    public function allTransactionInfo(AllTransactionInfoRequestInterface $transactionInfoRequest): ResponseInterface;
}
