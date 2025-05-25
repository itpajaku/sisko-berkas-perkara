<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\Eloquent;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasPermohonan;

class BerkasPermohonanService
{
  protected \APP_Controller $app;
  protected  $eloquent;

  public function __construct()
  {
    $this->app = &get_instance();
    $this->eloquent = Eloquent::get_instance();
  }

  public function get_datatable()
  {
    $draw = RequestBody::post('draw');
    $start = RequestBody::post('start');
    $length = RequestBody::post('length');
    $search = RequestBody::post('search')['value'];

    $query = BerkasPermohonan::selectRaw(
      "*, datediff(curdate(), tanggal_putusan) as selisih, (SELECT posisi_ekspedisi.posisi FROM berkas_ekspedisi JOIN posisi_ekspedisi ON berkas_ekspedisi.save_point = posisi_ekspedisi.id WHERE berkas_ekspedisi.berkas_id = berkas_permohonan.id ORDER BY save_point DESC LIMIT 1) as ekspedisi"
    );

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q
          ->where("nomor_perkara", "like", "$search%")
          ->orWhere("majelis_hakim", "like", "$search%")
          ->orWhere("para_pihak", "like", "$search%")
          ->orWhere("panitera", "like", "$search%")
          ->orWhere("jurusita", "like", "$search%");
      });
    }

    $filter = RequestBody::get()->filter ?? null;
    if ($filter) {
      if (RequestBody::get()->type == "range") {
        $query->whereDate($filter, ">=", RequestBody::get()->start);
        $query->whereDate($filter, "<=", RequestBody::get()->end);
      }
    }

    $total = BerkasPermohonan::selectRaw("COUNT(*) as total")->first()->total;
    $filtered = $query->count();
    $data = $query->orderBy("created_at", "desc")->offset($start)->limit($length)->get();

    $data->transform(function ($item, $n) {
      $item->no = ++$n;
      $item->tanggal_pendaftaran = tanggal_indo($item->tanggal_pendaftaran, false);
      $item->tanggal_putusan = tanggal_indo($item->tanggal_putusan, false);
      $item->ekspedisi = Templ::component("berkas_permohonan/kolom_ekspedisi", [
        "berkas" => $item
      ]);

      $item->selisih = Templ::component("berkas_permohonan/kolom_selisih", [
        "berkas" => $item
      ]);

      $item->aksi = Templ::component("berkas_permohonan/kolom_aksi", [
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

  /**
   * Insert a new BerkasPermohonan record into the database.
   * @param array $data
   * @return BerkasPermohonan
   * @throws \Throwable
   */
  public function insertOne($perkara_id)
  {
    try {
      $this->eloquent->connection("default")->beginTransaction();

      $berkas = BerkasPermohonan::create([
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
      ]);

      $berkas->ekspedisi()->attach(RequestBody::post("posisi_berkas"), [
        "save_time" => date("Y-m-d H:i:s"),
        "created_by" => AuthData::getUserData()->username
      ]);

      $this->eloquent->connection("default")->commit();
      return $berkas;
    } catch (\Throwable $th) {
      $this->eloquent->connection("default")->rollback();
      throw $th;
    }
  }

  public function update($id, $perkara_id)
  {
    $berkas = BerkasPermohonan::findOrFail($id);
    $berkas->update([
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
      "tanggal_diterima" => RequestBody::post("tanggal_diterima") ?: null,
      "status" => RequestBody::post("status")
    ]);
  }
}
