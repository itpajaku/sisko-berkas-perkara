<?php

namespace App\Libraries;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Eloquent
{
  public Capsule $capsule;
  private static Capsule $instance;

  public function boot()
  {
    $capsule = new Capsule;

    $capsule->addConnection([
      'driver' => 'mysql',
      'host' => $_ENV["DB_HOST_SIPP"],
      'database' => $_ENV["DB_NAME_SIPP"],
      'username' => $_ENV["DB_USER_SIPP"],
      'password' => $_ENV["DB_PASS_SIPP"],
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ], 'sipp');

    $capsule->addConnection([
      'driver' => 'mysql',
      'host' => $_ENV["DB_HOST"],
      'database' => $_ENV["DB_NAME"],
      'username' => $_ENV["DB_USER"],
      'password' => $_ENV["DB_PASS"],
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ]);

    if (php_sapi_name() !== 'cli') {
      $capsule->setEventDispatcher(new Dispatcher(new Container));
      $capsule->setAsGlobal();
    }

    $capsule->bootEloquent();

    $this->capsule = &$capsule;
    self::$instance = &$capsule;


    return $this;
  }

  public static function &get_instance()
  {
    if (!self::$instance) {
      throw new \Exception("Capsule belum di inisialisasi", 1);
    }
    return self::$instance;
  }
}
