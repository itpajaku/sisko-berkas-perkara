<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraPemberitahuanPutusan extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_putusan_pemberitahuan_putusan";

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }

  public function pihak_1()
  {
    return $this->belongsTo(PerkaraPihak1::class, "pihak_id", "id");
  }

  public function pihak_2()
  {
    return $this->belongsTo(PerkaraPihak2::class, "pihak_id", "id");
  }
}
