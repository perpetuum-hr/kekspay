<?php

namespace Perpetuum\KeksPay\Api;

use Magento\Framework\Exception\AlreadyExistsException;
use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterface;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatus\Collection;
use Exception;

interface KeksPaymentStatusRepositoryInterface
{
    /**
     * @param KeksPaymentStatusInterface $keksPaymentStatus
     * @throws AlreadyExistsException
     */
    public function save(KeksPaymentStatusInterface $keksPaymentStatus): void;

    /**
     * @param int $keksPaymentStatusId order_id
     * @return KeksPaymentStatusInterface|null
     */
    public function getById(int $keksPaymentStatusId): ?KeksPaymentStatusInterface;

    /**
     * @param int $quoteId
     * @return KeksPaymentStatusInterface|null
     */
    public function getByQuoteId(int $quoteId): ?KeksPaymentStatusInterface;

    /**
     * @param string $billId
     * @return KeksPaymentStatusInterface|null
     */
    public function getByBillId(string $billId): ?KeksPaymentStatusInterface;

    /**
     * @param string $identityHash
     * @return KeksPaymentStatusInterface|null
     */
    public function getByIdentityHash(string $identityHash): ?KeksPaymentStatusInterface;

    /**
     * @param KeksPaymentStatusInterface $keksPaymentStatus
     * @throws Exception
     */
    public function delete(KeksPaymentStatusInterface $keksPaymentStatus): void;

    /**
     * @param int $keksPaymentStatusId
     * @throws Exception
     */
    public function deleteById(int $keksPaymentStatusId);

    /**
     * @return KeksPaymentStatusInterface
     */
    public function getModel(): KeksPaymentStatusInterface;

    /**
     * @return Collection
     */
    public function getCollection();
}
