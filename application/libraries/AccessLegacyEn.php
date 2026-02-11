<?php

namespace App\Libraries;

class AccessLegacyEn
{
  protected static $initialized = false;
  public static function _init()
  {
    if (self::$initialized) {
      return;
    }
    $ci = &get_instance();
    $ci->load->library("Legacyen");
    $ci->legacyen->set_key($_ENV["SIPP_APP_KEY"]);
    self::$initialized = true;
  }

  public static function encode($par): string
  {
    self::_init();
    $ci = &get_instance();
    return $ci->legacyen->encode($par);
  }

  public static function decode($par = ""): string
  {
    self::_init();
    $ci = &get_instance();
    return $ci->legacyen->decode($par);
  }
}
