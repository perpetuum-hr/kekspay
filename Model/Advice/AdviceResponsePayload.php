<?php

namespace Perpetuum\KeksPay\Model\Advice;

use Magento\Framework\DataObjectFactory;
use Perpetuum\KeksPay\Api\Data\Advice\AdviceResponsePayloadInterface;

class AdviceResponsePayload implements AdviceResponsePayloadInterface
{
    private $data;

    /**
     * AdviceResponsePayload constructor.
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(DataObjectFactory $dataObjectFactory)
    {
        $this->data = $dataObjectFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): AdviceResponsePayloadInterface
    {
        $this->data->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): AdviceResponsePayloadInterface
    {
        $this->data->setData(self::MESSAGE, $message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create(): string
    {
        return $this->data->toJson();
    }
}
