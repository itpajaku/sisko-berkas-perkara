<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as DB;

class PerkaraBelumArsipDataTable
{
  protected string $connection = 'sipp';
  protected string $table = 'perkara';

  protected array $columnOrder = [
    'nomor_perkara',
    'jenis_perkara_nama',
    'majelis_jakim_nama',
    'panitera_pengganti_text',
    'tanggal_putusan',
    'tanggal_bht'
  ];

  protected array $columnSearch = [
    'nomor_perkara',
    'jenis_perkara_nama',
    'majelis_jakim_nama',
    'panitera_pengganti_text',
    'tanggal_putusan',
    'tanggal_bht'
  ];

  protected array $defaultOrder = [
    'perkara.perkara_id' => 'desc'
  ];

  /* ==========================================================
       | Base Query
       ========================================================== */
  private function baseQuery()
  {
    $query = DB::connection($this->connection)
      ->table($this->table)
      ->select([
        'perkara.perkara_id',
        'perkara.nomor_perkara',
        'perkara.jenis_perkara_nama',
        'perkara_penetapan.majelis_hakim_nama',
        'perkara_penetapan.panitera_pengganti_text',
        'perkara_putusan.tanggal_putusan',
        'perkara_putusan.tanggal_bht'
      ])
      ->leftJoin(
        'perkara_putusan',
        'perkara.perkara_id',
        '=',
        'perkara_putusan.perkara_id'
      )
      ->leftJoin(
        'perkara_penetapan',
        'perkara.perkara_id',
        '=',
        'perkara_penetapan.perkara_id'
      )
      ->whereNotExists(function ($q) {
        $q->select(DB::raw(1))
          ->from('arsip')
          ->whereRaw('arsip.perkara_id = perkara_putusan.perkara_id');
      })
      ->whereNotNull('perkara_putusan.tanggal_bht');

    /* ================= FILTER ================= */
    if (isset($_POST['filter'])) {

      if (($_POST['type'] ?? null) === 'range') {
        $start = $_POST['start'] ?? null;
        $end   = $_POST['end'] ?? null;

        if ($start && $end) {
          $query->whereBetween('perkara.created_at', [$start, $end]);
        }
      }

      if (($_POST['type'] ?? null) === 'year') {
        $year = $_POST['year'] ?? null;

        if ($year) {
          $query->whereYear('perkara.created_at', $year);
        }
      }
    }

    /* ================= SEARCH ================= */
    if (!empty($_POST['search']['value'])) {
      $search = $_POST['search']['value'];

      $query->where(function ($q) use ($search) {
        foreach ($this->columnSearch as $column) {
          $q->orWhere("perkara.$column", 'LIKE', "%{$search}%");
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
    return DB::connection($this->connection)
      ->table($this->table)
      ->count();
  }
}
