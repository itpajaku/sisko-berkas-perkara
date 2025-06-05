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
        $aksesAdmin = [
            [
                'group_id' => 1,
                'menu_section_id' => 1,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 2,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 3,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 4,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 5,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 6,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 7,
            ],
            [
                'group_id' => 1,
                'menu_section_id' => 8,
            ],
        ];

        $aksesPanmudHukum = [
            [
                'group_id' => 430,
                'menu_section_id' => 8,
            ],
        ];

        $aksesPanmudGugatan = [
            [
                'group_id' => 1000,
                'menu_section_id' => 6,
            ],
        ];

        $aksesPanmudPermohonan = [
            [
                'group_id' => 1010,
                'menu_section_id' => 7,
            ],
        ];

        $aksesMeja3 = [
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
        ];


        $table->insert(array_merge(
            $aksesAdmin,
            $aksesPanmudHukum,
            $aksesPanmudGugatan,
            $aksesPanmudPermohonan,
            $aksesMeja3
        ));
        $table->saveData();
    }
}
