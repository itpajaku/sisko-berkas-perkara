<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Models\BerkasAkta;

class AktaCeraiService
{
    private static $instances = [];

    protected function __construct() {}

    public static function getInstance(): AktaCeraiService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function insertOne()
    {
        try {
            $eloquent = Eloquent::get_instance();
            $eloquent->connection("default")->beginTransaction();
            $akta = BerkasAkta::create([
                "nomor_perkara" => RequestBody::post("nomor_perkara"),
                "perkara_id" => Hashid::singleDecode(RequestBody::post("perkara_id")),
                "jenis_perkara" => RequestBody::post("jenis_perkara"),
                "tanggal_pendaftaran" => RequestBody::post("tanggal_pendaftaran"),
                "para_pihak" => RequestBody::post("para_pihak"),
                "majelis" => RequestBody::post("majelis"),
                "panitera" => RequestBody::post("panitera"),
                "jurusita" => RequestBody::post("jurusita"),
                "nomor_akta" => RequestBody::post("nomor_akta"),
                "nomor_seri" => RequestBody::post("nomor_seri"),
                "tanggal_putus" => RequestBody::post("tanggal_putusan"),
                "tanggal_pbt" => RequestBody::post("tanggal_pbt"),
                "tanggal_bht" => RequestBody::post("tanggal_bht"),
                "tanggal_akta" => RequestBody::post("tanggal_akta"),
                "keterangan" => RequestBody::post("keterangan"),
            ]);

            $akta->ekspedisi()->attach(RequestBody::post("posisi_berkas"), [
                "save_time" => date("Y-m-d H:i:s"),
                "created_by" => AuthData::getUserData()->username
            ]);

            $eloquent->connection("default")->commit();

            return $akta;
        } catch (\Throwable $th) {
            $eloquent->connection("default")->rollBack();
            throw $th;
        }
    }
}
