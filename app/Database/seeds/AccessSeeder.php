<?php


use Phinx\Seed\AbstractSeed;

class AccessSeeder extends AbstractSeed
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
                'name' => 'afterBuy',
            ],
            [
                'name' => 'afterRegistration',
            ],
        ];

        $posts = $this->table('access');
        $posts->insert($data)->save();
    }
}
