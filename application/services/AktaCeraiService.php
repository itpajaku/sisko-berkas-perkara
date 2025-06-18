<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\DateHelper;
use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Libraries\Sysconf;
use App\Libraries\Templ;
use App\Models\BerkasAkta;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class AktaCeraiService
{
    private static $instances = [];
    private $app;

    protected function __construct()
    {
        $this->app = &get_instance();
    }

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
            $perkaraId = Hashid::singleDecode(RequestBody::post("perkara_id"));
            $eloquent = Eloquent::get_instance();
            $eloquent->connection("default")->beginTransaction();

            $existedBerkas = BerkasAkta::where("perkara_id", $perkaraId)
                ->first();

            if ($existedBerkas) {
                throw new \Exception("Berkas dengan nomor perkara " . RequestBody::post("nomor_perkara") . " sudah ada", 1);
            }

            $akta = BerkasAkta::create([
                "nomor_perkara" => RequestBody::post("nomor_perkara"),
                "perkara_id" => $perkaraId,
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
                "created_by" => AuthData::getUserData()->username,
                "status" => true
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
            $item->nomor_perkara = Templ::component("components/kolom_nomor_perkara", ["berkas" => $item]);
            $item->tanggal_pendaftaran = tanggal_indo($item->tanggal_pendaftaran, false);
            $item->tanggal_putusan = tanggal_indo($item->tanggal_putus, false);
            $item->majelis = str_replace('\n', "<br>", $item->majelis);
            $item->para_pihak = str_replace('Melawan', "<br>Melawan<br>", $item->para_pihak);
            $item->tanggal_pbt = tanggal_indo($item->tanggal_pbt, false);
            $item->tanggal_bht = tanggal_indo($item->tanggal_bht, false);
            $item->nomor_akta = $item->nomor_akta_cerai;
            $item->tanggal_akta = tanggal_indo($item->tanggal_akta, false);


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

    public function generate_doc()
    {
        $berdasarkan = $this->app->encryption->decrypt(RequestBody::post("berdasarkan"));

        $tanggalAwal = RequestBody::post("tanggal_awal");
        $tanggalAkhir = RequestBody::post("tanggal_akhir");
        $penandatangan = RequestBody::post("penandatangan");

        $berkas = BerkasAkta::whereDate($berdasarkan, ">=", $tanggalAwal)
            ->whereDate($berdasarkan, "<=", $tanggalAkhir)
            ->orderBy($berdasarkan, "asc")
            ->get();

        $docTemplate = new TemplateProcessor("../doc/template/template_laporan_akta.docx");

        $total = $berkas->count();
        $totalMasukBerkas = $berkas->whereNotNull("tanggal_arsip")->count() ?? 0;
        $totalBelumMasukBerkas = $total - $totalMasukBerkas ?? 0;

        $docTemplate->setValue("NAMA_SATKER", Sysconf::getVar()->NamaPN);
        $docTemplate->setValue("ALAMAT_SATKER", Sysconf::getVar()->AlamatPN);
        $docTemplate->setValue("TANGGAL_AWAL", tanggal_indo(RequestBody::post("tanggal_awal")));
        $docTemplate->setValue("TANGGAL_AKHIR", tanggal_indo(RequestBody::post("tanggal_akhir")));
        $docTemplate->setValue("TOTAL_DATA", $total);
        $docTemplate->setValue("TOTAL_MASUK_BERKAS", strval($totalMasukBerkas));
        $docTemplate->setValue("TOTAL_BELUM_ARSIP", strval($totalBelumMasukBerkas));

        $docTemplate->setValue("penandatangan", RequestBody::post("penandatangan"));
        $docTemplate->setValue("nip_penandatangan", pejabat_to_nip(
            RequestBody::post("penandatangan")
        ));
        $docTemplate->setValue("tgl_hari_laporan", tanggal_indo(date("Y-m-d")));
        $docTemplate->setValue("pejabat", nama_to_jabatan(
            RequestBody::post("penandatangan")
        ));

        $docTemplate->cloneRowAndSetValues("no", $berkas->map(function ($item, $n) {
            return [
                "no" => ++$n,
                "nomor_perkara" => $item->nomor_perkara,
                "jenis_perkara" => $item->jenis_perkara,
                "tgl_putus" => tanggal_indo($item->tanggal_putus, false),
                "tgl_pip" => tanggal_indo($item->tanggal_pbt, false),
                "tgl_bht" => tanggal_indo($item->tanggal_bht, false),
                "majelis" => explode('\n', $item->majelis)[0],
                "nomor_akta" => $item->nomor_akta,
                "nomor_seri" => $item->nomor_seri,
                "tgl_akta" => tanggal_indo($item->tanggal_akta, false),
                "panitera" => $item->panitera,
                "selisih" => DateHelper::getDayInterval($item->tanggal_bht, $item->tanggal_arsip) . " Hari",
                "masuk" => $item->tanggal_arsip ?? "Masih Berjalan",
            ];
        })->all());

        $fileName = "Laporan_Akta_Cerai_" . date("YmdHis") . ".docx";
        $docTemplate->saveAs("../doc/output/" . $fileName);

        force_download("../doc/output/" . $fileName, null);
        unlink("../doc/output/" . $fileName);
    }
}
