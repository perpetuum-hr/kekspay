<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Gateway\Command;

use Magento\Directory\Model\Currency;
use Magento\Framework\Phrase;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\Payment;
use Perpetuum\KeksPay\Api\Data\PaymentStatusCode;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Capture implements CommandInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * Capture constructor.
     * @param LoggerInterface $logger
     * @param KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository
     * @param Currency $currency
     */
    public function __construct(
        LoggerInterface $logger,
        KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository,
        Currency $currency
    ) {
        $this->logger = $logger;
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
        $this->currency = $currency;
    }

    public function execute(array $commandSubject)
    {
        /**
         * @var Payment $payment
         */
        $payment = $commandSubject['payment']->getPayment();

        /**
         * @var OrderInterface $order
         */
        $order = $payment->getOrder();

        $status = $this->keksPaymentStatusRepository->getByQuoteId((int) $order->getQuoteId());

        if ($status === null) {
            throw new CommandException(
                new Phrase('Payment status not found.')
            );
        }

        if ($status->getStatus() !== PaymentStatusCode::OK || !$status->getKeksId()) {
            throw new CommandException(
                new Phrase("Payment capture failed. Not Paid")
            );
        }

        $grandTotal = $this->formatAmount($order->getGrandTotal());

        $statusPaidAmount = $this->formatAmount($status->getAmount());

        if ($grandTotal !== $statusPaidAmount) {
            $this->logger->critical("Payment amount doesn't match - ORDER's QUOTE ID: {$order->getQuoteId()}");
            // Reset status, amount, message values for giving user a chance to repay
            $status->setStatus(null);
            $status->setAmount(null);
            $status->setMessage(null);
            $status->setKeksId(null);
            $this->keksPaymentStatusRepository->save($status);

            throw new CommandException(
                new Phrase("Payment capture failed. Amount Paid Doesn't Match")
            );
        }

        $payment->setTransactionId($status->getKeksId())->setIsTransactionClosed(1);

        return null;
    }

    /**
     * @param $amount
     * @return string
     */
    private function formatAmount($amount): string
    {
        return $this->currency->format($amount, ['symbol' => ''], false, false);
    }
}
