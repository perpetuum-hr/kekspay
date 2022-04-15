<?php

namespace Perpetuum\KeksPay\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Directory\Model\Currency;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Asset\Repository as ViewRepository;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class KeksPayConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ViewRepository
     */
    private $viewRepository;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var KeksPaymentStatusRepositoryInterface
     */
    private $keksPaymentStatusRepository;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * KeksPayConfigProvider constructor.
     * @param Session $session
     * @param Configuration $configuration
     * @param KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository
     * @param CartRepositoryInterface $cartRepository
     * @param ViewRepository $viewRepository
     * @param Currency $currency
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Session $session,
        Configuration $configuration,
        KeksPaymentStatusRepositoryInterface $keksPaymentStatusRepository,
        CartRepositoryInterface $cartRepository,
        ViewRepository $viewRepository,
        Currency $currency,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager
    ) {
        $this->viewRepository = $viewRepository;
        $this->configuration = $configuration;
        $this->session = $session;
        $this->logger = $logger;
        $this->currency = $currency;
        $this->keksPaymentStatusRepository = $keksPaymentStatusRepository;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array|void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getConfig()
    {
        $paymentConfig = [];

        // set kekspay active by default
        $paymentConfig['payment']['kekspay']['active'] = true;

        $quote = $this->session->getQuote();

        if (!$this->configuration->isActive() || !$quote) {
            $paymentConfig['payment']['kekspay']['active'] = false;
            return $paymentConfig;
        }

        if (!$quote->getReservedOrderId()) {
            $quote->reserveOrderId();
            $this->cartRepository->save($quote);
        }

        $kekspaymentStatus = $this->keksPaymentStatusRepository->getByQuoteId((int) $quote->getId());

        if ($kekspaymentStatus) {
            $this->keksPaymentStatusRepository->delete($kekspaymentStatus);
            $kekspaymentStatus = null;
        }

        // BILL ID
        $billId = "{$this->configuration->billIdPrefix()}{$quote->getReservedOrderId()}";
        $orderId = $quote->getReservedOrderId();
        $identityHash = $this->generateIdentityHash((int) $quote->getId());

        if ($kekspaymentStatus === null) {
            $kekspaymentStatus = $this->keksPaymentStatusRepository->getModel();

            $kekspaymentStatus->setId($orderId);
            $kekspaymentStatus->setQuoteId((int) $quote->getId());
            $kekspaymentStatus->setBillId($billId);
            $kekspaymentStatus->setCid($this->configuration->cid());
            $kekspaymentStatus->setTid($this->configuration->tid());
            $kekspaymentStatus->setIdentityHash($identityHash);

            try {
                $this->keksPaymentStatusRepository->save($kekspaymentStatus);
            } catch (AlreadyExistsException $e) {
                $this->logger->alert($e->getMessage());
                return $paymentConfig;
            }
        }

        list(
            $appStoreSvgLogoPath,
            $playStoreSvgLogoPath,
            $keksPayLogoPath,
            $keksPayLogoVPath,
            $plusIcon
            ) = $this->getLogosPaths();

        $paymentConfig['payment']['kekspay']['qr_type'] = 1; // default
        $paymentConfig['payment']['kekspay']['order_id'] = $orderId;
        $paymentConfig['payment']['kekspay']['bill_id'] = $billId;
        $paymentConfig['payment']['kekspay']['cid'] = $this->configuration->cid();
        $paymentConfig['payment']['kekspay']['tid'] = $this->configuration->tid();
        $paymentConfig['payment']['kekspay']['store_description'] = $this->configuration->storeDescription();
        $paymentConfig['payment']['kekspay']['identity_hash'] = $identityHash;

        $paymentConfig['payment']['kekspay']['currency'] = $this
            ->storeManager
            ->getStore()
            ->getCurrentCurrency()
            ->getCode();

        $paymentConfig['payment']['kekspay']['appstore_logo_svg'] = $appStoreSvgLogoPath;
        $paymentConfig['payment']['kekspay']['playstore_logo_svg'] = $playStoreSvgLogoPath;
        $paymentConfig['payment']['kekspay']['appstore_app_link'] = $this->configuration->appStoreAppLink();
        $paymentConfig['payment']['kekspay']['playstore_app_link'] = $this->configuration->playStoreAppLink();
        $paymentConfig['payment']['kekspay']['kekspay_logo'] = $keksPayLogoPath;
        $paymentConfig['payment']['kekspay']['kekspay_logo_v'] = $keksPayLogoVPath;
        $paymentConfig['payment']['kekspay']['plus_icon'] = $plusIcon;
        $paymentConfig['payment']['kekspay']['is_sandbox'] = $this->configuration->isSandbox();

        return $paymentConfig;
    }

    /**
     * @return array
     */
    private function getLogosPaths(): array
    {
        $dir = 'Perpetuum_KeksPay::images';

        return [
            $this->viewRepository->getUrl("$dir/download_on_appstore.svg"),
            $this->viewRepository->getUrl("$dir/download_on_playstore.svg"),
            $this->viewRepository->getUrl("$dir/kekspay_logo.svg"),
            $this->viewRepository->getUrl("$dir/kekspay_vertical_logo.svg"),
            $this->viewRepository->getUrl("$dir/plus_icon.svg")
        ];
    }

    /**
     * @param $quoteId
     * @return string
     */
    private function generateIdentityHash($quoteId): string
    {
        return $quoteId . bin2hex(openssl_random_pseudo_bytes(31));
    }
}
