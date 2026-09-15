<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusMediasi extends Model
{
  protected $connection = "sipp";
  protected $table = "status_mediasi";
  protected $primaryKey = "id";
}
