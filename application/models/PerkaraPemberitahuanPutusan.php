<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraPemberitahuanPutusan extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_putusan_pemberitahuan_putusan";
}
