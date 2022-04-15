<?php

namespace Perpetuum\KeksPay\Model\Client\Request;

use Closure;

interface RequestInterface
{
    const METHOD = 'method';
    const ENDPOINT = 'endpoint';
    const PAYLOAD = 'payload';
    const AUTH_LEVEL = 'auth_level';
    const HEADERS = 'headers';
    const FULL_URI = 'full_uri';

    /**
     * @return null|string
     */
    public function getAuthLevel(): ?string;

    /**
     * @param string $level
     * @return mixed
     */
    public function setAuthLevel(string $level);

    /**
     * @return string
     */
    public function getMethod(): ?string;

    /**
     * @param string $method
     * @return RequestInterface
     */
    public function setMethod(string $method): self;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @param string $endpoint
     * @return RequestInterface
     */
    public function setEndpoint(string $endpoint): self;

    /**
     * @return mixed
     */
    public function getPayload();

    /**
     * @param $payload
     * @return RequestInterface
     */
    public function setPayload($payload): self;

    /**
     * @param string $name
     * @return null|mixed
     */
    public function getHeader(string $name);

    /**
     * @return array|null
     */
    public function getHeaders(): ?array;

    /**
     * @param array $headers
     * @return RequestInterface
     */
    public function setHeaders(array $headers): self;

    /**
     * @param string $name
     * @param mixed $value
     * @return RequestInterface
     */
    public function addHeader(string $name, $value): self;

    /**
     * @return null|string
     */
    public function getFullUri(): ?string;

    /**
     * @param string $uri
     * @return RequestInterface
     */
    public function setFullUri(string $uri): self;

    /**
     * @return Closure|null
     */
    public function getMiddleware(): ?Closure;

    /**
     * @param Closure $closure
     * @return RequestInterface
     */
    public function setMiddleware(Closure $closure): self;

    /**
     * @return array
     */
    public function getData(): array;
}
