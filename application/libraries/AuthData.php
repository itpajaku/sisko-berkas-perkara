<?php

namespace App\Libraries;

class AuthData
{
  protected static $app;
  protected static $avatar;

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
    self::initApp();
    if (!isset($_SESSION["app_user_data"])) {
      self::$app->session->set_userdata("http_auth_redirect", $_SERVER["REQUEST_URI"]);
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

  public static function getAvatar()
  {
    self::initApp();
    if (!self::$avatar) {
      self::$avatar = self::$app->eloquent->capsule
        ->table("avatars")
        ->where("user_id", self::getUserData()->userid)
        ->first();
      return self::$avatar;
    }
    return self::$avatar;
  }
}
