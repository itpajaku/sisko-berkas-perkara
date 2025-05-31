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

if (!function_exists("add_prefix")) {
  function add_zero_leading($par, $len = 4)
  {
    $result = "";
    for ($i = strlen($par); $i < $len; $i++) {
      $result .= "0";
    }

    $result .= $par;
    return $result;
  }
}
