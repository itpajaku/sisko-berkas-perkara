<?php

namespace App\Libraries;

class DateHelper
{
  /**
   * Singleton untuk DateTime hari ini.
   */
  protected static ?\DateTime $today = null;

  /**
   * Mengembalikan instance DateTime untuk hari ini (disimpan sebagai singleton).
   *
   * @return \DateTime
   */
  protected static function getToday(): \DateTime
  {
    if (self::$today === null) {
      self::$today = new \DateTime(); // default = sekarang
    }
    return clone self::$today; // clone agar tidak terubah dari luar
  }

  /**
   * Menghitung jenjang hari (selisih hari) antara dua tanggal.
   *
   * @param string $fromDate Format 'Y-m-d'
   * @param string|null $toDate Format 'Y-m-d' atau null (default: hari ini)
   * @return int Selisih hari (positif atau negatif)
   * @throws Exception
   */
  public static function getDayInterval(string $fromDate, ?string $toDate = null): int
  {
    $from = new \DateTime($fromDate);
    $to = $toDate ? new \DateTime($toDate) : self::getToday();

    return (int)$from->diff($to)->format('%r%a');
  }
}
