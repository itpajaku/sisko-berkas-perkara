<?php
defined("BASEPATH") or exit("No direct script access allowed");

namespace App\Libraries;

use App\Libraries\Eloquent;

class Sysconf
{
  public Eloquent $eloquent;
  public function __construct(Eloquent $eloquent)
  {
    $this->eloquent = $eloquent;
    $sysdata = $this->eloquent->capsule->connection("sipp")->table("sys_config")->where("id", ">", 60)->get();
    foreach ($sysdata as $row) {
      $this->{$row->name} = $row->value;
    }
  }
}
