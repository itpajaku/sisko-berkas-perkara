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
use App\Models\BerkasEkspedisi;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Perkara;
use Illuminate\Support\Str;


$eloquent = new Eloquent;
$eloquent->boot();

BerkasGugatan::truncate();
BerkasPermohonan::truncate();
BerkasEkspedisi::truncate();

$perkaralist = Perkara::with(['arsip', 'perkara_penetapan', 'perkara_jurusita'])
  ->whereHas('perkara_putusan', function ($q) use ($parsed) {
    if ($parsed['opsi'] == 1) {
      $q->whereNotNull('tanggal_putusan');
    } else {
      $q->whereNotNull('tanggal_bht');
    }
  })
  ->whereYear('diinput_tanggal', $parsed['tahun'])
  ->get();



foreach ($perkaralist as $i => $perkara) {
  try {
    if (Str::contains($perkara->nomor_perkara, "Pdt.G")) {
      $nomor_perkara = import_berkas_gugatan($perkara);
    } else if (Str::contains($perkara->nomor_perkara, "Pdt.P")) {
      $nomor_perkara = import_berkas_permohonan($perkara, $i);
    } else {
      $nomor_perkara = null;
    }

    if (!$nomor_perkara) {
      appendLog("[INFO] Perkara Nomor $perkara->nomor_perkara tidak bisa diimport. Silahkan input manual.");
      continue;
    }
    appendLog("[SUKSES] Perkara Nomor $nomor_perkara berhasil diimport");
  } catch (\Throwable $th) {
    appendLog("[GAGAL] Perkara Nomor $perkara->nomor_perkara gagal diimport. Error : " . $th->getMessage());
  }
}
appendLog("[SELESAI] Import selesai dengan jumlah total " . $perkaralist->count() . " data");

function import_berkas_gugatan($perkara)
{
  $berkas = BerkasGugatan::create([
    "perkara_id" => $perkara->perkara_id,
    "nomor_perkara" => $perkara->nomor_perkara,
    "para_pihak" => $perkara->pihak1_text . "Melawan" .  $perkara->pihak2_text,
    "tanggal_pendaftaran" => $perkara->tanggal_pendaftaran,
    "jenis_perkara" => $perkara->jenis_perkara_nama,
    "majelis_hakim" => str_replace("</br>", '\n', $perkara->perkara_penetapan->majelis_hakim_nama),
    "panitera" => str_replace("Panitera Pengganti: ", "", $perkara->perkara_penetapan->panitera_pengganti_text),
    "jurusita" => appendJurusita($perkara),
    "tanggal_putusan" => $perkara->perkara_putusan->tanggal_putusan,
    "tanggal_bht" => $perkara->perkara_putusan->tanggal_bht,
    "status" => $perkara->arsip ? 1 : 0,
    "tanggal_terima" => $perkara->arsip ? date("Y-m-d") : null,
    "tanggal_arsip" => $perkara->arsip ? $perkara->arsip->tanggal_masuk_arsip : null,
    "keterangan" => "Imported by System",
  ]);

  $berkas->ekspedisi()->attach(1, [
    "save_time" => date("Y-m-d H:i:s"),
    "created_by" => "System"
  ]);

  if ($perkara->arsip) {
    $berkas->ekspedisi()->attach(6, [
      "save_time" => date("Y-m-d H:i:s"),
      "created_by" => "System"
    ]);
  }
  return $perkara->nomor_perkara;
}

function import_berkas_permohonan($perkara, $index)
{
  $berkas = BerkasPermohonan::create([
    "perkara_id" => $perkara->perkara_id,
    "nomor_perkara" => $perkara->nomor_perkara,
    "para_pihak" => explode($perkara->pihak1_text, '<br />')[0],
    "tanggal_pendaftaran" => $perkara->tanggal_pendaftaran,
    "jenis_perkara" => $perkara->jenis_perkara_nama,
    "majelis_hakim" => str_replace("</br>", '\n', $perkara->perkara_penetapan->majelis_hakim_nama),
    "panitera" => str_replace("Panitera Pengganti: ", "", $perkara->perkara_penetapan->panitera_pengganti_text),
    "jurusita" => appendJurusita($perkara),
    "tanggal_putusan" => $perkara->perkara_putusan->tanggal_putusan,
    "status" => $perkara->arsip ? 1 : 0,
    "tanggal_diterima" => $perkara->arsip ? date("Y-m-d") : null,
    "tanggal_arsip" => $perkara->arsip ? $perkara->arsip->tanggal_masuk_arsip : null,
    "keterangan" => "Imported by System",
  ]);

  $berkas->ekspedisi()->attach(1, [
    "save_time" => date("Y-m-d H:i:s"),
    "created_by" => "System"
  ]);

  if ($perkara->arsip) {
    $berkas->ekspedisi()->attach(6, [
      "save_time" => date("Y-m-d H:i:s"),
      "created_by" => "System"
    ]);
  }

  $berkas->update(["hash_id" => Hashid::encode($berkas->id + 1)]);
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
