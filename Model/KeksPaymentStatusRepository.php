<?php

namespace Perpetuum\KeksPay\Model;

use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterface;
use Perpetuum\KeksPay\Api\Data\KeksPaymentStatusInterfaceFactory;
use Perpetuum\KeksPay\Api\KeksPaymentStatusRepositoryInterface;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatus as KeksPaymentStatusResourceModel;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatusFactory as  KeksPaymentStatusResourceModelFactory;
use Perpetuum\KeksPay\Model\ResourceModel\KeksPaymentStatus\CollectionFactory as KeksPaymentStatusCollectionFactory;

class KeksPaymentStatusRepository implements KeksPaymentStatusRepositoryInterface
{
    /**
     * @var KeksPaymentStatusResourceModelFactory
     */
    private $resourceModelFactory;

    /**
     * @var KeksPaymentStatusInterfaceFactory
     */
    private $modelFactory;

    /**
     * @var KeksPaymentStatusCollectionFactory
     */
    private $collectionFactory;

    /**
     * KeksPaymentStatusRepository constructor.
     * @param KeksPaymentStatusResourceModelFactory $resourceModelFactory
     * @param KeksPaymentStatusInterfaceFactory $modelFactory
     * @param KeksPaymentStatusCollectionFactory $collectionFactory
     */
    public function __construct(
        KeksPaymentStatusResourceModelFactory $resourceModelFactory,
        KeksPaymentStatusInterfaceFactory $modelFactory,
        KeksPaymentStatusCollectionFactory $collectionFactory
    ) {
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function save(KeksPaymentStatusInterface $keksPaymentStatus): void
    {
        $this->getResourceModel()->save($keksPaymentStatus);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $keksPaymentStatusId): ?KeksPaymentStatusInterface
    {
        $model = $this->getModel();
        $this->getResourceModel()->load($model, $keksPaymentStatusId);
        return ($model->getId()) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function getByQuoteId(int $quoteId): ?KeksPaymentStatusInterface
    {
        $model = $this->getModel();
        $this->getResourceModel()->load($model, $quoteId, 'quote_id');
        return ($model->getId()) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function getByBillId(string $billId): ?KeksPaymentStatusInterface
    {
        $model = $this->getModel();
        $this->getResourceModel()->load($model, $billId, 'bill_id');
        return ($model->getId()) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function getByIdentityHash(string $identityHash): ?KeksPaymentStatusInterface
    {
        $model = $this->getModel();
        $this->getResourceModel()->load($model, $identityHash, 'identity_hash');
        return ($model->getId()) ? $model : null;
    }

    /**
     * @inheritDoc
     */
    public function delete(KeksPaymentStatusInterface $keksPaymentStatus): void
    {
        $this->getResourceModel()->delete($keksPaymentStatus);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $keksPaymentStatusId)
    {
        $model = $this->getById($keksPaymentStatusId);

        if ($model) {
            $this->getResourceModel()->delete($model);
        }
    }

    /**
     * @return KeksPaymentStatusInterface
     */
    public function getModel(): KeksPaymentStatusInterface
    {
        return $this->modelFactory->create();
    }

    /**
     * @return KeksPaymentStatusResourceModel
     */
    private function getResourceModel(): KeksPaymentStatusResourceModel
    {
        return $this->resourceModelFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
}
