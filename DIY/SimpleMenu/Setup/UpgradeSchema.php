<?php
/**
 * Created by PhpStorm.
 * User: koshpaevsv
 * Date: 03.10.17
 * Time: 10:28
 */

namespace DIY\SimpleMenu\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            // Get module table
            $tableName = $setup->getTable('simple_menu');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'item_id' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'auto_increment' => true,
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'name' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable => false'
                    ],
                    'parent_id' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    ],
                    'position' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    ],
                    'url' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    ],
                    'menu_id' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    ],

                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }

        $installer->endSetup();
    }
}
