<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Configuration
{
    const CONFIG_MAIN = 'payment/kekspay';
    const CONFIG_ACTIVE = self::CONFIG_MAIN . '/active';
    const CONFIG_SANDBOX = self::CONFIG_MAIN . '/sandbox';
    const CONFIG_CHECKAUTHORIZATIONTOKEN = self::CONFIG_MAIN . '/checkauthorizationtoken';
    const CONFIG_CID = self::CONFIG_MAIN . '/cid';
    const CONFIG_TID = self::CONFIG_MAIN . '/tid';
    const CONFIG_STORE = self::CONFIG_MAIN . '/store_description';
    const CONFIG_BILL_ID_PREFIX = self::CONFIG_MAIN . '/bill_id_prefix';
    const CONFIG_APPSTORE_APP_LINK = self::CONFIG_MAIN . '/appstore_app_link';
    const CONFIG_PLAYSTORE_APP_LINK = self::CONFIG_MAIN . '/playstore_app_link';
    const CONFIG_PRODUCTION_URL = self::CONFIG_MAIN . '/production_url';
    const CONFIG_SANDBOX_URL = self::CONFIG_MAIN . '/sandbox_url';
    const CONFIG_KEKS_TOKEN = self::CONFIG_MAIN . '/keks_token';
    const CONFIG_SECRETKEY = self::CONFIG_MAIN . '/keks_secretkey';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreInterface $store
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreInterface $store,
        EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->store = $store;
        $this->encryptor = $encryptor;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::CONFIG_ACTIVE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isSandbox(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::CONFIG_SANDBOX, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function cid(): string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_CID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function tid(): string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_TID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function billIdPrefix(): string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_BILL_ID_PREFIX, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function storeDescription(): string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_STORE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function appStoreAppLink(): string
    {
        return (string) $this->scopeConfig
                        ->getValue(self::CONFIG_APPSTORE_APP_LINK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function playStoreAppLink(): string
    {
        return (string) $this
            ->scopeConfig
            ->getValue(self::CONFIG_PLAYSTORE_APP_LINK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function keksToken(): string
    {
        return $this->encryptor->decrypt(
            $this->scopeConfig->getValue(self::CONFIG_KEKS_TOKEN, ScopeInterface::SCOPE_STORE)
        );
    }

    /**
     * @return string
     */
    public function keksSecretKey(): string
    {
        return $this->encryptor->decrypt(
            $this->scopeConfig->getValue(self::CONFIG_SECRETKEY, ScopeInterface::SCOPE_STORE)
        );
    }

    /**
     * @return string
     */
    public function productionUrl(): string
    {
        return (string) $this
            ->scopeConfig
            ->getValue(self::CONFIG_PRODUCTION_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function sandboxUrl(): string
    {
        return (string) $this
            ->scopeConfig
            ->getValue(self::CONFIG_SANDBOX_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isCheckAuthorizationToken(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::CONFIG_CHECKAUTHORIZATIONTOKEN,
            ScopeInterface::SCOPE_STORE
        );
    }
}
