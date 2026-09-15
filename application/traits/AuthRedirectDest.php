<?php

namespace App\Traits;

trait AuthRedirectDest
{
  private array $redirectPage = [
    "1" => "/admin",
    "1000" => "/dashboard",
    "1003" => "/dashboard",
  ];

  protected function getRedirectDestination(string|int|null $groupId): string
  {
    return $this->redirectPage[$groupId] ?? "/dashboard";
  }
}
