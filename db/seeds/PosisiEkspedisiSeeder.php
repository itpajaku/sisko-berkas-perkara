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
                "posisi" => "Meja 3 Gugatan",
                "keterangan" => "Meja 3 Gugatan"
            ],
            [
                "posisi" => "Meja 2 Gugatan",
                "keterangan" => "Meja 2 Gugatan"
            ],
            [
                "posisi" => "Meja 1 Gugatan",
                "keterangan" => "Meja 1 Gugatan"
            ],
            [
                "posisi" => "Panmud Gugatan",
                "keterangan" => "Panitera Muda Gugatan"
            ],
            [
                "posisi" => "Panmud Permohonan",
                "keterangan" => "Panitera Muda Permohonan"
            ],
            [
                "posisi" => "Panmud Hukum",
                "keterangan" => "Panitera Muda Hukum"
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
