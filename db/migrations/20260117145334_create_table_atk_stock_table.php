<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateTableAtkStockTable extends AbstractMigration
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
		$table = $this->table('atk_stock');
		$table->addColumn("atk_item_id", "integer", ["null" => false]);
		$table->addColumn("stock", "integer", ["null" => false]);
		$table->addColumn("tahun", "integer", ["null" => false, "limit" => MysqlAdapter::INT_SMALL]);
		$table->addTimestamps();
		$table->create();
	}
}
