<?php

namespace App\Traits;

trait AuthRedirectDest
{
  private array $redirectPage = [
    "1003" => "meja_3/dashboard",
    "1" => "/admin",
  ];
}
