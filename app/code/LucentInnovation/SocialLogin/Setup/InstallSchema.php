<?php
namespace LucentInnovation\SocialLogin\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

       
            $table = $installer->getConnection()
                ->newTable($installer->getTable('lucent_sociallogin'))
                ->addColumn('id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'ID')
                ->addColumn('customer_id', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Customer Id')
                ->addColumn('app_id', Table::TYPE_TEXT, null, ['nullable' => false, 'unsigned' => true,], 'Facebook App/Google Client Id')
                ->addColumn('email', Table::TYPE_TEXT, 255, [], 'Email')
                ->addColumn('social_type', Table::TYPE_TEXT, 255, [], 'Social Platform Type')
                ->setComment('Social Login Table');

            $installer->getConnection()->createTable($table);
        
        $installer->endSetup();
    }
}
