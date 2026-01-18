<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateTableAtkTransaksiTable extends AbstractMigration
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
		$table = $this->table("atk_transaksi");
		$table->addColumn("atk_item_id", "integer", ["null" => false]);
		$table->addColumn("waktu", "datetime", ["null" => false]);
		$table->addColumn("restock", "integer", ["limit" => MysqlAdapter::INT_TINY]);
		$table->addColumn("pengeluaran", "integer", ["limit" => MysqlAdapter::INT_TINY]);
		$table->addColumn("input_by", "string", ["null" => false]);
		$table->addColumn("current_stock", "integer", ["comment" => "Stock sebelum transaksi"]);
		$table->addColumn("after_stock", "integer", ["comment" => "Stock setelah transaksi"]);
		$table->addTimestamps();
		$table->create();
	}
}
