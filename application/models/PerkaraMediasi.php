<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraMediasi extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_mediasi";
  protected $primaryKey = "mediasi_id";

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }

  public function status_mediasi()
  {
    return $this->belongsTo(StatusMediasi::class, "status_mediasi_id", "id");
  }
}
