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
                'name' => 'putOneRuble',
            ],
            [
                'name' => 'putOneDollar',
            ],
            [
                'name' => 'putOneEuro',
            ],
            [
                'name' => 'sendMessageToGirl',
            ],
            [
                'name' => 'newDay',
            ],
            [
                'name' => 'registration',
            ],
        ];

        $posts = $this->table('action');
        $posts->insert($data)->save();
    }
}
