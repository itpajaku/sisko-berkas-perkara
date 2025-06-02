<?php

namespace App\Traits;

trait BerkasGugatanValidation
{
  public function validation($body, $validator)
  {
    $validator->set_data($body);

    if (isset($body["jenis_perkara"])) {
      $validator->set_rules(
        'jenis_perkara',
        'Jenis Perkara',
        'required|max_length[32]'
      );
    }
    if (isset($body["nomor_perkara"])) {
      $validator->set_rules(
        'nomor_perkara',
        'Nomor Perkara',
        'required|max_length[32]'
      );
    }
    if (isset($body["tanggal_putusan"])) {
      $validator->set_rules(
        'tanggal_putusan',
        'Tanggal Putusan',
        'required|trim'
      );
    }
    if (isset($body["tanggal_pendaftaran"])) {
      $validator->set_rules(
        'tanggal_pendaftaran',
        'Tanggal Pendaftaran',
        'required|trim'
      );
    }
    if (isset($body["tanggal_pbt"])) {
      $validator->set_rules(
        'tanggal_pbt',
        'Tanggal PBT',
        'trim'
      );
    }
    if (isset($body["tanggal_bht"])) {
      $validator->set_rules(
        'tanggal_bht',
        'Tanggal BHT',
        'trim'
      );
    }
    if (isset($body["para_pihak"])) {
      $validator->set_rules(
        'para_pihak',
        'Para Pihak',
        'required|max_length[255]'
      );
    }
    if (isset($body["majelis_hakim"])) {
      $validator->set_rules(
        'majelis_hakim',
        'Majelis Hakim',
        'required|max_length[255]'
      );
    }
    if (isset($body["panitera"])) {
      $validator->set_rules(
        'panitera',
        'Panitera Pengganti',
        'required|max_length[255]'
      );
    }
    if (isset($body["jurusita"])) {
      $validator->set_rules(
        'jurusita',
        'Jurusita',
        'required|max_length[255]'
      );
    }
    if (isset($body["keterangan"])) {
      $validator->set_rules(
        'keterangan',
        'Keterangan',
        'max_length[255]'
      );
    }

    if (
      (isset($body["tanggal_bht"]) && $body["tanggal_bht"] != null) && (isset($body["tanggal_pbt"]) && $body["tanggal_pbt"] != null)
    ) {
      if ($body["tanggal_bht"] < $body["tanggal_pbt"]) {
        throw new \Exception("Validaiton error: BHT tidak boleh kurang dari pbt", 1);
      }
    }


    if ($validator->run() == false) {
      throw new \Exception("Validaiton error:" . validation_errors("Validation error"), 1);
    }
  }
}
