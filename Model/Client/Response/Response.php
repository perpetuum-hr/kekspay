<?php

namespace Perpetuum\KeksPay\Model\Client\Response;

use Magento\Framework\DataObject;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response implements ResponseInterface
{
    private $payload;
    private $information;
    private $status;
    private $httpStatus;
    private $psrResponse;

    /**
     * Response constructor.
     * @param array|null $payload
     * @param null|string $information
     * @param int $status
     * @param int|null $httpStatus
     * @param null|PsrResponseInterface $psrResponse
     */
    public function __construct(
        ?array $payload = null,
        ?string $information = null,
        int $status = ResponseInterface::STATUS_SUCCESS,
        ?int $httpStatus = null,
        ?PsrResponseInterface $psrResponse = null
    ) {
        $this->payload = $payload;
        $this->information = $information;
        $this->status = $status;
        $this->httpStatus = $httpStatus;
        $this->psrResponse = $psrResponse;
    }

    /**
     * @inheritDoc
     */
    public function getPayload(): DataObject
    {
        return new DataObject(($this->payload) ? $this->payload : []);
    }

    /**
     * @inheritDoc
     */
    public function getInformation(): ?string
    {
        return $this->information;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * @inheritDoc
     */
    public function getPsrResponse(): ?PsrResponseInterface
    {
        return $this->psrResponse;
    }
}
