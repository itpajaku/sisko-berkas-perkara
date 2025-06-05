<?php

use Phinx\Seed\AbstractSeed;

class AccessMenuSectionSeeder extends AbstractSeed
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
        $table = $this->table('access_menu_section');
        $table->insert([
            [
                'group_id' => 1003,
                'menu_section_id' => 1,
            ],
            [
                'group_id' => 1003,
                'menu_section_id' => 2,
            ],
            [
                'group_id' => 1003,
                'menu_section_id' => 3,
            ],
            [
                'group_id' => 1003,
                'menu_section_id' => 4,
            ],
            [
                'group_id' => 1003,
                'menu_section_id' => 5,
            ]
        ]);
        $table->saveData();
    }
}
