<?php


if (!function_exists("printdie")) {
  function printdie(...$data)
  {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
  }
}
