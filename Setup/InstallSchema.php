<?php

namespace Perpetuum\KeksPay\Setup;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    const KEKSPAY_PAYMENT_STATUS_TABLE_NAME = 'kekspay_payment_status';
    const KEKSPAY_ADVICE_EVENT_LOG_TABLE_NAME = 'kekspay_advice_event_log';

    /**
     * InstallSchema constructor.
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     * @SuppressWarnings("unused")
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    // phpcs:ignore
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ((int)$this->productMetadata->getVersion() < 2.3) {
            // kekspay_payment_status Table
            if (!$setup->tableExists(self::KEKSPAY_PAYMENT_STATUS_TABLE_NAME)) {
                $paymentStatusTable = $setup->getConnection()->newTable(
                    $setup->getTable(self::KEKSPAY_PAYMENT_STATUS_TABLE_NAME)
                )->addColumn(
                    'order_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => false,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true
                    ],
                    'Order ID'
                )->addColumn(
                    'quote_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true
                    ],
                    'Quote ID'
                )->addColumn(
                    'bill_id',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Bill ID'
                )->addColumn(
                    'keks_id',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Keks ID'
                )->addColumn(
                    'cid',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'CID'
                )->addColumn(
                    'tid',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'TID'
                )->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'Status'
                )->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'Message'
                )->addColumn(
                    'response',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'Response'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Created At'
                )->addColumn(
                    'amount',
                    Table::TYPE_DECIMAL,
                    [13, 2],
                    []
                )->addColumn(
                    'is_order_created',
                    Table::TYPE_BOOLEAN
                )->addColumn(
                    'identity_hash',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Identity Hash'
                )->addColumn(
                    'additional_data',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'Additional Data'
                )->addIndex(
                    $setup->getIdxName(
                        self::KEKSPAY_PAYMENT_STATUS_TABLE_NAME,
                        ['bill_id', 'keks_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['bill_id', 'keks_id', 'identity_hash'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )->setComment('KeksPay Payment Status');

                $setup->getConnection()->createTable($paymentStatusTable);
            }

            // kekspay_advice_event_log Table
            if (!$setup->tableExists(self::KEKSPAY_ADVICE_EVENT_LOG_TABLE_NAME)) {
                $adviceEventLogTable = $setup->getConnection()->newTable(
                    $setup->getTable(self::KEKSPAY_ADVICE_EVENT_LOG_TABLE_NAME)
                )->addColumn(
                    'entity_id',
                    Table::TYPE_BIGINT,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true
                    ],
                    'ID'
                )->addColumn(
                    'bill_id',
                    Table::TYPE_TEXT,
                    255,
                    []
                )->addColumn(
                    'keks_id',
                    Table::TYPE_TEXT,
                    255,
                    []
                )->addColumn(
                    'tid',
                    Table::TYPE_TEXT,
                    255,
                    []
                )->addColumn(
                    'store',
                    Table::TYPE_TEXT,
                    255,
                    []
                )->addColumn(
                    'amount',
                    Table::TYPE_DECIMAL,
                    [13, 2],
                    []
                )->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'Status'
                )->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'Message'
                )->addColumn(
                    'response',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'Response'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Created At'
                )->setComment('KeksPay Advice Event Log');

                $setup->getConnection()->createTable($adviceEventLogTable);
            }
        }

        $setup->endSetup();
    }
}
