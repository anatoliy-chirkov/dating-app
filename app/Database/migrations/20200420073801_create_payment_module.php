<?php

use Phinx\Migration\AbstractMigration;

class CreatePaymentModule extends AbstractMigration
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
            ->addColumn('productType', 'enum', ['values' => ['advantage', 'pusher']])
            ->addColumn('productId', 'integer')
            ->addColumn('createdAt', 'datetime')
            ->addColumn('price', 'double')
            ->create()
        ;
    }
}
