<?php

namespace App\Models;

use App\Models\Perkara;
use Illuminate\Database\Eloquent\Model;

class PerkaraAktaCerai extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_akta_cerai";

  public function perkara()
  {
    $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }
}
