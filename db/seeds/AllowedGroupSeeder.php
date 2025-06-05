<?php


use Phinx\Seed\AbstractSeed;

class AllowedGroupSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $table = $this->table('allowed_group');
        $table->insert([
            [
                'group_id' => 1, // Admin group
                'group_name' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1003, // Meja 3 Gugatan
                'group_name' => 'Meja 3 Gugatan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 430, // Panmud Hukum
                'group_name' => 'Panmud Hukum',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1000, // Panmud Gugatan
                'group_name' => 'Panmud Gugatan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1010, // Panmud Permohonan
                'group_name' => 'Panmud Permohonan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ])->saveData();
    }
}
