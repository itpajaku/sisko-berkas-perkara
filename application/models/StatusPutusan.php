<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPutusan extends Model
{
  protected $connection = "sipp";
  protected $table = "status_putusan";
  protected $primaryKey = "id";
}
