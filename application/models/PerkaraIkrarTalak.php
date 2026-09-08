<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraIkrarTalak extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_ikrar_talak";
  protected $primaryKey = "perkara_id";
  public $incrementing = false;

  public function perkara()
  {
    return $this->belongsTo(Perkara::class, "perkara_id", "perkara_id");
  }
}
