<?php


use Phinx\Seed\AbstractSeed;

class ActionSeeder extends AbstractSeed
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
                'name' => 'sendMessage',
                'canReduce' => true,
            ],
            [
                'name' => 'sendMessageToGirl',
                'canReduce' => true,
            ],
            [
                'name' => 'seeVisits',
                'canReduce' => true,
            ],
            [
                'name' => 'hideVisit',
                'canReduce' => true,
            ],
            [
                'name' => 'hideOnline',
                'canReduce' => true,
            ],
            [
                'name' => 'bulkMessage',
                'canReduce' => true,
            ],
            [
                'name' => 'putMoney',
                'canReduce' => false,
            ],
            [
                'name' => 'newDay',
                'canReduce' => false,
            ],
            [
                'name' => 'registration',
                'canReduce' => false,
            ],
        ];

        $posts = $this->table('action');
        $posts->insert($data)->save();
    }
}
