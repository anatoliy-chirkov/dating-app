<?php

use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
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
        $userTable = $this->table('user');
        $userTable
            // entry
            ->addColumn('name', 'string', ['limit' => 40])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('passwordHash', 'string', ['limit' => 64])
            // default
            ->addColumn('sex', 'enum', ['values' => ['man', 'woman']])
            ->addColumn('age', 'integer')
            ->addColumn('googleGeoId', 'integer')
            // additional
            ->addColumn('weight', 'integer', ['null' => true])
            ->addColumn('height', 'integer', ['null' => true])
            ->addColumn('about', 'text', ['null' => true])
            // technical
            ->addColumn('money', 'integer', ['default' => 0])
            ->addColumn('connectedAt', 'datetime')
            ->addColumn('isConnected', 'boolean', ['default' => false])
            ->addColumn('createdAt', 'datetime')
        ;
        $userTable->addIndex(['email'], ['unique' => true]);
        $userTable->create();
    }
}
