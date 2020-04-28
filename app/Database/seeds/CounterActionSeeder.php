<?php

use Phinx\Seed\AbstractSeed;

class CounterActionSeeder extends AbstractSeed
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
                'type' => 'increase',
                'counterId' => 1,
                'actionId' => 8,
                'multiplier' => 15,
            ],
            [
                'type' => 'increase',
                'counterId' => 1,
                'actionId' => 7,
                'multiplier' => 3,
                'counterLimit' => 30,
            ],
            [
                'type' => 'increase',
                'counterId' => 1,
                'actionId' => 6,
                'multiplier' => 0.033,
            ],
            [
                'type' => 'reduce',
                'counterId' => 1,
                'actionId' => 9,
                'multiplier' => 1,
            ],
        ];

        $counterActions = $this->table('counterAction');
        $counterActions->insert($data)->save();
    }
}
