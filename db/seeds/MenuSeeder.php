<?php


use Phinx\Seed\AbstractSeed;

class MenuSeeder extends AbstractSeed
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
        $table = $this->table('menus');
        $table->insert([
            [
                'title' => 'Dashboard',
                "section_id" => 1,
                "is_sub" => false,
                "link" => "/dashboard",
                "icon" => "ti ti-layout-dashboard",
                "id" => 1
            ],
            [
                "id" => 2,
                'title' => 'Daftar Berkas',
                "section_id" => 2,
                "is_sub" => false,
                "link" => "/berkas_gugatan/register",
                "icon" => "ti ti-books"
            ],
            [
                "id" => 3,
                'title' => 'BHT Hari Ini',
                "section_id" => 2,
                "is_sub" => false,
                "link" => "/bht_hari_ini",
                "icon" => "ti ti-gavel"
            ],
            [
                "id" => 4,
                'title' => 'Laporan',
                "section_id" => 2,
                "is_sub" => false,
                "link" => "/berkas_gugatan/laporan",
                "icon" => "ti ti-report"
            ],
            [
                "id" => 5,
                'title' => 'Daftar Berkas',
                "section_id" => 3,
                "is_sub" => false,
                "link" => "/berkas_permohonan/register",
                "icon" => "ti ti-books"
            ],
            [
                "id" => 6,
                'title' => 'Laporan Berkas',
                "section_id" => 3,
                "is_sub" => false,
                "link" => "/berkas_permohonan/laporan",
                "icon" => "ti ti-report"
            ],
            [
                "id" => 7,
                "title" => 'Daftar Akta',
                "section_id" => 4,
                "is_sub" => false,
                "link" => "/akta_cerai/register",
                "icon" => "ti ti-books"
            ],
            [
                "id" => 8,
                "title" => "Konfigurasi Akta",
                "section_id" => 4,
                "is_sub" => false,
                "link" => "/akta_cerai/konfigurasi",
                "icon" => "ti ti-settings"
            ],
            [
                "id" => 9,
                "title" => "Laporan ",
                "section_id" => 4,
                "is_sub" => false,
                "link" => "/akta_cerai/laporan",
                "icon" => "ti ti-report"
            ],
            [
                "id" => 10,
                'title' => 'Akses Menu',
                "section_id" => 5,
                "is_sub" => false,
                "link" => "/pengaturan/akses_menu",
                "icon" => "ti ti-settings"
            ],
            [
                "id" => 11,
                'title' => 'Pengaturan Akun',
                "section_id" => 5,
                "is_sub" => false,
                "link" => "/pengaturan/akun",
                "icon" => "ti ti-user"
            ]
        ])->saveData();
    }
}
