<?php

namespace App\Libraries;

class SippLogin
{
  static public function validate(array $arrInput = [])
  {
    $hasil = '';
    foreach ($arrInput as $val) {
      if ($hasil == '') {
        $hasil = md5($val);
      } else {
        $code = md5($val);
        for ($hit = 0; $hit < min(array(strlen($code), strlen($hasil))); $hit++) {
          $hasil[$hit] = chr(ord($hasil[$hit]) ^ ord($code[$hit]));
        }
      }
    }

    return (md5($hasil));
  }
}
