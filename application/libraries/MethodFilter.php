<?php

namespace App\Libraries;

class MethodFilter
{
  protected static $app = null;

  public static function must(string $method, string $page_404 = null)
  {
    if (!self::$app) {
      self::$app = &get_instance();
    }

    $method = strtolower($method);
    if (self::$app->input->method() !== $method) {
      if ($page_404) {
        return self::$app->load->view($page_404, [], true);
      }

      return show_404();
    }
  }

  public static function mustHeader(string $header)
  {
    if (!self::$app) {
      self::$app = &get_instance();
    }

    if (!self::isHeader($header)) {
      return show_404();
    }
  }

  public static function isHeader(string $header)
  {
    if (!self::$app) {
      self::$app = &get_instance();
    }

    $targetHeader = false;
    foreach (self::$app->input->request_headers() as $n => $value) {
      if (strtoupper($n) == strtoupper($header)) {
        $targetHeader = true;
      }
    }
    return $targetHeader;
  }
}
