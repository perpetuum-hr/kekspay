<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Payment\Model\MethodInterface;

class PaymentAction implements OptionSourceInterface
{
    public const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => $this->getVal(), 'label' => __('Authorize & Capture')
            ]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            $this->getVal() => __('Authorize & Capture')
        ];
    }

    /**
     * Backward Compatibility with Magento 2.2.9
     * @return string
     */
    private function getVal(): string
    {
        // phpcs:ignore Magento2.PHP.LiteralNamespaces.LiteralClassUsage
        return interface_exists('Magento\Payment\Model\MethodInterface')
        // phpcs:ignore Magento2.PHP.LiteralNamespaces.LiteralClassUsage
        && defined('Magento\Payment\Model\MethodInterface::ACTION_AUTHORIZE_CAPTURE')
            ? MethodInterface::ACTION_AUTHORIZE_CAPTURE : self::ACTION_AUTHORIZE_CAPTURE;
    }
}
