<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Gateway\Handler;

use Magento\Payment\Gateway\Config\ValueHandlerInterface;

class TitleHandler implements ValueHandlerInterface
{
    const DEFAULT_TITLE        = 'KEKS Pay';
    const DEFAULT_TITLE_FORMAT = '%s (%s)';

    /**
     * Retrieve method configured value
     *
     * @param array $subject
     * @param null $storeId
     * @return mixed|void
     * @SuppressWarnings(UnusedFormalParameter);
     */
    // phpcs:ignore
    public function handle(array $subject, $storeId = null)
    {
        return self::DEFAULT_TITLE;
    }
}
