<?php

namespace Perpetuum\KeksPay\Model\Client\Request;

use Magento\Framework\DataObject;
use Closure;

class Request implements RequestInterface
{
    private $data;

    /**
     * @var Closure|null
     */
    private $middleWare;

    /**
     * Request constructor.
     * @param DataObject|null $data
     */
    public function __construct(?DataObject $data = null)
    {
        $this->data = ($data)
            ? $data
            : new DataObject();
    }

    /**
     * @inheritDoc
     */
    public function getAuthLevel(): ?string
    {
        return $this->data->getData(self::AUTH_LEVEL);
    }

    /**
     * @inheritDoc
     */
    public function setAuthLevel(string $level)
    {
        $this->data->setData(self::AUTH_LEVEL, $level);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->data->getData(self::METHOD);
    }

    /**
     * @inheritDoc
     */
    public function setMethod(string $method): RequestInterface
    {
        $this->data->setData(self::METHOD, $method);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return $this->data->getData(self::ENDPOINT);
    }

    /**
     * @inheritDoc
     */
    public function setEndpoint(string $endpoint): RequestInterface
    {
        $this->data->setData(self::ENDPOINT, $endpoint);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPayload()
    {
        return $this->data->getData(self::PAYLOAD);
    }

    /**
     * @inheritDoc
     */
    public function setPayload($payload): RequestInterface
    {
        $this->data->setData(self::PAYLOAD, $payload);
        return $this;
    }

    /**
     * @param string $name
     * @return null|mixed
     */
    public function getHeader(string $name)
    {
        $headers = (array)$this->data->getData(self::HEADERS);

        if (array_key_exists($name, $headers)) {
            return $headers[$name];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ?array
    {
        return $this->data->getData(self::HEADERS);
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $headers): RequestInterface
    {
        $this->data->setData(self::HEADERS, $headers);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addHeader(string $name, $value): RequestInterface
    {
        $headers = $this->getHeaders();

        if (!$headers) {
            $headers = [];
        }

        $headers[$name] = $value;

        $this->setHeaders($headers);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFullUri(): ?string
    {
        return $this->data->getData(self::FULL_URI);
    }

    /**
     * @inheritDoc
     */
    public function setFullUri(string $uri): RequestInterface
    {
        $this->data->setData(self::FULL_URI, $uri);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMiddleware(): ?Closure
    {
        return $this->middleWare;
    }

    /**
     * @inheritDoc
     */
    public function setMiddleware(Closure $closure): RequestInterface
    {
        $this->middleWare = $closure;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->data->getData();
    }
}
