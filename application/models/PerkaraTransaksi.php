<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraTransaksi extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_biaya";

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }

  public function detail_pihak()
  {
    return $this->belongsTo(Pihak::class, "pihak_id", "id");
  }
}
