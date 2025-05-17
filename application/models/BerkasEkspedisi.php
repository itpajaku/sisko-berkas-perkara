<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasEkspedisi extends Model
{
  protected $table = "berkas_ekspedisi";
  protected $guarded = [];

  protected $casts = [
    'save_time' => 'datetime:Y-m-d H:i:s',
  ];

  public function berkas()
  {
    return $this->morphTo("berkas");
  }

  public function posisi_ekspedisi()
  {
    return $this->belongsTo(PosisiEkspedisi::class, "save_point", "id");
  }
}
