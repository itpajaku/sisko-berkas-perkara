<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hakim extends Model
{
  protected $connection = "sipp";
  protected $table = "hakim_pn";

  public function perkara_hakim()
  {
    return $this->hasMany(PerkaraHakim::class, 'hakim_id', 'id');
  }
}
