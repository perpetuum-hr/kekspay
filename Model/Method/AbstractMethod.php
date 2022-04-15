<?php

namespace Perpetuum\KeksPay\Model\Method;

use Magento\Framework\UrlInterface;
use Perpetuum\KeksPay\Model\Client\ClientInterface;
use Perpetuum\KeksPay\Model\Client\ClientInterfaceFactory;
use Perpetuum\KeksPay\Model\Client\Request\RequestInterface;
use Perpetuum\KeksPay\Model\Client\Request\RequestInterfaceFactory;

/**
 * Class AbstractMethod
 * @package Perpetuum\KeksPay\Model\Method
 */
abstract class AbstractMethod
{
    /**
     * @var null|ClientInterface
     */
    protected $client;

    /**
     * @var ClientInterfaceFactory
     */
    private $clientFactory;

    /**
     * @var RequestInterfaceFactory
     */
    private $requestFactory;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * AbstractMethod constructor.
     * @param ClientInterfaceFactory $clientFactory
     * @param RequestInterfaceFactory $requestFactory
     * @param UrlInterface $url
     */
    public function __construct(
        ClientInterfaceFactory $clientFactory,
        RequestInterfaceFactory $requestFactory,
        UrlInterface $url
    ) {
        $this->clientFactory = $clientFactory;
        $this->requestFactory = $requestFactory;
        $this->url = $url;
    }

    /**
     * @return ClientInterface|null
     */
    protected function getClient()
    {
        if (!$this->client) {
            $this->client = $this->clientFactory->create();
        }

        return $this->client;
    }

    /**
     * @return RequestInterface
     */
    protected function createRequest()
    {
        return $this->requestFactory->create();
    }
}
