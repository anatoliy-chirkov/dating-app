<?php

use Phinx\Migration\AbstractMigration;

class CreateCounterModule extends AbstractMigration
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
        $actionTable = $this->table('action');
        $actionTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->create()
        ;

        $counterTable = $this->table('counter');
        $counterTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('isActive', 'boolean', ['default' => false])
            ->create()
        ;

        $counterActionTable = $this->table('counterAction');
        $counterActionTable
            ->addColumn('type', 'enum', ['values' => ['reduce', 'increase']])
            ->addColumn('counterId', 'integer')
            ->addColumn('actionId', 'integer')
            ->addColumn('multiplier', 'double')
            ->create()
        ;

        $userCounterTable = $this->table('userCounter');
        $userCounterTable
            ->addColumn('userId', 'integer')
            ->addColumn('counterId', 'integer')
            ->addColumn('count', 'double')
            ->create()
        ;
    }
}
