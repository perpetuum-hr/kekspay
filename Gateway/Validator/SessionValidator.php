<?php
// phpcs:ignoreFile

namespace Perpetuum\KeksPay\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;

class SessionValidator extends AbstractValidator
{
    /**
     * SessionValidator constructor.
     * @param ResultInterfaceFactory $resultFactory
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory
    ) {
        parent::__construct($resultFactory);
    }

    /**
     * @param array $validationSubject
     * @return ResultInterface
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function validate(array $validationSubject)
    {
        return $this->createResult(true);
    }
}
