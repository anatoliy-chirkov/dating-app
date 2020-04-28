<?php

use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
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
                'name' => '3 дня',
                'groupId' => 1,
                'duration' => 72,
                'price' => 990,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => '2 недели',
                'groupId' => 1,
                'duration' => 336,
                'price' => 2690,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => '30 дней',
                'groupId' => 1,
                'duration' => 720,
                'price' => 4530,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => '30 дней',
                'groupId' => 2,
                'duration' => 720,
                'price' => 690,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => '2 недели',
                'groupId' => 3,
                'duration' => 336,
                'price' => 290,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => '30 дней',
                'groupId' => 3,
                'duration' => 720,
                'price' => 490,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => 'Разовое поднятие анкеты',
                'groupId' => 4,
                'duration' => 0,
                'price' => 190,
                'isFree' => false,
                'isActive' => true,
            ],
            [
                'name' => 'Пробный премиум',
                'groupId' => 1,
                'duration' => 24,
                'price' => 0,
                'isFree' => true,
                'isActive' => true,
            ],
        ];

        $products = $this->table('product');
        $products->insert($data)->save();
    }
}
