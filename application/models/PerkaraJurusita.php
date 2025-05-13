<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraJurusita extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_jurusita";

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }
}
