<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasAkta;
use Illuminate\Support\Facades\Request;

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
                "majelis" => RequestBody::post("majelis_hakim"),
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

    public function datatable()
    {
        $draw = RequestBody::post('draw');
        $start = RequestBody::post('start');
        $length = RequestBody::post('length');
        $search = RequestBody::post('search')['value'];

        $query = BerkasAkta::select("*");

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q
                    ->where("nomor_perkara", "like", "$search%")
                    ->orWhere("majelis_hakim", "like", "$search%")
                    ->orWhere("para_pihak", "like", "$search%")
                    ->orWhere("panitera", "like", "$search%")
                    ->orWhere("jurusita", "like", "$search%")
                    ->orWhere("nomor_seri", "like", "$search%")
                    ->orWhere("nomor_akta", "like", "$search%");
            });
        }

        $filter = RequestBody::get()->filter ?? null;
        if ($filter) {
            if (RequestBody::get()->type == "range") {
                $query->whereDate($filter, ">=", RequestBody::get()->start);
                $query->whereDate($filter, "<=", RequestBody::get()->end);
            }
        }

        $total = BerkasAkta::selectRaw("COUNT(*) as total")->first()->total;
        $filtered = $query->count();
        $data = $query->orderBy("created_at", "desc")->offset($start)->limit($length)->get();

        $data->transform(function ($item, $n) {
            $item->no = ++$n;
            $item->tanggal_pendaftaran = tanggal_indo($item->tanggal_pendaftaran, false);
            $item->tanggal_putusan = tanggal_indo($item->tanggal_putus, false);


            $item->action = Templ::component("akta_cerai/kolom_aksi", [
                "berkas" => $item
            ]);

            return $item;
        });

        return [
            'draw' => intval($draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data->all()
        ];
    }

    public function updateOne($id): void
    {
        $eloquent = Eloquent::get_instance();
        try {
            $eloquent->connection("default")->beginTransaction();
            BerkasAkta::where("id", $id)->update([
                "nomor_perkara" => RequestBody::post("nomor_perkara"),
                "perkara_id" => Hashid::singleDecode(RequestBody::post("perkara_id")),
                "jenis_perkara" => RequestBody::post("jenis_perkara"),
                "tanggal_pendaftaran" => RequestBody::post("tanggal_pendaftaran"),
                "para_pihak" => RequestBody::post("para_pihak"),
                "majelis" => RequestBody::post("majelis_hakim"),
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

            $eloquent->connection("default")->commit();
        } catch (\Throwable $th) {
            $eloquent->connection("default")->rollBack();
            throw $th;
        }
    }

    public function sinkron_sipp($id)
    {
        $akta = BerkasAkta::findOrFail($id);
        $arsipAkta = Eloquent::get_instance()->connection("sipp")
            ->table("perkara_akta_cerai")
            ->where("perkara_id", $akta->perkara_id)
            ->first();

        if (!$arsipAkta) {
            throw new \Exception("Arsip perkara ini belum ada di SIPP", 1);
        }

        $akta->update([
            "tanggal_arsip" => date("Y-m-d", strtotime($arsipAkta->diperbaharui_tanggal)),
            "tanggal_diterima" => date("Y-m-d"),
            "status" => 1, // 1 = sudah sinkron
        ]);
    }

    public function hapus_sinkron_sipp($id)
    {
        $akta = BerkasAkta::findOrFail($id);

        $akta->update([
            "tanggal_arsip" => null,
            "tanggal_diterima" => null,
            "status" => 0, // 1 = sudah sinkron
        ]);
    }
}
