<?php

use Phinx\Migration\AbstractMigration;

class CreateShopModule extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $accessTable = $this->table('action');
        $accessTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('canReduce', 'boolean')
            ->create()
        ;

        $commandTable = $this->table('command');
        $commandTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->create()
        ;

        $productGroupTable = $this->table('productGroup');
        $productGroupTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('about', 'text', ['null' => true])
            ->addColumn('isActive', 'boolean', ['default' => false])
            ->create()
        ;

        $productTable = $this->table('product');
        $productTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('type', 'enum', ['values' => ['access', 'command', 'both']])
            ->addColumn('groupId', 'integer')
            ->addColumn('duration', 'integer')
            ->addColumn('price', 'double')
            ->addColumn('isFree', 'boolean', ['default' => false])
            ->addColumn('isActive', 'boolean', ['default' => false])
            ->create()
        ;

        $productActionTable = $this->table('productAction');
        $productActionTable
            ->addColumn('productId', 'integer')
            ->addColumn('actionId', 'integer')
            ->create()
        ;

        $productCommandTable = $this->table('productCommand');
        $productCommandTable
            ->addColumn('productId', 'integer')
            ->addColumn('commandId', 'integer')
            ->create()
        ;

        $userProductTable = $this->table('userProduct');
        $userProductTable
            ->addColumn('userId', 'integer')
            ->addColumn('productId', 'integer')
            ->addColumn('createdAt', 'datetime')
            ->addColumn('expiredAt', 'datetime')
            ->create()
        ;

        $counterTable = $this->table('counter');
        $counterTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('about', 'text', ['null' => true])
            ->addColumn('isActive', 'boolean', ['default' => false])
            ->create()
        ;

        $counterActionTable = $this->table('counterAction');
        $counterActionTable
            ->addColumn('type', 'enum', ['values' => ['reduce', 'increase']])
            ->addColumn('counterId', 'integer')
            ->addColumn('actionId', 'integer')
            ->addColumn('productId', 'integer', ['null' => true])
            ->addColumn('multiplier', 'double')
            ->addColumn('counterLimit', 'integer', ['null' => true])
            ->addColumn('actionLimit', 'integer', ['null' => true])
            ->create()
        ;

        $userCounterTable = $this->table('userCounter');
        $userCounterTable
            ->addColumn('userId', 'integer')
            ->addColumn('counterId', 'integer')
            ->addColumn('count', 'double')
            ->create()
        ;

        $billTable = $this->table('bill');
        $billTable
            ->addColumn('amount', 'double')
            ->addColumn('userId', 'integer')
            ->addColumn('createdAt', 'datetime')
            ->addColumn('expiredAt', 'datetime', ['null' => true])
            ->addColumn('paidAt', 'datetime', ['null' => true])
            ->create()
        ;

        $purchaseTable = $this->table('purchase');
        $purchaseTable
            ->addColumn('userId', 'integer')
            ->addColumn('productId', 'integer')
            ->addColumn('createdAt', 'datetime')
            ->addColumn('price', 'double')
            ->create()
        ;
    }
}
