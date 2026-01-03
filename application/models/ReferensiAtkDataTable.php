<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as DB;

class ReferensiAtkDataTable
{
	protected string $table = 'atk_item';

	protected array $columnOrder = [
		'name',
		'type',
		'status',
		'desc',
	];

	protected array $columnSearch = [
		'name',
		'type',
		'status',
		'desc',
	];

	protected array $defaultOrder = [
		'id' => 'desc'
	];

	/* ==========================================================
       | Base Query
       ========================================================== */
	private function baseQuery()
	{
		$query = DB::table($this->table);

		/* ================= FILTER ================= */
		if (isset($_POST['filter'])) {

			if (($_POST['type'] ?? null) === 'range') {
				$start = $_POST['start'] ?? null;
				$end   = $_POST['end'] ?? null;

				if ($start && $end) {
					$query->whereBetween('created_at', [$start, $end]);
				}
			}

			if (($_POST['type'] ?? null) === 'year') {
				$year = $_POST['year'] ?? null;

				if ($year) {
					$query->whereYear('created_at', $year);
				}
			}
		}

		/* ================= SEARCH ================= */
		if (!empty($_POST['search']['value'])) {
			$search = $_POST['search']['value'];

			$query->where(function ($q) use ($search) {
				foreach ($this->columnSearch as $column) {
					$q->orWhere("$column", 'LIKE', "%{$search}%");
				}
			});
		}

		/* ================= ORDER ================= */
		if (isset($_POST['order'][0])) {
			$columnIndex = $_POST['order'][0]['column'];
			$direction   = $_POST['order'][0]['dir'];
			$query->orderBy(
				$this->columnOrder[$columnIndex],
				$direction
			);
		} else {
			foreach ($this->defaultOrder as $col => $dir) {
				$query->orderBy($col, $dir);
			}
		}

		return $query;
	}

	/* ==========================================================
       | Get Data
       ========================================================== */
	public function getDatatables()
	{
		$query = $this->baseQuery();

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$start  = $_POST['start'] ?? 0;
			$length = $_POST['length'];

			$query->offset($start)->limit($length);
		}

		return $query->get();
	}

	/* ==========================================================
       | Count Filtered
       ========================================================== */
	public function countFiltered()
	{
		return $this->baseQuery()->count();
	}

	/* ==========================================================
       | Count All
       ========================================================== */
	public function countAll()
	{
		return DB::table($this->table)
			->count();
	}
}
