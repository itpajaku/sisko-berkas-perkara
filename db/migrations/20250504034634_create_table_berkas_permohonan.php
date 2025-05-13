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
        $table->addColumn("perkara_id", "integer", ["null" => false]);
        $table->addColumn("nomor_perkara", "string", ["null" => false]);
        $table->addColumn("jenis_perkara", "string", ["null" => false]);
        $table->addColumn("para_pihak", "text");
        $table->addColumn("tanggal_pendaftaran", "date");
        $table->addColumn("majelis", "string", ["null" => false]);
        $table->addColumn("panitera", "string", ["null" => false]);
        $table->addColumn("jurusita", "string", ["null" => false]);
        $table->addColumn("tanggal_putusan", "string", ["null" => false]);
        $table->addTimestamps();
        $table->create();
    }
}
