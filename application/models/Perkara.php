<?php

namespace App\Models;

use App\Models\Arsip;
use Illuminate\Database\Eloquent\Model;

class Perkara extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara";

  public function perkara_penetapan()
  {
    return $this->hasOne(PerkaraPenetapan::class, "perkara_id", "perkara_id");
  }

  public function perkara_jurusita()
  {
    return $this->hasMany(PerkaraJurusita::class, "perkara_id", "perkara_id");
  }

  public function perkara_putusan()
  {
    return $this->hasOne(PerkaraPutusan::class, "perkara_id", "perkara_id");
  }

  public function arsip()
  {
    return $this->hasOne(Arsip::class, "perkara_id", "perkara_id");
  }
}
