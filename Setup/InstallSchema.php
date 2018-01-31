<?php

namespace Springbot\Queue\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'springbot_queue'
         */
        $connection = $installer->getConnection();
        $table = $connection->newTable(
            $installer->getTable('springbot_queue'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                11,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID')
            ->addColumn(
                'method',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Queue method')
            ->addColumn(
                'args',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Type ID')
            ->addColumn(
                'class',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Class To Instantiate')
            ->addColumn(
                'hash',
                Table::TYPE_TEXT,
                40,
                ['nullable' => false],
                'Has options')
            ->addColumn(
                'queue',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Required options')
            ->addColumn(
                'priority',
                Table::TYPE_INTEGER,
                10,
                [],
                'Queue priority')
            ->addColumn(
                'attempts',
                Table::TYPE_INTEGER,
                10,
                [],
                'Attempts to run queue')
            ->addColumn(
                'run_at',
                Table::TYPE_DATETIME,
                null,
                [],
                'Initial run time')
            ->addColumn(
                'locked_at',
                Table::TYPE_DATETIME,
                null,
                [],
                'Locked at time')
            ->addColumn(
                'locked_by',
                Table::TYPE_TEXT,
                255,
                [],
                'Locked by pid')
            ->addColumn(
                'error',
                Table::TYPE_TEXT,
                null,
                [],
                'Next run time')
            ->addColumn(
                'next_run_at',
                Table::TYPE_DATETIME,
                null,
                [],
                'Next run time')
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                [],
                'Next run time')
            ->setComment('Springbot Queue Table');
        $installer->getConnection()->createTable($table);

        $connection->addIndex(
            $setup->getTable('springbot_queue'),
            $setup->getIdxName('springbot_queue', ['next_run_at']),
            ['next_run_at']
        );

        $connection->addIndex(
            $setup->getTable('springbot_queue'),
            $setup->getIdxName('springbot_queue', ['attempts']),
            ['attempts']
        );

        $connection->addIndex(
            $setup->getTable('springbot_queue'),
            $setup->getIdxName('springbot_queue', ['priority']),
            ['priority']
        );

        $installer->endSetup();
    }
}
