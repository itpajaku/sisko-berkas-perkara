<?php


if (!function_exists("sipp_url")) {
  function sipp_url($par)
  {
    $url = $_ENV["SIPP_URL"] . "/" . $par;
    $url = str_replace('//', '/', $url);
    return $url;
  }
}
