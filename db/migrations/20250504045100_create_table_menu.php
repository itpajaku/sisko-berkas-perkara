<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableMenu extends AbstractMigration
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
        $table = $this->table("menus");
        $table->addColumn("section_id", "integer");
        $table->addColumn("title", "string");
        $table->addColumn("is_sub", "integer", ["limit" => 1]);
        $table->addColumn("link", "string");
        $table->addColumn("icon", "string");
        $table->addTimestamps();
        $table->create();
    }
}
