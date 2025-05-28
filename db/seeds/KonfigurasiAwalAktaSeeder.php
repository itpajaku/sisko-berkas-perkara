<?php


use Phinx\Seed\AbstractSeed;

class KonfigurasiAwalAktaSeeder extends AbstractSeed
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
        $table = $this->table("konfigurasi_akta");
        $table->insert([
            [
                "id" => 1,
                "prefix" => "J ",
                "nomor_akta_terakhir" => 0,
            ],
        ])->saveData();
    }
}
