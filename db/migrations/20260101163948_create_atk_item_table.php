<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAtkItemTable extends AbstractMigration
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
		$table = $this->table("atk_item");
		$table
			->addColumn("name", "string", ["null" => false])
			->addColumn("type", "enum", [
				"values" => ["consume", "assets", "etc"],
				"default" => "consume",
			])
			->addColumn("status", "boolean", ["default" => true])
			->addColumn("icon", "string", [
				"limit" => 32,
				"default" => "ti ti-pencil",
			])
			->addColumn("desc", "string", ["limit" => 512])
			->addTimestamps()
			->create();
	}
}
