<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkaraPanitera extends Model
{
  protected $connection = "sipp";
  protected $table = "perkara_panitera_pn";
}