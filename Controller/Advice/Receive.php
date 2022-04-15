<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Controller\Advice;

use Magento\Checkout\Model\Session;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\DB\Transaction;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Perpetuum\KeksPay\Api\Data\Advice\AdviceResponsePayloadInterface;
use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterface;
use Perpetuum\KeksPay\Api\KeksPayAdviceEventLogRepositoryInterface;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;
use Perpetuum\KeksPay\Model\Advice\AdvicePayloadFactory;
use Perpetuum\KeksPay\Model\Advice\AdviceResponsePayloadFactory;
use Perpetuum\KeksPay\Model\Configuration;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
// phpcs:ignore Generic.Classes.DuplicateClassName.Found
class Receive extends Action implements CsrfAwareActionInterface
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    /**
     * @var KeksPayAdviceEventLogRepositoryInterface
     */
    private $keksPayAdviceEventLogRepository;

    /**
     * @var AdviceResponsePayloadFactory
     */
    private $responsePayloadFactory;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $statusRepository;

    /**
     * @var Onepage
     */
    private $onePage;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var AdvicePayloadFactory
     */
    private $advicePayloadFactory;

    /**
     * Receive constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository
     * @param KeksPayAdviceEventLogRepositoryInterface $keksPayAdviceEventLogRepository
     * @param AdviceResponsePayloadFactory $responsePayloadFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param Transaction $transaction
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Configuration $configuration
     * @param CartRepositoryInterface $cartRepository
     * @param Session $session
     * @param KeksPaymentStatusRepositoryInterface $statusRepository
     * @param Onepage $onePage
     * @param \Magento\Customer\Model\Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param LoggerInterface $logger
     * @param AdvicePayloadFactory $advicePayloadFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository,
        KeksPayAdviceEventLogRepositoryInterface $keksPayAdviceEventLogRepository,
        AdviceResponsePayloadFactory $responsePayloadFactory,
        OrderRepositoryInterface $orderRepository,
        Transaction $transaction,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Configuration $configuration,
        CartRepositoryInterface $cartRepository,
        Session $session,
        KeksPaymentStatusRepositoryInterface $statusRepository,
        Onepage $onePage,
        \Magento\Customer\Model\Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        LoggerInterface $logger,
        AdvicePayloadFactory $advicePayloadFactory
    ) {
        parent::__construct($context);

        $this->jsonFactory = $jsonFactory;
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
        $this->keksPayAdviceEventLogRepository = $keksPayAdviceEventLogRepository;
        $this->responsePayloadFactory = $responsePayloadFactory;
        $this->orderRepository = $orderRepository;
        $this->transaction = $transaction;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->configuration = $configuration;
        $this->cartRepository = $cartRepository;
        $this->session = $session;
        $this->statusRepository = $statusRepository;
        $this->onePage = $onePage;
        $this->customerSession = $customerSession;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->logger = $logger;
        $this->advicePayloadFactory = $advicePayloadFactory;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     * @throws AlreadyExistsException
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $this->session->clearStorage();

        $this->customerSession->clearStorage();

        $requestContent = $this->getRequest()->getContent();

        $data = json_decode($requestContent, true);

        if ($this->configuration->isCheckAuthorizationToken()) {
            $tokenHeader = $this->getRequest()->getHeader('Authorization');

            if (!$tokenHeader) {
                return $this->generateWrongTokenResponse();
            }

            if (!$this->checkIsValidAuthorizationToken($tokenHeader)) {
                return $this->generateWrongTokenResponse();
            }
        }

        $advicePayload = $this->advicePayloadFactory->create(['data' => $data]);

        // save event log
        $eventLog = $this->keksPayAdviceEventLogRepository->getModel();
        $eventLog->addData($data);
        $eventLog->setResponse($requestContent);
        $this->keksPayAdviceEventLogRepository->save($eventLog);

        // fetch payment status and save with received advice data
        $statusModel = $this->keksPaymentStatusRepository->getByBillId($advicePayload->getBillId());
        if ($statusModel === null) {
            return $this->generateAdviceResponse(
                AdviceResponsePayloadInterface::ADVICE_FAILED,
                'No payment status available with received bill id'
            );
        }
        $statusModel->addData($advicePayload->toModelArray());
        $statusModel->setResponse($requestContent);
        $this->keksPaymentStatusRepository->save($statusModel);

        $orderId = $statusModel->getId();
        if (!$orderId) {
            return $this->generateAdviceResponse(
                AdviceResponsePayloadInterface::ADVICE_FAILED,
                'Advice failed to be saved!'
            );
        }

        try {
            $setup = $this->setup($orderId);
            /**
             * @var KeksPaymentStatusInterface|null
             */
            $statusModel = null;

            if ($setup) {
                /**
                 * @var CartInterface $quote
                 * @var KeksPaymentStatusInterface $status
                 */
                list($quote, $status) = $setup;
                $statusModel = $status;
            }

            try {
                $this->onePage->saveOrder();
                $statusModel->setIsOrderCreated(true);
                $this->keksPaymentStatusRepository->save($statusModel);
            } catch (Throwable $exception) {
                $statusModel->setIsOrderCreated(false);
                $this->keksPaymentStatusRepository->save($statusModel);
                $this->logger->alert($exception->getTraceAsString());

                return $this->generateAdviceResponse(
                    AdviceResponsePayloadInterface::ADVICE_FAILED,
                    $exception->getMessage()
                );
            }
        } catch (Throwable $exception) {
            $statusModel->setIsOrderCreated(false);
            $this->keksPaymentStatusRepository->save($statusModel);
            $this->logger->alert($exception->getTraceAsString());

            return $this->generateAdviceResponse(
                AdviceResponsePayloadInterface::ADVICE_FAILED,
                $exception->getMessage()
            );
        }

        return $this->generateAdviceResponse(
            AdviceResponsePayloadInterface::ADVICE_OK,
            AdviceResponsePayloadInterface::ADVICE_ACCEPTED_MESSAGE
        );
    }

    /**
     * @param string $tokenHeader
     * @return bool
     */
    private function checkIsValidAuthorizationToken(string $tokenHeader): bool
    {
        $tokenExploded = explode(" ", $tokenHeader);

        $tokenValue = $tokenExploded[1];

        return !empty($tokenValue) && $tokenValue === $this->configuration->keksToken();
    }

    /**
     * @return Json
     */
    private function generateWrongTokenResponse(): Json
    {
        return $this->generateAdviceResponse(
            AdviceResponsePayloadInterface::ADVICE_FAILED,
            AdviceResponsePayloadInterface::ADVICE_WRONG_TOKEN_MESSAGE
        );
    }

    /**
     * @param $status
     * @param $message
     * @return Json
     */
    private function generateAdviceResponse(int $status, string $message)
    {
        // create response and set data
        $responsePayload = $this->responsePayloadFactory->create();

        $jsonResponse = $responsePayload
            ->setStatus($status)
            ->setMessage($message)
            ->create();

        return $this->jsonFactory->create()->setJsonData($jsonResponse);
    }

    /**
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     * @SuppressWarnings("unused")
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @param RequestInterface $request
     * @return bool|null
     * @SuppressWarnings("unused")
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @param mixed $reservedOrderId
     * @return array|null
     */
    private function setup($reservedOrderId): ?array
    {
        $this->session->clearStorage();

        $this->customerSession->clearStorage();

        try {
            $status = $this->statusRepository->getById((int) $reservedOrderId);

            if (!$status) {
                return null;
            }

            $quote = $this->cartRepository->getActive($status->getQuoteId());

            if ($quote->getCustomerId()) {
                $customer = $quote->getCustomer();
                $this->session->setCustomerData($customer);
                $this->customerSession->setCustomerDataAsLoggedIn($customer);
            }

            $this->session->replaceQuote($quote);
            $this->onePage->setQuote($quote);

            return [$quote, $status];
        } catch (Throwable $exception) {
            $this->logger->alert($exception->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An Exception has occurred.'));
            return null;
        }
    }

    /**
     * @return DataObject
     */
    protected function getDataObject(): DataObject
    {
        return $this->dataObjectFactory->create();
    }
}
