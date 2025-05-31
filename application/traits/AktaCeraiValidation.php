<?php

namespace App\Traits;

trait AktaCeraiValidation
{
    protected $validator;
    private function validationInit()
    {
        if (!$this->validator) {
            $app = &get_instance();
            $this->validator = $app->form_validation;
        }
    }

    private function validate($data)
    {
        $this->validator->set_data($data);

        if (isset($data["jenis_perkara"])) {
            $this->validator->set_rules(
                'jenis_perkara',
                'Jenis Perkara',
                'required|max_length[121]'
            );
        }
        if (isset($data["nomor_perkara"])) {
            $this->validator->set_rules(
                'nomor_perkara',
                'Nomor Perkara',
                'required|max_length[32]'
            );
        }
        if (isset($data["tanggal_putusan"])) {
            $this->validator->set_rules(
                'tanggal_putusan',
                'Tanggal Putusan',
                'required|trim'
            );
        }
        if (isset($data["tanggal_pendaftaran"])) {
            $this->validator->set_rules(
                'tanggal_pendaftaran',
                'Tanggal Pendaftaran',
                'required|trim'
            );
        }
        if (isset($data["para_pihak"])) {
            $this->validator->set_rules(
                'para_pihak',
                'Para Pihak',
                'required|max_length[255]'
            );
        }
        if (isset($data["majelis_hakim"])) {
            $this->validator->set_rules(
                'majelis_hakim',
                'Majelis Hakim',
                'required|max_length[255]'
            );
        }
        if (isset($data["panitera"])) {
            $this->validator->set_rules(
                'panitera',
                'Panitera Pengganti',
                'required|max_length[255]'
            );
        }
        if (isset($data["jurusita"])) {
            $this->validator->set_rules(
                'jurusita',
                'Jurusita',
                'required|max_length[255]'
            );
        }
        if (isset($data["keterangan"])) {
            $this->validator->set_rules(
                'keterangan',
                'Keterangan',
                'max_length[255]'
            );
        }
        if (isset($data["tanggal_akta"])) {
            $this->validator->set_rules(
                'tanggal_akta',
                'Tanggal Akta',
                'trim|required'
            );
        }
        if (isset($data["tanggal_pbt"])) {
            $this->validator->set_rules(
                'tanggal_pbt',
                'Tanggal PBT',
                'trim|required'
            );
        }
        if (isset($data["nomor_akta"])) {
            $this->validator->set_rules(
                'tanggal_pbt',
                'Tanggal PBT',
                'trim|required'
            );
        }
        if (isset($data["nomor_seri"])) {
            $this->validator->set_rules(
                'tanggal_pbt',
                'Tanggal PBT',
                'trim|required'
            );
        }


        if ($this->validator->run() == false) {
            throw new \Exception("Validaiton error:" . validation_errors("Validation error"), 1);
        }
    }
}
