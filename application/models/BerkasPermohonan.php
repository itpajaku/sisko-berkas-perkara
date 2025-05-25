<?php

namespace App\Models;

use App\Models\BerkasEkspedisi;
use App\Models\Perkara;
use App\Models\PosisiEkspedisi;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

class BerkasPermohonan extends Model
{
  protected $table = "berkas_permohonan";
  protected $guarded = [];

  protected $hidden = [
    'id',
  ];

  /**
   * The "booted" method of the model.
   *
   * @return void
   */
  protected static function booted()
  {
    static::created(function ($model) {
      $hash = new Hashids();
      $model->hash_id = $hash->encode($model->id);
      $model->save();
    });
  }

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
}
