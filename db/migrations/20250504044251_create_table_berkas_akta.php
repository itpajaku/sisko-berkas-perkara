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
        $table->addColumn("perkara_id", "integer", ["null" => false]);
        $table->addColumn("nomor_perkara", "string", ["null" => false]);
        $table->addColumn("nomor_ac", "string", ["null" => false]);
        $table->addColumn("nomor_seri", "integer", ["null" => false, "limit" => 6]);
        $table->addColumn("kode_seri", "string", ["null" => false, "limit" => 2]);
        $table->addColumn("tanggal_akta", "date", ["null" => false]);
        $table->addColumn("tanggal_pbt", "date");
        $table->addColumn("tanggal_arsip", "date");
        $table->addTimestamps();
        $table->create();
    }
}
