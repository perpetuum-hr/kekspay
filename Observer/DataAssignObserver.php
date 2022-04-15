<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    const DEVICE = 'device';

    private $additionalInformationList = [
        self::DEVICE
    ];

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    public function __construct(KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository)
    {
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
    }

    /**
     * @param Observer $observer
     * @throws AlreadyExistsException
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_array($additionalData)) {
            return;
        }

        $paymentInfo = $this->readPaymentModelArgument($observer);
        $quote = $paymentInfo->getQuote();
        $keksPaymentStatus = $this->keksPaymentStatusRepository->getByQuoteId((int) $quote->getId());

        foreach ($this->additionalInformationList as $additionalInformationKey) {
            if (isset($additionalData[$additionalInformationKey])) {
                if ($additionalInformationKey === self::DEVICE) {
                    $keksPaymentStatus->setAdditionalDataByKey(self::DEVICE, $additionalData[self::DEVICE]);
                    // phpcs:ignore
                    $this->keksPaymentStatusRepository->save($keksPaymentStatus);
                }

                $paymentInfo->setAdditionalInformation(
                    $additionalInformationKey,
                    $additionalData[$additionalInformationKey]
                );
            }
        }
    }
}
