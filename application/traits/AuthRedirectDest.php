<?php

namespace App\Traits;

trait AuthRedirectDest
{
  private array $redirectPage = [
    "1003" => "/dashboard",
    "1" => "/admin",
  ];
}
