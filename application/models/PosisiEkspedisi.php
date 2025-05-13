<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosisiEkspedisi extends Model
{
  protected $table = "posisi_ekspedisi";

  protected $guarded = [];

  public function berkas_gugatan()
  {
    return $this->morphedByMany(BerkasGugatan::class, 'berkas');
  }
}
