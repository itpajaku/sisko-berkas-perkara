<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableBerkasGugatan extends AbstractMigration
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
        $table = $this->table('berkas_gugatan');
        $table->addColumn('perkara_id', 'integer', ['limit' => 11])
            ->addColumn('nomor_perkara', 'string', ['limit' => 32])
            ->addColumn('para_pihak', 'string', ['limit' => 255])
            ->addColumn('tanggal_pendaftaran', 'date')
            ->addColumn('jenis_perkara', 'string', ['limit' => 32])
            ->addColumn('majelis_hakim', 'string')
            ->addColumn('panitera', 'string')
            ->addColumn('jurusita', 'string')
            ->addColumn('tanggal_putusan', 'date')
            ->addColumn('tanggal_pbt', 'date', ['null' => true])
            ->addColumn('tanggal_bht', 'date', ["null" => true])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 0])
            ->addColumn('tanggal_terima', 'date', ['null' => true])
            ->addColumn('keterangan', 'text')
            ->addTimestamps()
            ->create();
    }
}
