<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Controller\Checkout;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Success extends Action
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $statusRepository;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * Success constructor.
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param KeksPaymentStatusRepositoryInterface $statusRepository
     * @param CartRepositoryInterface $cartRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        KeksPaymentStatusRepositoryInterface $statusRepository,
        CartRepositoryInterface $cartRepository,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct($context);

        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->statusRepository = $statusRepository;
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $this->checkoutSession->clearStorage();

        $identity = $this->getRequest()->getParam('identity');
        $status = $this->statusRepository->getByIdentityHash($identity);
        $quote = $this->cartRepository->get($status->getQuoteId());
        $order = $this->getOrderByQuoteId($quote->getId());

        $this->checkoutSession->replaceQuote($quote);
        $this->setCheckoutSessionData($quote, $order);

        // set customer data if customer was logged in
        if ($quote->getCustomerId()) {
            $this->setCustomerDataInSessions($quote);
        }

        return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');
    }

    /**
     * @param CartInterface $quote
     * @param OrderInterface $order
     */
    private function setCheckoutSessionData(CartInterface $quote, OrderInterface $order)
    {
        $this
            ->checkoutSession
            ->setLastQuoteId($quote->getId())
            ->setLastSuccessQuoteId($quote->getId())
            ->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());
    }

    /**
     * @param CartInterface $quote
     */
    private function setCustomerDataInSessions(CartInterface $quote)
    {
        $customer = $quote->getCustomer();
        $this->checkoutSession->setCustomerData($customer);
        $this->customerSession->setCustomerDataAsLoggedIn($customer);
    }

    /**
     * @param $quoteId
     * @return OrderInterface
     */
    private function getOrderByQuoteId($quoteId)
    {
        $searchByQuoteId = $this->searchCriteriaBuilder->addFilter('quote_id', $quoteId)->create();
        $searchByQuoteId->setPageSize(1)->setCurrentPage(1);
        $orders = $this->orderRepository->getList($searchByQuoteId)->getItems();

        /** @var OrderInterface $order */
        return current($orders);
    }
}
