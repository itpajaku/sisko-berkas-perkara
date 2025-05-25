<?php

namespace App\Libraries;

class RequestBody
{
  protected static $ci;
  protected static $queryObj;

  private static function init()
  {
    if (self::$ci === null) {
      self::$ci = &get_instance();
    }
  }

  private static function initQueryObj()
  {
    if (!self::$queryObj) {
      self::$queryObj = new class {};
      return self::$queryObj;
    }
    return self::$queryObj;
  }

  /**
   * Isi param jika akan menggunakan native post method
   * @param string $par
   */
  public static function post(string $par = null)
  {
    self::init();
    $rawinput = file_get_contents("php://input");
    if (self::$ci->input->method() == "post") {
      if (isset(self::$ci->input->request_headers()["application/json"])) {
        return collect(json_decode($rawinput));
      }

      return self::$ci->input->post($par, true);
    }

    $input = [];
    parse_str($rawinput, $input);
    return collect($input);
  }

  public static function get()
  {
    self::init();
    self::initQueryObj();
    foreach (self::$ci->input->get() as $key => $value) {
      self::$queryObj->{$key} = $value;
    }
    return self::$queryObj;
  }
}
