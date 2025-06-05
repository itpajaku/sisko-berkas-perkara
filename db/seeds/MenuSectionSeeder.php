<?php


use Phinx\Seed\AbstractSeed;

class MenuSectionSeeder extends AbstractSeed
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
        $table = $this->table('menu_section');
        $table->insert([
            [
                "header" => "Beranda",
                "id" => 1,
            ],
            [
                "header" => "Berkas Gugatan",
                "id" => 2,
            ],
            [
                "header" => "Berkas Permohonan",
                "id" => 3,
            ],
            [
                "header" => "Akta Cerai",
                "id" => 4,
            ],
            [
                "header" => "Pengaturan",
                "id" => 5,
            ],
            [
                "header" => "Panmud Gugatan",
                "id" => 6,
            ],
            [
                "header" => "Panmud Permohonan",
                "id" => 7,
            ],
            [
                "header" => "Panmud Hukum",
                "id" => 8,
            ],
        ]);
        $table->saveData();
    }
}
