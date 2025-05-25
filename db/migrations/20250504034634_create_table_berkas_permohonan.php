<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableBerkasPermohonan extends AbstractMigration
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
        $table = $this->table("berkas_permohonan");
        $table->addColumn("hash_id", "string", ["null" => true]);
        $table->addColumn("perkara_id", "integer");
        $table->addColumn("nomor_perkara", "string");
        $table->addColumn("jenis_perkara", "string");
        $table->addColumn("para_pihak", "text");
        $table->addColumn("tanggal_pendaftaran", "date");
        $table->addColumn("majelis_hakim", "string");
        $table->addColumn("panitera", "string");
        $table->addColumn("jurusita", "string");
        $table->addColumn("tanggal_putusan", "string");
        $table->addColumn("keterangan", "text");
        $table->addColumn("status", "boolean", ["default" => false]);
        $table->addColumn("tanggal_diterima", "date", ["null" => true]);
        $table->addTimestamps();
        $table->create();
    }
}
