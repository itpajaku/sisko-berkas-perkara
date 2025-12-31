<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddActiveStatusToAllMenusTable extends AbstractMigration
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
        $sectionMenus = $this->table("menu_section");
        $sectionMenus->addColumn("is_active", "boolean", ["default" => true, "after" => "header"]);
        $sectionMenus->update();

        $menus = $this->table("menus");
        $menus->addColumn("is_active", "boolean", ["default" => true, "after" => "icon"]);
        $menus->update();

        $subMenus = $this->table("sub_menus");;
        $subMenus->addColumn("is_active", "boolean", ["default" => true, "after" => "icon"]);
        $subMenus->update();
    }
}
