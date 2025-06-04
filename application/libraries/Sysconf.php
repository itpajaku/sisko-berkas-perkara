<?php

namespace App\Libraries;


use App\Libraries\Eloquent;

class Sysconf
{
  public Eloquent $eloquent;
  protected static $sysObj;

  protected static $sysArr;

  public function __construct(Eloquent $eloquent)
  {
    $this->eloquent = $eloquent;
    $sysdata = $this->eloquent->capsule->connection("sipp")->table("sys_config")->where("id", ">", 60)->get();
    foreach ($sysdata as $row) {
      $this->{$row->name} = $row->value;
    }
  }

  protected static function init()
  {
    if (!self::$sysObj) {
      $sysdata = Eloquent::get_instance()
        ->connection("sipp")
        ->table("sys_config")
        ->where("id", ">", 60)
        ->get();

      self::$sysObj = new class {};
      foreach ($sysdata as $row) {
        self::$sysObj->{$row->name} = $row->value;
      }
    }
  }

  public static function getVar()
  {
    self::init();
    return self::$sysObj;
  }

  public static function getArr()
  {
    self::init();
    if (!self::$sysArr) {
      self::$sysArr = (array) self::$sysObj;
    }
    return self::$sysArr;
  }
}
