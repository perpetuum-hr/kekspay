<?php

namespace Perpetuum\KeksPay\Model\Client\Response;

use Magento\Framework\DataObject;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface ResponseInterface
{
    const STATUS_SUCCESS = 0;
    const STATUS_FAILURE = 1;
    const STATUS_EXCEPTION = 2;

    /**
     * @return DataObject
     */
    public function getPayload(): DataObject;

    /**
     * @return null|string
     */
    public function getInformation(): ?string;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return null|int
     */
    public function getHttpStatus(): ?int;

    /**
     * @return null|PsrResponseInterface
     */
    public function getPsrResponse(): ?PsrResponseInterface;
}
