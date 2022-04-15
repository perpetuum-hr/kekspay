<?php

namespace Perpetuum\KeksPay\Block\Info;

use Magento\Framework\Locale\Resolver;
use Magento\Framework\View\Element\Template;
use Magento\Payment\Block\Info;

class KeksPay extends Info
{
    /**
     * @var Resolver
     */
    private $locale;

    /**
     * KeksPay constructor.
     * @param Template\Context $context
     * @param Resolver $locale
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Resolver $locale,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_template = 'Perpetuum_KeksPay::payment/info.phtml';
        $this->locale = $locale;
    }

    /**
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale->getLocale();
    }

    /**
     * Check if string is a url
     *
     * @param string $string
     * @return bool
     */
    public function isStringUrl($string)
    {
        return (bool)filter_var($string, FILTER_VALIDATE_URL);
    }
}
