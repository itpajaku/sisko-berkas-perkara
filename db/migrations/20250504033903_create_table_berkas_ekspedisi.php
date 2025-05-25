<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableBerkasEkspedisi extends AbstractMigration
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
        $table = $this->table("berkas_ekspedisi");
        $table->addColumn("save_point", "string", ["null" => false]);
        $table->addColumn("save_time", "timestamp", ["null" => true]);
        $table->addColumn("berkas_id", "integer", ["null" => false]);
        $table->addColumn("berkas_type", "string");
        $table->addColumn("created_by", "string", ["limit" => 191]);
        $table->addTimestamps();
        $table->create();
    }
}
