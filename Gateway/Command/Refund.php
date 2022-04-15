<?php

namespace Perpetuum\KeksPay\Gateway\Command;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Phrase;
use Magento\Payment\Gateway\Command;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Perpetuum\KeksPay\Api\Data\Payment\RefundRequestInterfaceFactory;
use Perpetuum\KeksPay\Api\Data\PaymentStatusCode;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;
use Perpetuum\KeksPay\Api\Payment\PaymentInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Refund implements CommandInterface
{
    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    /**
     * @var PaymentInterface
     */
    private $payment;

    /**
     * @var RefundRequestInterfaceFactory
     */
    private $refundRequestFactory;

    public function __construct(
        RefundRequestInterfaceFactory $refundRequestFactory,
        PaymentInterface $payment,
        KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository
    ) {
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
        $this->payment = $payment;
        $this->refundRequestFactory = $refundRequestFactory;
    }

    /**
     * @param array $commandSubject
     * @return Command\ResultInterface|void|null
     * @throws CommandException
     * @throws AlreadyExistsException
     */
    public function execute(array $commandSubject)
    {
        /**
         * @var InfoInterface $payment
         */
        $payment = $commandSubject['payment']->getPayment();
        $amount = $commandSubject['amount'];
        /**
         * @var OrderInterface $order
         */
        $order = $payment->getOrder();

        $status = $this->keksPaymentStatusRepository->getById((int) $order->getIncrementId());

        if ($status === null) {
            throw new CommandException(
                new Phrase('Payment status not found.')
            );
        }

        // check if its paid
        if ($status->getStatus() !== PaymentStatusCode::OK
            && $status->getMessage() !== PaymentStatusCode::PAID_MESSAGE) {
            throw new CommandException(
                new Phrase('Expected payment status: ' . PaymentStatusCode::OK
                    . ', got ' . $status->getStatus())
            );
        }

        $refundRequest = $this->refundRequestFactory->create();

        $refundRequest
            ->setTid($status->getTid())
            ->setKeksId($status->getKeksId())
            ->setBillId($status->getBillId())
            ->setAmount((string) $amount)
            ->createRequest();

        $refundResult = $this->payment->refund($refundRequest);

        if (!in_array($refundResult->getHttpStatus(), [200, 204])) {
            throw new CommandException(
                new Phrase('Payment refund failed. HTTP status: %1', [$refundResult->getHttpStatus()])
            );
        }

        $receivedData = $refundResult->getPayload();

        $rcvStatus = null;

        if ($receivedData->hasData('status')) {
            $rcvStatus = (int) $receivedData->getData('status');
        }

        $rcvMessage = (string) $receivedData->getData('message');

        if ($rcvStatus === null || $rcvStatus !== PaymentStatusCode::OK) {
            throw new CommandException(
                new Phrase('Payment refund failed. Message: %1', [$rcvMessage])
            );
        }

        $status->setMessage($rcvMessage);
        $status->setStatus($rcvStatus);
        $this->keksPaymentStatusRepository->save($status);

        $payment->setTransactionId($receivedData->getData('keks_refund_id'))->setIsTransactionClosed(1);

        return null;
    }
}
