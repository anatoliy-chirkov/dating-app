<?php


use Phinx\Seed\AbstractSeed;

class PhinxlogSeeder extends AbstractSeed
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
                'version' => '20200415074701',
                'migration_name' => 'CreateUserTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084240',
                'migration_name' => 'CreateChatTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084246',
                'migration_name' => 'CreateChatUserTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084255',
                'migration_name' => 'CreateEntryTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084300',
                'migration_name' => 'CreateTokenTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084306',
                'migration_name' => 'CreateImageTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084312',
                'migration_name' => 'CreateMessageTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
            [
                'version' => '20200415084321',
                'migration_name' => 'CreateGoogleGeoTable',
                'start_time' => '2020-04-15 09:21:02',
                'end_time' => '2020-04-15 09:21:02',
                'breakpoint' => 0,
            ],
        ];

        $phinxlog = $this->table('phinxlog');
        $phinxlog->insert($data)->save();
    }
}
