<?php


use Phinx\Seed\AbstractSeed;

class AccessMenuSeeder extends AbstractSeed
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
        $table = $this->table('access_menu');
        $aksesAdmin = [
            [
                'group_id' => 1,
                'menu_id' => 1,
            ],
            [
                'group_id' => 1,
                'menu_id' => 2,
            ],
            [
                'group_id' => 1,
                'menu_id' => 3,
            ],
            [
                'group_id' => 1,
                'menu_id' => 4,
            ],
            [
                'group_id' => 1,
                'menu_id' => 5,
            ],
            [
                'group_id' => 1,
                'menu_id' => 6,
            ],
            [
                'group_id' => 1,
                'menu_id' => 7,
            ],
            [
                'group_id' => 1,
                'menu_id' => 8,
            ],
            [
                'group_id' => 1,
                'menu_id' => 9,
            ],
            [
                'group_id' => 1,
                'menu_id' => 10,
            ],
            [
                'group_id' => 1,
                'menu_id' => 11,
            ]
        ];

        $aksesMeja3Gugatan = [
            [
                'group_id' => 1003,
                "menu_id" => 1,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 2,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 3,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 4,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 5,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 6,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 7,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 8,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 9,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 10,
            ],
            [
                "group_id" => 1003,
                "menu_id" => 11,
            ],
        ];
        $table->insert(array_merge(
            $aksesAdmin,
            $aksesMeja3Gugatan
        ))->saveData();
    }
}
