<?php

namespace App\Libraries;

class MethodFilter
{
  public static function must(string $method, string $page_404 = null)
  {
    $app = &get_instance();
    $method = strtolower($method);
    if ($app->input->method() !== $method) {
      if ($page_404) {
        return $app->load->view($page_404, [], true);
      }

      return show_404();
    }
  }

  public static function mustHeader(string $header)
  {
    $app = &get_instance();
    if (!isset($app->input->request_headers()[$header])) {
      return show_404();
    }
  }
}
