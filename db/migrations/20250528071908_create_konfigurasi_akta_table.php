<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateKonfigurasiAktaTable extends AbstractMigration
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
        $table = $this->table('konfigurasi_akta');
        $table->addColumn('prefix', 'string', [
            'limit' => 10,
            'default' => '',
            'null' => false,
            'comment' => 'Prefix untuk kode akta cerai',
        ]);
        $table->addColumn('nomor_akta_terakhir', 'integer', [
            'default' => 0,
            'null' => false,
            'comment' => 'Nomor akta terakhir yang digunakan',
        ]);
        $table->addColumn('nomor_seri_terakhir', 'integer', [
            'default' => 0,
            'null' => false,
            'comment' => 'Nomor seri akta yang digunakan',
        ]);
        $table->addTimestamps();
        $table->create();
    }
}
