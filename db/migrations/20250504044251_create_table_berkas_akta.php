<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableBerkasAkta extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table("berkas_akta");
        $table->addColumn("hash_id", "string", ["null" => true, "limit" => 20]);
        $table->addColumn("perkara_id", "integer", ["null" => false]);
        $table->addColumn("nomor_perkara", "string", ["null" => false]);
        $table->addColumn("tanggal_pendaftaran", "date", ["null" => false]);
        $table->addColumn("jenis_perkara", "string", ["null" => false]);
        $table->addColumn("para_pihak", "string", ["null" => false]);
        $table->addColumn("majelis", "string", ["null" => false]);
        $table->addColumn("panitera", "string", ["null" => false]);
        $table->addColumn("jurusita", "string", ["null" => false]);
        $table->addColumn("nomor_akta", "string", ["null" => false]);
        $table->addColumn("nomor_seri", "string", ["null" => false, "limit" => 6]);
        $table->addColumn("tanggal_putus", "date", ["null" => false]);
        $table->addColumn("tanggal_bht", "date", ["null" => true]);
        $table->addColumn("tanggal_akta", "date", ["null" => true]);
        $table->addColumn("tanggal_pbt", "date", ["null" => true]);
        $table->addColumn("tanggal_diterima", "date", ["null" => true]);
        $table->addColumn("tanggal_arsip", "date", ["null" => true]);
        $table->addColumn("status", "boolean", ["default" => false]);
        $table->addColumn("keterangan", "string");
        $table->addTimestamps();
        $table->create();
    }
}
