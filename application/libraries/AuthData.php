<?php

namespace App\Libraries;

class AuthData
{
  protected static $app;

  private static function initApp()
  {
    if (!self::$app) {
      self::$app = &get_instance();
    }
  }

  public static function isLogedIn()
  {
    self::initApp();
    return isset(self::$app->session->userdata["app_user_data"]);
  }

  public static function authenticatedPass()
  {
    if (!isset($_SESSION["app_user_data"])) {
      if (isset($_SERVER['HTTP_HX_REQUEST'])) {
        header("HX-Redirect: /auth");
        exit;
      }
      redirect("auth");
    }
  }

  public static function getUserData()
  {
    $app = &get_instance();
    return $app->session->userdata("app_user_data") ?? null;
  }
}
