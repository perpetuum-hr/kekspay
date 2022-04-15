<?php

namespace Perpetuum\KeksPay\Controller\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Perpetuum\KeksPay\Api\Data\PaymentStatusCode;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;

/**
 * @SuppressWarnings(LongVariable)
 */
class Poll extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;
    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    public const STATUS_PROPERTY = 'status';
    public const MESSAGE_PROPERTY = 'message';

    /**
     * @var Session
     */
    private $session;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        DataObjectFactory $dataObjectFactory,
        KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository,
        Session $session
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
        $this->session = $session;
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        if ($orderId) {
            $paymentStatus = $this->keksPaymentStatusRepository->getById((int) $orderId);

            // if keks_id exists that means it was received from advice
            if ($paymentStatus->getKeksId() !== null
                && $paymentStatus->getStatus() === PaymentStatusCode::OK
                && $paymentStatus->getIsOrderCreated()) {
                return $this->successResponse(PaymentStatusCode::PAID_MESSAGE);
            }

            if (($paymentStatus->getKeksId() != null
                && $paymentStatus->getStatus() === PaymentStatusCode::FAILED)
                || $paymentStatus->getIsOrderCreated() === false) {
                return $this->failedResponse(PaymentStatusCode::FAILED_MESSAGE);
            }
        }

        return $this->notFoundResponse('N/A');
    }

    /**
     * @param string $message
     * @return Json
     */
    private function successResponse(string $message): Json
    {
        return $this->getJsonResponse()->setJsonData(
            $this->jsonResponsePayload(PaymentStatusCode::OK, $message)
        );
    }

    /**
     * @param string $message
     * @return Json
     */
    private function failedResponse(string $message): Json
    {
        return $this->getJsonResponse()->setJsonData(
            $this->jsonResponsePayload(PaymentStatusCode::FAILED, $message)
        );
    }

    /**
     * @param $message
     * @return Json
     */
    private function notFoundResponse(string $message): Json
    {
        return $this->getJsonResponse()->setJsonData(
            $this->jsonResponsePayload(404, $message)
        );
    }

    /**
     * @param $status
     * @param $message
     * @return bool|string
     */
    private function jsonResponsePayload($status, $message)
    {
        $data = $this->getDataObject();
        $data->setData(self::STATUS_PROPERTY, $status);
        $data->setData(self::MESSAGE_PROPERTY, $message);
        return $data->toJson();
    }

    /**
     * @return Json
     */
    private function getJsonResponse()
    {
        return $this->jsonFactory->create();
    }

    /**
     * @return DataObject
     */
    private function getDataObject()
    {
        return $this->dataObjectFactory->create();
    }
}
