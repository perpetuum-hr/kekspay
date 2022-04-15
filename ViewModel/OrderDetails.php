<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;

class OrderDetails implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $statusRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        RequestInterface $request,
        KeksPaymentStatusRepositoryInterface $statusRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->request = $request;
        $this->statusRepository = $statusRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return string|null
     */
    public function getCurrentOrderKeksId()
    {
        $currentOrderId = (int) $this->request->getParam('order_id');
        $currentOrder = $this->orderRepository->get($currentOrderId);
        $keksPaymentStatus = $this->statusRepository->getByQuoteId((int) $currentOrder->getQuoteId());

        if ($keksPaymentStatus !== null && $keksPaymentStatus->getKeksId() !== null) {
            return $keksPaymentStatus->getKeksId();
        }

        return null;
    }
}
