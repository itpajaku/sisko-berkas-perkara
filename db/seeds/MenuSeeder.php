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
		$table = $this->table("menus");
		$table->truncate();
		$table
			->insert([
				[
					"id" => 1,
					"title" => "Dashboard",
					"section_id" => 1,
					"is_sub" => false,
					"link" => "/dashboard",
					"icon" => "ti ti-layout-dashboard",
				],
				[
					"id" => 2,
					"title" => "Daftar Berkas",
					"section_id" => 2,
					"is_sub" => false,
					"link" => "/berkas_gugatan/register",
					"icon" => "ti ti-books",
				],
				[
					"id" => 3,
					"title" => "BHT Hari Ini",
					"section_id" => 2,
					"is_sub" => false,
					"link" => "/kalender_bht",
					"icon" => "ti ti-gavel",
				],
				[
					"id" => 4,
					"title" => "Laporan",
					"section_id" => 2,
					"is_sub" => false,
					"link" => "/berkas_gugatan/laporan",
					"icon" => "ti ti-report",
				],
				[
					"id" => 5,
					"title" => "Daftar Berkas",
					"section_id" => 3,
					"is_sub" => false,
					"link" => "/berkas_permohonan/register",
					"icon" => "ti ti-books",
				],
				[
					"id" => 6,
					"title" => "Laporan Berkas",
					"section_id" => 3,
					"is_sub" => false,
					"link" => "/berkas_permohonan/laporan",
					"icon" => "ti ti-report",
				],
				[
					"id" => 7,
					"title" => "Daftar Akta",
					"section_id" => 4,
					"is_sub" => false,
					"link" => "/akta_cerai/register",
					"icon" => "ti ti-books",
				],
				[
					"id" => 8,
					"title" => "Konfigurasi Akta",
					"section_id" => 4,
					"is_sub" => false,
					"link" => "/akta_cerai/konfigurasi",
					"icon" => "ti ti-settings",
				],
				[
					"id" => 9,
					"title" => "Laporan ",
					"section_id" => 4,
					"is_sub" => false,
					"link" => "/akta_cerai/laporan",
					"icon" => "ti ti-report",
				],
				[
					"id" => 10,
					"title" => "Pengaturan Ekspedisi",
					"section_id" => 5,
					"is_sub" => false,
					"link" => "/pengaturan/ekspedisi",
					"icon" => "ti ti-settings",
				],
				[
					"id" => 11,
					"title" => "Pengaturan Akun",
					"section_id" => 5,
					"is_sub" => false,
					"link" => "/pengaturan/akun",
					"icon" => "ti ti-user",
				],
				[
					"id" => 12,
					"title" => "Sinkron Berkas",
					"section_id" => 5,
					"is_sub" => false,
					"link" => "/sinkron/berkas",
					"icon" => "ti ti-cloud-download",
				],
				[
					"id" => 13,
					"title" => "Berkas PBT",
					"section_id" => 2,
					"is_sub" => false,
					"link" => "/berkas_pbt",
					"icon" => "ti ti-phone-outgoing",
				],
				[
					"id" => 14,
					"title" => "Sinkron Akta",
					"section_id" => 5,
					"is_sub" => false,
					"link" => "/sinkron/akta",
					"icon" => "ti ti-cloud-download",
				],
				[
					"id" => 15,
					"title" => "Dashboard",
					"section_id" => 2,
					"is_sub" => false,
					"link" => "/dashboard_gugatan",
					"icon" => "ti ti-home",
				],
				[
					"id" => 16,
					"title" => "Stock Opname ATK",
					"section_id" => 8,
					"is_sub" => false,
					"link" => "stock_opname_atk",
					"icon" => "ti ti-pencil-plus",
				],
				[
					"id" => 17,
					"title" => "Referensi ATK",
					"section_id" => 8,
					"is_sub" => false,
					"link" => "stock_opname_atk/referensi",
					"icon" => "ti ti-pencil-cog",
				],
				[
					"id" => 18,
					"title" => "Statistik ATK",
					"section_id" => 8,
					"is_sub" => false,
					"link" => "stock_opname_atk/dashboard",
					"icon" => "ti ti-pencil-discount",
				],
				[
					"id" => 19,
					"title" => "Laporan ATK",
					"section_id" => 8,
					"is_sub" => false,
					"link" => "stock_opname_atk/laporan",
					"icon" => "ti ti-pencil-check",
				],
				[
					"id" => 20,
					"title" => "Arsip Perkara",
					"section_id" => 9,
					"is_sub" => false,
					"link" => "arsip_perkara",
					"icon" => "ti ti-books",
				],
				[
					"id" => 21,
					"title" => "Dashboard",
					"section_id" => 3,
					"is_sub" => false,
					"link" => "dashboard_permohonan",
					"icon" => "ti ti-layout-dashboard",
				],
				[
					"id" => 22,
					"title" => "Minutasi Perkara",
					"section_id" => 6,
					"is_sub" => false,
					"link" => "/minutasi_perkara_gugatan",
					"icon" => "ti ti-file-check",
				],
				[
					"id" => 23,
					"title" => "Minutasi Perkara",
					"section_id" => 7,
					"is_sub" => false,
					"link" => "/minutasi_perkara_permohonan",
					"icon" => "ti ti-file-check",
				],
				[
					"id" => 24,
					"title" => "Monitoring BAS",
					"section_id" => 7,
					"is_sub" => false,
					"link" => "/monitoring_bas_permohonan",
					"icon" => "ti ti-writing",
				],
			])
			->saveData();
	}
}
