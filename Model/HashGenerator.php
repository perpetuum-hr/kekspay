<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Model;

use Magento\Framework\DataObject;
use Perpetuum\KeksPay\Api\Data\Payment\RefundRequestInterface;

class HashGenerator
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * HashGenerator constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $epochTime
     * @param DataObject $refundRequestData
     * @return string
     */
    public function generateHashForRefundRequest($epochTime, DataObject $refundRequestData)
    {
        $tid = $refundRequestData->getData(RefundRequestInterface::TID);
        $amount = $refundRequestData->getData(RefundRequestInterface::AMOUNT);
        $billId = $refundRequestData->getData(RefundRequestInterface::BILL_ID);

        $dataToBeEncrypted = $epochTime . $tid . $amount . $billId;
        $secretKey = $this->configuration->keksSecretKey();

        return $this->generateHash($dataToBeEncrypted, $secretKey);
    }

    /**
     * @param string $data
     * @param string $secret
     * @return string
     */
    private function generateHash(string $data, string $secret): string
    {
        $hash = hash('md5', utf8_encode($data), true);

        $errRep = error_reporting(error_reporting() & ~E_WARNING); // disable warning coz of empty iv
        $cipherText = openssl_encrypt($hash, 'DES-EDE3-CBC', $secret, OPENSSL_RAW_DATA);
        error_reporting($errRep);

        return strtoupper(bin2hex($cipherText));
    }
}
