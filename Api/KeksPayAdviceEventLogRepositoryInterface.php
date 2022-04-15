<?php

namespace Perpetuum\KeksPay\Api;

use Magento\Framework\Exception\AlreadyExistsException;
use Perpetuum\KeksPay\Api\Data\KeksPayAdviceEventLogInterface;
use Exception;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface KeksPayAdviceEventLogRepositoryInterface
{
    /**
     * @param KeksPayAdviceEventLogInterface $keksPayAdviceEventLog
     * @throws AlreadyExistsException
     */
    public function save(KeksPayAdviceEventLogInterface $keksPayAdviceEventLog): void;

    /**
     * @param int $keksPayAdviceEventLogId
     * @return KeksPayAdviceEventLogInterface|null
     */
    public function getById(int $keksPayAdviceEventLogId): ?KeksPayAdviceEventLogInterface;

    /**
     * @param KeksPayAdviceEventLogInterface $keksPayAdviceEventLog
     * @throws Exception
     */
    public function delete(KeksPayAdviceEventLogInterface $keksPayAdviceEventLog): void;

    /**
     * @param int $keksPayAdviceEventLogId
     * @throws Exception
     */
    public function deleteById(int $keksPayAdviceEventLogId);

    /**
     * @return KeksPayAdviceEventLogInterface
     */
    public function getModel(): KeksPayAdviceEventLogInterface;
}
