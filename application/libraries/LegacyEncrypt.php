<?php

namespace App\Libraries;

defined('BASEPATH') or exit('No direct script access allowed');

class LegacyEncrypt
{

  // Cipher dan mode
  private static $cipher = 'aes-256-cbc';

  /**
   * Enkripsi data (menghasilkan string base64 seperti CI Encrypt lama)
   */
  public static function encode($data)
  {
    $key = self::get_hashed_key();
    $iv_size = openssl_cipher_iv_length(self::$cipher);
    $iv = openssl_random_pseudo_bytes($iv_size);

    $encrypted = openssl_encrypt(
      $data,
      self::$cipher,
      $key,
      OPENSSL_RAW_DATA,
      $iv
    );

    // Seperti CI Encrypt: IV + encrypted, lalu base64
    return base64_encode($iv . $encrypted);
  }

  /**
   * Dekripsi data (hasil dari metode encode)
   */
  public static function decode($data)
  {
    $key = self::get_hashed_key();
    $decoded = base64_decode($data);
    $iv_size = openssl_cipher_iv_length(self::$cipher);

    $iv = substr($decoded, 0, $iv_size);
    $ciphertext = substr($decoded, $iv_size);

    return openssl_decrypt(
      $ciphertext,
      self::$cipher,
      $key,
      OPENSSL_RAW_DATA,
      $iv
    );
  }

  /**
   * Hash key sesuai gaya CI Encrypt lama
   */
  private static function get_hashed_key()
  {
    return sha1($_ENV["SIPP_APP_KEY"], true); // 160-bit hashed key
  }
}
