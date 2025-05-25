<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Libraries\Eloquent;
use App\Models\BerkasGugatan;
use APP_Controller;

class BerkasGugatanService
{
  private Eloquent $eloquent;
  private APP_Controller $app;

  public function __construct(Eloquent $eloquent, APP_Controller $app)
  {
    $this->eloquent = $eloquent;
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
      $this->eloquent->capsule->connection("default")->beginTransaction();

      $berkas = BerkasGugatan::create([
        "perkara_id" => $perkara_id,
        "nomor_perkara" => $this->app->input->post("nomor_perkara", true),
        "jenis_perkara" => $this->app->input->post("jenis_perkara", true),
        "para_pihak" => $this->app->input->post("para_pihak", true),
        "majelis_hakim" => $this->app->input->post("majelis_hakim", true),
        "panitera" => $this->app->input->post("panitera", true),
        "jurusita" => $this->app->input->post("jurusita", true),
        "keterangan" => $this->app->input->post("keterangan", true),
        "tanggal_putusan" => $this->app->input->post("tanggal_putusan", true),
        "tanggal_pendaftaran" => $this->app->input->post("tanggal_pendaftaran", true),
        "tanggal_pbt" => empty($this->app->input->post("tanggal_pbt")) ? null : $this->app->input->post("tanggal_pbt", true),
        "tanggal_bht" => empty($this->app->input->post("tanggal_bht")) ? null : $this->app->input->post("tanggal_bht", true),
      ]);

      $berkas->ekspedisi()->attach($this->app->input->post("posisi_berkas", true), [
        "save_time" => date("Y-m-d H:i:s"),
        "created_by" => AuthData::getUserData()->username
      ]);

      $this->eloquent->capsule->connection("default")->commit();
      return $berkas;
    } catch (\Throwable $th) {
      $this->eloquent->capsule->connection("default")->rollback();
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
      $row['majelis'] = $r->majelis_hakim;
      $row['panitera'] = $r->panitera;
      $row['jurusita'] = $r->jurusita;
      $row['aksi'] = $this->app->load->view("berkas_gugatan/kolom_aksi", ["berkas" => $r], true);
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
}
