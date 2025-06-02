<?php

namespace App\Libraries;

class RequestBody
{
  protected static $ci;
  protected static $queryObj;
  protected static $input = [];
  protected static $inputCollection;
  protected static $inputObj;

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
  public static function post(string $par = "")
  {
    self::init();

    if (self::$ci->input->method() == "post") {
      if ($par) {
        if (is_array(self::$ci->input->post($par, true))) {
          return self::$ci->input->post($par, true);
        }
        return htmlspecialchars(self::$ci->input->post($par, true), ENT_QUOTES, "utf-8");
      }
      self::$input = self::$ci->input->post();
    } else {
      $rawinput = file_get_contents("php://input");

      if (isset(self::$ci->input->request_headers()["application/json"])) {
        self::$input = json_decode($rawinput);
      } else {
        parse_str($rawinput, self::$input);
      }
    }

    if ($par) {
      return htmlspecialchars(self::$input[$par], ENT_QUOTES, "utf-8");
    }

    foreach (self::$input as $key => $value) {
      $input[$key] = htmlspecialchars($value, ENT_QUOTES, "utf-8");
    }

    if (self::$inputCollection === null) {
      $container = [];

      foreach (self::$input as $key => $value) {
        $container[$key] = htmlspecialchars($value, ENT_QUOTES, "utf-8");
      }
      self::$inputCollection = collect($container);
    }

    return self::$inputCollection;
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
