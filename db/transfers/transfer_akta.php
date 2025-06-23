<?php
$args = $_SERVER['argv'];

// Skip argv[0] (php), argv[1] (controller), argv[2] (method)
$params = array_slice($args, 1); // mulai dari argumen ke-3

$parsed = [];
foreach ($params as $param) {
  if (strpos($param, '--') === 0) {
    $pair = explode('=', substr($param, 2), 2);
    $parsed[$pair[0]] = $pair[1] ?? true;
  }
}

if (!isset($parsed['opsi'])) {
  appendLog("[ERROR] Parameter opsi tidak ada");
  exit("Parameter opsi tidak ada");
}

if (!isset($parsed['tahun'])) {
  appendLog("[ERROR] Parameter tahun tidak ada");
  exit("Parameter tahun tidak ada");
}

ini_set('memory_limit', '512M');
set_time_limit(0);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

Dotenv\Dotenv::createMutable('../')->load();

use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Models\BerkasAkta;
use App\Models\Perkara;
use App\Models\PosisiEkspedisi;
use Illuminate\Support\Str;


$eloquent = new Eloquent;
$eloquent->boot();

BerkasAkta::truncate();
// BerkasEkspedisi::truncate();
$ekspedisiArsip = PosisiEkspedisi::where("posisi", "Arsip")->first();

$perkaralist = Perkara::with(['perkara_putusan', 'perkara_penetapan', 'arsip'])->whereHas('perkara_akta_cerai', function ($q) use ($parsed) {
  if ($parsed['opsi'] == 1) {
    $q->whereNotNull('tgl_akta_cerai')->whereYear('tgl_akta_cerai', $parsed['tahun']);
  }
})
  ->get();


foreach ($perkaralist as $i => $perkara) {
  try {
    $nomor_perkara = import_akta($perkara, $ekspedisiArsip);

    appendLog("[SUKSES] Perkara Nomor $nomor_perkara berhasil diimport");
  } catch (\Throwable $th) {
    appendLog("[GAGAL] Perkara Nomor $perkara->nomor_perkara gagal diimport. Error : " . $th->getMessage());
  }
}
appendLog("[SELESAI] Import selesai dengan jumlah total " . $perkaralist->count() . " data");

function import_akta($perkara, $ekspedisiArsip)
{
  $berkas = BerkasAkta::create([
    "nomor_perkara" => $perkara->nomor_perkara,
    "perkara_id" => $perkara->perkara_id,
    "jenis_perkara" => $perkara->jenis_perkara_nama,
    "tanggal_pendaftaran" => $perkara->tanggal_pendaftaran,
    "para_pihak" => $perkara->pihak1_text . "Melawan" .  $perkara->pihak2_text,
    "majelis" => str_replace("</br>", '\n', $perkara->perkara_penetapan->majelis_hakim_nama),
    "panitera" => str_replace("Panitera Pengganti: ", "", $perkara->perkara_penetapan->panitera_pengganti_text),
    "jurusita" => appendJurusita($perkara),
    "tanggal_putus" => $perkara->perkara_putusan->tanggal_putusan,
    "nomor_akta" => explode("/AC", $perkara->perkara_akta_cerai->nomor_akta_cerai)[0] ?? $perkara->perkara_akta_cerai->nomor_akta_cerai,
    "nomor_seri" => $perkara->perkara_akta_cerai->no_seri_akta_cerai,
    "tanggal_bht" => $perkara->perkara_putusan->tanggal_bht,
    "tanggal_akta" => $perkara->perkara_akta_cerai->tgl_akta_cerai,
    "keterangan" => "Imported by System",
    "created_at" => date("Y-m-d H:i:s", strtotime($perkara->perkara_putusan->tanggal_putusan))
  ]);

  $berkas->ekspedisi()->attach(1, [
    "save_time" => date("Y-m-d H:i:s"),
    "created_by" => "System",
    "status" => $perkara->arsip ? false : true
  ]);

  if ($perkara->arsip) {

    $berkas->ekspedisi()->attach($ekspedisiArsip->id, [
      "save_time" => date("Y-m-d H:i:s"),
      "created_by" => "System",
      "status" => true
    ]);
  }

  $berkas->update([
    'hash_id' => Hashid::encode($berkas->id)
  ]);

  return $perkara->nomor_perkara;
}


function appendJurusita($perkara)
{
  $jurusita = "";
  foreach ($perkara->perkara_jurusita as $js) {
    $jurusita .= $js->jurusita_nama . ", ";
  }
  return $jurusita;
}


function appendLog($message)
{
  $timestamp = date("d_m_Y");
  file_put_contents(realpath(__DIR__ . "/../../application/logs") . '/import_log_' . $timestamp . '.log', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
}
