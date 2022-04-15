<?php

namespace Perpetuum\KeksPay\Model\Client;

use Perpetuum\KeksPay\Model\Client\Request\RequestInterface;
use Perpetuum\KeksPay\Model\Client\Response\ResponseInterface;

interface ClientInterface
{
    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function doRequest(RequestInterface $request): ResponseInterface;
}
