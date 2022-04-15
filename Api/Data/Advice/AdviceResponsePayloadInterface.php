<?php

namespace Perpetuum\KeksPay\Api\Data\Advice;

interface AdviceResponsePayloadInterface
{
    const STATUS = 'status';
    const MESSAGE = 'message';

    const ADVICE_OK = 0;
    const ADVICE_FAILED = -6;
    const ADVICE_ACCEPTED_MESSAGE = 'Accepted';
    const ADVICE_WRONG_TOKEN_MESSAGE = 'Wrong or missing token';

    /**
     * @param int $status
     * @return AdviceResponsePayloadInterface
     */
    public function setStatus(int $status): AdviceResponsePayloadInterface;

    /**
     * @param string $message
     * @return AdviceResponsePayloadInterface
     */
    public function setMessage(string $message): AdviceResponsePayloadInterface;

    /**
     * Should return the payload as JSON
     * @return string
     */
    public function create(): string;
}
