<?php

use Phinx\Migration\AbstractMigration;

class CreateAdvantageModule extends AbstractMigration
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
        $permissionTable = $this->table('permission');
        $permissionTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->create()
        ;

        $accessTable = $this->table('access');
        $accessTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->create()
        ;

        $advantageGroupTable = $this->table('advantageGroup');
        $advantageGroupTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->create()
        ;

        $advantageTable = $this->table('advantage');
        $advantageTable
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('groupId', 'integer')
            ->addColumn('accessId', 'integer')
            ->addColumn('duration', 'integer')
            ->addColumn('price', 'double')
            ->addColumn('isActive', 'boolean', ['default' => false])
            ->create()
        ;

        $advantagePermissionTable = $this->table('advantagePermission');
        $advantagePermissionTable
            ->addColumn('advantageId', 'integer')
            ->addColumn('permissionId', 'integer')
            ->create()
        ;

        $userAdvantageTable = $this->table('userAdvantage');
        $userAdvantageTable
            ->addColumn('userId', 'integer')
            ->addColumn('advantageId', 'integer')
            ->addColumn('createdAt', 'datetime')
            ->addColumn('expiredAt', 'datetime')
            ->create()
        ;
    }
}
