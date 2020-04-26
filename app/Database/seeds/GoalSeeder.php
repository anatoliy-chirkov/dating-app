<?php

use Phinx\Seed\AbstractSeed;

class GoalSeeder extends AbstractSeed
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
                'name' => 'Поиск друзей',
                'icon' => '✌️',
            ],
            [
                'name' => 'Постоянные отношения',
                'icon' => '💍',
            ],
//            [
//                'name' => 'Романтические отношения',
//                'icon' => '💕',
//            ],
            [
                'name' => 'Провести вечер',
                'icon' => '🍷',
            ],
            [
                'name' => 'Совместное путешествие',
                'icon' => '✈️',
            ],
            [
                'name' => 'Ищу спонсора',
                'icon' => '💰',
            ],
            [
                'name' => 'Стану спонсором',
                'icon' => '💵',
            ],
        ];

        $posts = $this->table('goal');
        $posts->insert($data)->save();
    }
}
