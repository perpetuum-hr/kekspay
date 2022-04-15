<?php

namespace Perpetuum\KeksPay\Model;

use Perpetuum\KeksPay\Api\Data\KeksPayAdviceEventLogInterface;
use Perpetuum\KeksPay\Api\Data\KeksPayAdviceEventLogInterfaceFactory;
use Perpetuum\KeksPay\Api\KeksPayAdviceEventLogRepositoryInterface;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPayAdviceEventLog as KeksPayAdviceEventLogResourceModel;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPayAdviceEventLog\CollectionFactory;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPayAdviceEventLogFactory as KeksPayAdviceEventLogResourceModelFactory;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class KeksPayAdviceEventLogRepository implements KeksPayAdviceEventLogRepositoryInterface
{
    /**
     * @var KeksPayAdviceEventLogResourceModelFactory
     */
    private $resourceModelFactory;

    /**
     * @var KeksPayAdviceEventLogInterfaceFactory
     */
    private $modelFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        KeksPayAdviceEventLogResourceModelFactory $resourceModelFactory,
        KeksPayAdviceEventLogInterfaceFactory $modelFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function save(KeksPayAdviceEventLogInterface $keksPayAdviceEventLog): void
    {
        $this->getResourceModel()->save($keksPayAdviceEventLog);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $keksPayAdviceEventLogId): ?KeksPayAdviceEventLogInterface
    {
        $model = $this->getModel();

        $this->getResourceModel()->load($model, $keksPayAdviceEventLogId);

        return ($model->getId()) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function delete(KeksPayAdviceEventLogInterface $keksPayAdviceEventLog): void
    {
        $this->getResourceModel()->delete($keksPayAdviceEventLog);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $keksPayAdviceEventLogId)
    {
        $model = $this->getById($keksPayAdviceEventLogId);

        if ($model) {
            $this->getResourceModel()->delete($model);
        }
    }

    /**
     * @inheritDoc
     */
    public function getModel(): KeksPayAdviceEventLogInterface
    {
        return $this->modelFactory->create();
    }

    /**
     * @return KeksPayAdviceEventLogResourceModel
     */
    private function getResourceModel(): KeksPayAdviceEventLogResourceModel
    {
        return $this->resourceModelFactory->create();
    }
}
