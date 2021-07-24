<?php
/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */

namespace Cowell\AbandonedCart\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Cowell\AbandonedCart\Setup
 * @codeCoverageIgnore
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $this->installQuoteAlertEmailLogs($installer);
        $this->installQuoteAlertStatus($installer);
        $installer->endSetup();
    }

    /**
     * Create quote_alert_email_logs
     * @param $installer
     */
    public function installQuoteAlertEmailLogs($installer)
    {
        $connection = $installer->getConnection();
        $tableName  = $installer->getTable('quote_alert_email_logs');

        // Check if the table already exists
        if ($connection->isTableExists($tableName) != true) {
            /**
             * Create table 'quote_alert_email_logs'
             */
            $table = $connection->newTable($tableName)->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                NULL,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'quote_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false],
                'Quote Id'
            )->addColumn(
                'type',
                Table::TYPE_INTEGER,
                NULL,
                [],
                'Type'
            )->addColumn(
                'status',
                Table::TYPE_INTEGER,
                NULL,
                [],
                'Status'
            )->addColumn(
                'email_subject',
                Table::TYPE_TEXT,
                255,
                [],
                'Email Subject'
            )->addColumn(
                'email_content',
                Table::TYPE_TEXT,
                NULL,
                [],
                'Email Content'
            )->addColumn(
                'email_to',
                Table::TYPE_TEXT,
                255,
                [],
                'Email To'
            )->addColumn(
                'sender',
                Table::TYPE_TEXT,
                255,
                [],
                'sender'
            )->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                255,
                [],
                'store_id'
            )->addColumn(
                'sent_date',
                Table::TYPE_TIMESTAMP,
                NULL,
                ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Sent Date'
            )->setComment(
                'Quote Alert Email Logs'
            )->setOption('charset', 'utf8');

            $connection->createTable($table);
        }
    }

    /**
     * Create quote_alert_status
     * @param $installer
     */
    public function installQuoteAlertStatus($installer)
    {
        $connection = $installer->getConnection();
        $tableName  = $installer->getTable('quote_alert_status');

        // Check if the table already exists
        if ($connection->isTableExists($tableName) != true) {
            /**
             * Create table 'quote_alert_status'
             */
            $table = $connection->newTable($tableName)->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                NULL,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'quote_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false],
                'Quote Id'
            )->addColumn(
                'type',
                Table::TYPE_INTEGER,
                NULL,
                [],
                'Type'
            )->addColumn(
                'sent_date',
                Table::TYPE_TIMESTAMP,
                NULL,
                ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Sent Date'
            )->setComment(
                'Quote Alert Status'
            )->setOption('charset', 'utf8');

            $connection->createTable($table);
        }
    }
}
