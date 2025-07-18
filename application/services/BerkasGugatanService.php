<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\DateHelper;
use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Libraries\Sysconf;
use App\Libraries\Templ;
use App\Models\BerkasGugatan;
use APP_Controller;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class BerkasGugatanService
{
  private Eloquent $eloquent;
  private APP_Controller $app;

  public function __construct(Eloquent $eloquent, APP_Controller $app)
  {
    $this->app = $app;
  }


  /**
   * Insert a new BerkasGugatan record into the database.
   * @param array $data
   * @return BerkasGugatan
   * @throws \Throwable
   */
  public function insertOne($perkara_id): BerkasGugatan
  {
    try {
      Eloquent::get_instance()->connection("default")->beginTransaction();

      $existedBerkas = BerkasGugatan::where("perkara_id", $perkara_id)
        ->first();

      if ($existedBerkas) {
        throw new \Exception("Berkas untuk perkara ini sudah ada", 1);
      }

      $berkas = BerkasGugatan::create([
        "perkara_id" => $perkara_id,
        "nomor_perkara" => RequestBody::post("nomor_perkara"),
        "jenis_perkara" => RequestBody::post("jenis_perkara"),
        "para_pihak" => RequestBody::post("para_pihak"),
        "majelis_hakim" => RequestBody::post("majelis_hakim"),
        "panitera" => RequestBody::post("panitera"),
        "jurusita" => RequestBody::post("jurusita"),
        "keterangan" => RequestBody::post("keterangan"),
        "tanggal_putusan" => RequestBody::post("tanggal_putusan"),
        "tanggal_pendaftaran" => RequestBody::post("tanggal_pendaftaran"),
        "tanggal_pbt" => empty(RequestBody::post("tanggal_pbt")) ? null : RequestBody::post("tanggal_pbt"),
        "tanggal_bht" => empty(RequestBody::post("tanggal_bht")) ? null : RequestBody::post("tanggal_bht"),
      ]);

      $berkas->ekspedisi()->attach(RequestBody::post("posisi_berkas"), [
        "save_time" => date("Y-m-d H:i:s"),
        "created_by" => AuthData::getUserData()->username
      ]);

      Eloquent::get_instance()->connection("default")->commit();
      return $berkas;
    } catch (\Throwable $th) {
      Eloquent::get_instance()->connection("default")->rollback();
      throw $th;
    }
  }

  /**
   * Retrieve a list of BerkasGugatan records from the database with datatable formated.
   * @param array $data
   * @return array
   */
  public function datatable()
  {
    $this->app->load->model('BerkasGugatanDataTable', 'BerkasGugatanDataTable');
    $list = $this->app->BerkasGugatanDataTable->get_datatables();
    $data = [];
    $n = 1;
    foreach ($list as $r) {
      $row = [];
      $row['no'] = $n;
      $row['nomor_perkara'] = $r->nomor_perkara;
      $row['tanggal_pendaftaran'] = tanggal_indo($r->tanggal_pendaftaran, false);
      $row['tanggal_putusan'] = tanggal_indo($r->tanggal_putusan, false);
      $row['tanggal_pbt'] = $this->app->load->view("berkas_gugatan/kolom_pbt", ["berkas" => $r], true);
      $row['tanggal_bht'] = tanggal_indo($r->tanggal_bht, false);
      $row['selisih'] = $this->app->load->view("berkas_gugatan/kolom_selisih", ["berkas" => $r], true);
      $row['ekspedisi'] = $this->app->load->view("berkas_gugatan/kolom_ekspedisi", ["berkas" => $r], true);
      $row['majelis'] = explode('\n', $r->majelis_hakim)[0] . "<br>" . $r->panitera . "<br>" . $r->jurusita;
      $row['aksi'] = $this->app->load->view("berkas_gugatan/kolom_aksi", ["berkas" => $r], true);
      $row['tanggal_terima'] = tanggal_indo($r->tanggal_terima, false) ?? "Tanggal diterima belum diisi";
      $n++;
      $data[] = $row;
    }

    return [
      "draw" => intval($this->app->input->post('draw')),
      "recordsTotal" => $this->app->BerkasGugatanDataTable->count_all(),
      "recordsFiltered" => $this->app->BerkasGugatanDataTable->count_filtered(),
      "data" => $data,
    ];
  }

  /**
   * @param int $id
   * @return void
   * @throws \Throwable 
   */
  public function remove($id)
  {
    $berkas = BerkasGugatan::find($id);

    if (!$berkas) {
      throw new \Exception("Berkas tidak ditemukan", 1);
    }
    $berkas->ekspedisi()->detach();
    $berkas->delete();
  }

  public function toggle_status($id, $status)
  {
    $berkas = BerkasGugatan::findOrFail($id);
    $berkas->status = $status;
    if ($status = 1) {
      if (!$berkas->tanggal_pbt) {
        throw new \Exception("Tanggal PBT harus diisi terlebih dahulu", 1);
      }
      $berkas->tanggal_terima = date("Y-m-d");
    }

    $berkas->save();

    if ($status == 0) {
      $selisih = BerkasGugatan::selectRaw("datediff(curdate(), tanggal_bht) as selisih")
        ->where("id", $id)->first();
      $berkas->selisih = $selisih->selisih;
    }

    return $berkas;
  }


  public function bht_datatable()
  {
    $draw = $this->app->input->post('draw');
    $start = $this->app->input->post('start');
    $length = $this->app->input->post('length');
    $search = $this->app->input->post('search')['value'];

    $query = BerkasGugatan::selectRaw("*, datediff(curdate(), tanggal_pbt) as selisih")
      ->whereRaw("datediff(curdate(), tanggal_pbt) >= 18")
      ->whereNull("tanggal_bht")
      ->whereNotNull("tanggal_pbt");

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q
          ->where("nomor_perkara", "like", "$search%")
          ->orWhere("majelis_hakim", "like", "$search%")
          ->orWhere("panitera", "like", "$search%")
          ->orWhere("jurusita", "like", "$search%");
      });
    }

    $total = BerkasGugatan::selectRaw("COUNT(*) as total")->first()->total;
    $filtered = $query->count();
    $data = $query->offset($start)->limit($length)->get();

    $data->transform(function ($item, $n) {
      $item->no = ++$n;
      $item->selisih = Templ::component("berkas_gugatan/kolom_selisih", ["berkas" => $item]);
      $item->nomor_perkara = $item->nomor_perkara . " <br/><span class='text-primary'><strong>$item->jenis_perkara</strong></span>";
      $item->tanggal_pendaftaran = tanggal_indo($item->tanggal_pendaftaran, false);
      $item->tanggal_putusan = tanggal_indo($item->tanggal_putusan, false);
      $item->tanggal_pbt = tanggal_indo($item->tanggal_pbt);
      $item->aksi = $this->app->load->view("berkas_gugatan/aksi_add_bht", ["berkas" => $item], true);
      return $item;
    });

    return [
      'draw' => intval($draw),
      'recordsTotal' => $total,
      'recordsFiltered' => $filtered,
      'data' => $data->all()
    ];
  }

  public function updateOne($id)
  {
    $berkas = BerkasGugatan::findOrFail($id);
    $berkas->update(RequestBody::post()->toArray());
  }

  public function generate_docs()
  {
    $berdasarkan = $this->app->encryption->decrypt(RequestBody::post("berdasarkan"));

    $berkasGugatan = BerkasGugatan::whereDate($berdasarkan, ">=", RequestBody::post("tanggal_awal"))
      ->whereDate($berdasarkan, "<=", RequestBody::post("tanggal_akhir"))
      ->orderBy($berdasarkan, "asc")
      ->get();

    $docTemplate = new TemplateProcessor(
      "../doc/template/template_laporan_berkas_gugatan.docx"
    );

    $docTemplate->setValue("NAMA_SATKER", Sysconf::getVar()->NamaPN);
    $docTemplate->setValue("ALAMAT_SATKER", Sysconf::getVar()->AlamatPN);
    $docTemplate->setValue("TANGGAL_AWAL", tanggal_indo(RequestBody::post("tanggal_awal")));
    $docTemplate->setValue("TANGGAL_AKHIR", tanggal_indo(RequestBody::post("tanggal_akhir")));
    $docTemplate->setValue("penandatangan", RequestBody::post("penandatangan"));
    $docTemplate->setValue("nip_penandatangan", pejabat_to_nip(
      RequestBody::post("penandatangan")
    ));
    $docTemplate->setValue("tgl_hari_laporan", tanggal_indo(date("Y-m-d")));
    $docTemplate->setValue("pejabat", nama_to_jabatan(
      RequestBody::post("penandatangan")
    ));

    $docTemplate->cloneRowAndSetValues("no", $berkasGugatan->map(function ($item, $index) {
      return [
        "no" => $index + 1,
        "nomor_perkara" => $item->nomor_perkara,
        "jenis_perkara" => $item->jenis_perkara_text,
        "tgl_daftar" => tanggal_indo($item->tanggal_pendaftaran, false),
        "tgl_putus" => tanggal_indo($item->tanggal_putusan, false),
        "tgl_pip" => tanggal_indo($item->tanggal_pbt, false) ?? "Belum PIP",
        "tgl_bht" => tanggal_indo($item->tanggal_bht, false),
        "panitera" => $item->panitera,
        "majelis" => explode('\n', $item->majelis_hakim)[0]  ?? null,

        "selisih_1" => DateHelper::getDayInterval(
          $item->tanggal_putusan,
          $item->tanggal_pbt
        ) . " Hari",
        "selisih_2" => DateHelper::getDayInterval(
          $item->tanggal_bht,
          $item->tanggal_arsip
        ) . " Hari",

        "tgl_arsip" => $item->tanggal_arsip ?? "Masih Berjalan",
      ];
    })->all());

    $total = $berkasGugatan->count();
    $totalMasukBerkas = $berkasGugatan->whereNotNull("tanggal_arsip")->count();
    $totalBelumMasukBerkas = $total - $totalMasukBerkas;

    $docTemplate->setValue("TOTAL_DATA", $total);
    $docTemplate->setValue("TOTAL_MASUK_BERKAS", strval($totalMasukBerkas));
    $docTemplate->setValue("TOTAL_BELUM_ARSIP", strval($totalBelumMasukBerkas));

    $fileName = "Laporan_Berkas_Gugatan_" . date("YmdHis") . ".docx";
    $docTemplate->saveAs("../doc/output/" . $fileName);

    force_download("../doc/output/" . $fileName, null);
    unlink("../doc/output/" . $fileName);
  }
}
