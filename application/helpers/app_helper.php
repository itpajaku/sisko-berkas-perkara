<?php

use App\Libraries\Sysconf;

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

if (!function_exists("pejabat_to_nip")) {
  function pejabat_to_nip($pejabat)
  {
    $listnip = [
      Sysconf::getVar()->PanSekNama => Sysconf::getVar()->PanSekNIP,
      Sysconf::getVar()->PlhPanitera => Sysconf::getVar()->PlhPaniteraNip,
      Sysconf::getVar()->KetuaPNNama => Sysconf::getVar()->KetuaPNNIP,
      Sysconf::getVar()->WakilKetuaPNNama => Sysconf::getVar()->WakilKetuaPNNIP,
      Sysconf::getVar()->WaSekNama => Sysconf::getVar()->WaSekNIP,
      Sysconf::getVar()->PlhKetua => Sysconf::getVar()->PlhKetuaNip,
    ];

    return $listnip[$pejabat] ?? null;
  }
}

if (!function_exists("nama_to_jabatan")) {
  function nama_to_jabatan($pejabat)
  {
    $listnip = [
      Sysconf::getVar()->PanSekNama => "Panitera " .  ucwords(strtolower(Sysconf::getVar()->NamaPN)),
      Sysconf::getVar()->PlhPanitera => "Pelaksana Harian Panitera " . ucwords(strtolower(Sysconf::getVar()->NamaPN)),
      Sysconf::getVar()->KetuaPNNama => "Ketua " . ucwords(strtolower(Sysconf::getVar()->NamaPN)),
      Sysconf::getVar()->WakilKetuaPNNama => "Wakil Ketua " . ucwords(strtolower(Sysconf::getVar()->NamaPN)),
      Sysconf::getVar()->WaSekNama => "Sekretaris " . ucwords(strtolower(Sysconf::getVar()->NamaPN)),
      Sysconf::getVar()->PlhKetua => "Pelaksana Harian Ketua " . ucwords(strtolower(Sysconf::getVar()->NamaPN)),
    ];

    return $listnip[$pejabat] ?? null;
  }
}
