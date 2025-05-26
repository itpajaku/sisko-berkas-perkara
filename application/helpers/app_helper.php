<?php


if (!function_exists("sipp_url")) {
  function sipp_url($endpoint)
  {
    if ($endpoint[0] !== '/') {
      $endpoint = '/' . $endpoint;
    }

    return $_ENV['SIPP_URL'] . $endpoint;
  }
}
