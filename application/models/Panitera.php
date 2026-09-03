<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panitera extends Model
{
  protected $connection = "sipp";
  protected $table = "panitera_pn";
}