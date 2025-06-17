<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasGugatan extends Model
{
  protected $connection = "default";
  protected $table = "berkas_gugatan";
  protected $guarded = [];

  public function ekspedisi()
  {
    return $this->morphToMany(PosisiEkspedisi::class, "berkas", "berkas_ekspedisi", "berkas_id", "save_point");
  }

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }

  public function berkas_ekspedisi()
  {
    return $this->morphMany(BerkasEkspedisi::class, "berkas");
  }

  public function berkas_akta()
  {
    return $this->hasOne(BerkasAkta::class, "perkara_id", "perkara_id");
  }
}
