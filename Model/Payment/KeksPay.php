<?php

namespace Perpetuum\KeksPay\Model\Payment;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Locale\Resolver;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\Adapter;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(ExcessivePublicCount)
 * @SuppressWarnings(TooManyPublicMethods)
 * @SuppressWarnings(TooManyMethods)
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class KeksPay implements MethodInterface
{
    const METHOD_CODE = 'kekspay';

    private $code = 'kekspay';

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * KeksPay constructor.
     * @param Adapter $adapter
     * @param Resolver $resolver
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        Adapter $adapter,
        Resolver $resolver,
        ScopeConfigInterface $config
    ) {
        $this->adapter = $adapter;
        $this->resolver = $resolver;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getFormBlockType()
    {
        return $this->adapter->getFormBlockType();
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->adapter->getTitle();
    }

    /**
     * @param int $storeId
     */
    public function setStore($storeId)
    {
        $this->adapter->setStore($storeId);
    }

    /**
     * @return int
     */
    public function getStore()
    {
        return $this->adapter->getStore();
    }

    /**
     * @return bool
     */
    public function canOrder()
    {
        return $this->adapter->canOrder();
    }

    /**
     * @return bool
     */
    public function canAuthorize()
    {
        return $this->adapter->canAuthorize();
    }

    /**
     * @return bool
     */
    public function canCapture()
    {
        return $this->adapter->canCapture();
    }

    /**
     * @return bool
     */
    public function canCapturePartial()
    {
        return $this->adapter->canCapturePartial();
    }

    /**
     * @return bool
     */
    public function canCaptureOnce()
    {
        return $this->adapter->canCaptureOnce();
    }

    /**
     * @return bool
     */
    public function canRefund()
    {
        return $this->adapter->canRefund();
    }

    /**
     * @return bool
     */
    public function canRefundPartialPerInvoice()
    {
        return $this->adapter->canRefundPartialPerInvoice();
    }

    /**
     * @return bool
     */
    public function canVoid()
    {
        return $this->adapter->canVoid();
    }

    /**
     * @return bool
     */
    public function canUseInternal()
    {
        return $this->adapter->canUseInternal();
    }

    /**
     * @return bool
     */
    public function canUseCheckout()
    {
        return $this->adapter->canUseCheckout();
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        return $this->adapter->canEdit();
    }

    /**
     * @return bool
     */
    public function canFetchTransactionInfo()
    {
        return $this->adapter->canFetchTransactionInfo();
    }

    /**
     * @param InfoInterface $payment
     * @param string $transactionId
     * @return array|\Magento\Payment\Gateway\Command\ResultInterface|null
     */
    public function fetchTransactionInfo(InfoInterface $payment, $transactionId)
    {
        return $this->adapter->fetchTransactionInfo($payment, $transactionId);
    }

    /**
     * @return bool
     */
    public function isGateway()
    {
        return $this->adapter->isGateway();
    }

    /**
     * @return bool
     */
    public function isOffline()
    {
        return $this->adapter->isOffline();
    }

    /**
     * @return bool
     */
    public function isInitializeNeeded()
    {
        return $this->adapter->isInitializeNeeded();
    }

    /**
     * @param string $country
     * @return bool
     */
    public function canUseForCountry($country)
    {
        return $this->adapter->canUseForCountry($country);
    }

    /**
     * @param string $currencyCode
     * @return bool
     */
    public function canUseForCurrency($currencyCode)
    {
        return $this->adapter->canUseForCurrency($currencyCode);
    }

    /**
     * @return string
     */
    public function getInfoBlockType()
    {
        return $this->adapter->getInfoBlockType();
    }

    /**
     * @return InfoInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getInfoInstance()
    {
        return $this->adapter->getInfoInstance();
    }

    /**
     * @param InfoInterface $info
     */
    public function setInfoInstance(InfoInterface $info)
    {
        $this->adapter->setInfoInstance($info);
    }

    /**
     * @return $this|KeksPay
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate()
    {
        $this->adapter->validate();
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this|KeksPay
     */
    public function order(InfoInterface $payment, $amount)
    {
        $this->adapter->order($payment, $amount);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this|KeksPay
     */
    public function authorize(InfoInterface $payment, $amount)
    {
        $this->adapter->authorize($payment, $amount);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this|KeksPay
     */
    public function capture(InfoInterface $payment, $amount)
    {
        $this->adapter->capture($payment, $amount);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this|KeksPay
     */
    public function refund(InfoInterface $payment, $amount)
    {
        $this->adapter->refund($payment, $amount);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @return $this|KeksPay
     */
    public function cancel(InfoInterface $payment)
    {
        $this->adapter->cancel($payment);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @return $this|KeksPay
     */
    public function void(InfoInterface $payment)
    {
        $this->adapter->void($payment);
        return $this;
    }

    /**
     * @return bool
     */
    public function canReviewPayment()
    {
        return $this->adapter->canReviewPayment();
    }

    /**
     * @param InfoInterface $payment
     * @return $this|false
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function acceptPayment(InfoInterface $payment)
    {
        $this->adapter->acceptPayment($payment);
        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @return $this|false
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function denyPayment(InfoInterface $payment)
    {
        $this->adapter->denyPayment($payment);
        return $this;
    }

    /**
     * @param string $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        return $this->adapter->getConfigData($field, $storeId);
    }

    /**
     * @param DataObject $data
     * @return $this|KeksPay
     */
    public function assignData(DataObject $data)
    {
        $this->adapter->assignData($data);
        return $this;
    }

    /**
     * @param CartInterface|null $quote
     * @return array|bool|mixed|null
     */
    public function isAvailable(CartInterface $quote = null)
    {
        return $this->adapter->isAvailable($quote);
    }

    /**
     * @param null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        $scope = ($storeId === null ? ScopeConfigInterface::SCOPE_TYPE_DEFAULT : ScopeInterface::SCOPE_STORES);
        return $this->config->isSetFlag('payment/' . self::METHOD_CODE . '/active', $scope, $storeId);
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * @return Adapter|MethodInterface|KeksPay
     */
    public function initialize($paymentAction, $stateObject)
    {
        return $this->adapter->initialize($paymentAction, $stateObject);
    }

    /**
     * @return mixed|string
     */
    public function getConfigPaymentAction()
    {
        return $this->adapter->getConfigPaymentAction();
    }
}
