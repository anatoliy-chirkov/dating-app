<?php


use Phinx\Seed\AbstractSeed;

class PermissionSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name' => 'sendMessageToGirl',
            ],
            [
                'name' => 'seeVisits',
            ],
            [
                'name' => 'hideVisit',
            ],
            [
                'name' => 'hideOnline',
            ],
            [
                'name' => 'bulkMessage',
            ],
        ];

        $posts = $this->table('permission');
        $posts->insert($data)->save();
    }
}
