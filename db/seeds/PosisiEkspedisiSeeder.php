<?php


use Phinx\Seed\AbstractSeed;

class PosisiEkspedisiSeeder extends AbstractSeed
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
        $table = $this->table("posisi_ekspedisi");
        $table->insert([
            [
                "posisi" => "Meja 3",
                "keterangan" => "Meja 3 Gugatan"
            ],
            [
                "posisi" => "Meja 2",
                "keterangan" => "Meja 2 Gugatan"
            ],
            [
                "posisi" => "Meja 1",
                "keterangan" => "Meja 1 Gugatan"
            ],
            [
                "posisi" => "Panitera Pengganti",
                "keterangan" => "Panitera pengganti yang telah ditetapkan"
            ],
            [
                "posisi" => "Ketua Majelis",
                "keterangan" => "Ketua Majelis Hakim"
            ],
            [
                "posisi" => "Arsip",
                "keterangan" => "Ruang Arsip"
            ],
        ])->saveData();
    }
}
