<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraHakim extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_hakim_pn";

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, 'perkara_id', 'id');
  }
}
