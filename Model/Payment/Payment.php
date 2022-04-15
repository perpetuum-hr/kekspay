<?php

namespace Perpetuum\KeksPay\Model\Payment;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Magento\Framework\UrlInterface;
use Perpetuum\KeksPay\Api\Data\Payment\RefundRequestInterface;
use Perpetuum\KeksPay\Api\Data\Transaction\AllTransactionInfoRequestInterface;
use Perpetuum\KeksPay\Api\Data\Transaction\TransactionInfoRequestInterface;
use Perpetuum\KeksPay\Api\Payment\PaymentInterface;
use Perpetuum\KeksPay\Model\Client\ClientInterfaceFactory;
use Perpetuum\KeksPay\Model\Client\Request\RequestInterface;
use Perpetuum\KeksPay\Model\Client\Request\RequestInterfaceFactory;
use Perpetuum\KeksPay\Model\Client\Response\ResponseInterface;
use Perpetuum\KeksPay\Model\Method\AbstractMethod;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Payment extends AbstractMethod implements PaymentInterface
{
    const REFUND_ENDPOINT = 'keksrefund';
    const SINGLE_TRANSACTION_INFO_ENDPOINT = 'kekstrxinfo';
    const ALL_TRANSACTION_INFO_ENDPOINT = 'kekstrxqry';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Payment constructor.
     * @param ClientInterfaceFactory $clientFactory
     * @param RequestInterfaceFactory $requestFactory
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientInterfaceFactory $clientFactory,
        RequestInterfaceFactory $requestFactory,
        UrlInterface $url,
        LoggerInterface $logger
    ) {
        parent::__construct($clientFactory, $requestFactory, $url);
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function refund(RefundRequestInterface $refundRequest): ResponseInterface
    {
        return $this
            ->getClient()
            ->doRequest(
                $this
                    ->createRequest()
                    ->setEndpoint($this->endpointProvider(self::REFUND_ENDPOINT))
                    ->setMethod('POST')
                    ->setPayload($refundRequest->createRequest())
            );
    }

    /**
     * @inheritDoc
     */
    public function transactionInfo(TransactionInfoRequestInterface $transactionInfoRequest): ResponseInterface
    {
        return $this
            ->getClient()
            ->doRequest(
                $this
                    ->createRequest()
                    ->setEndpoint($this->endpointProvider(self::SINGLE_TRANSACTION_INFO_ENDPOINT))
                    ->setMethod('POST')
                    ->setPayload($transactionInfoRequest->createRequest())
            );
    }

    /**
     * @inheritDoc
     */
    public function allTransactionInfo(AllTransactionInfoRequestInterface $transactionInfoRequest): ResponseInterface
    {
        return $this
            ->getClient()
            ->doRequest(
                $this
                    ->createRequest()
                    ->setEndpoint($this->endpointProvider(self::ALL_TRANSACTION_INFO_ENDPOINT))
                    ->setMethod('POST')
                    ->setPayload($transactionInfoRequest->createRequest())
            );
    }

    /**
     * @param $endpoint
     * @return string
     */
    private function endpointProvider($endpoint): string
    {
        return $endpoint;
    }
}
