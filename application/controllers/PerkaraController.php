<?php

use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\Arsip;
use Illuminate\Database\Capsule\Manager as DB;

defined("BASEPATH") or exit("Exited");

class PerkaraController extends APP_Controller
{
  protected $modelNamespace = "App\Models\\";

  public function unsinkron_berkas($hash_id)
  {
    MethodFilter::must("patch");
    try {
      $berkasClass = $this->modelNamespace . $this->encryption->decrypt(RequestBody::post("en_class_name"));

      $berkas = $berkasClass::findOrFail(Hashid::singleDecode($hash_id));

      $berkas->update([
        "status" => false,
        "tanggal_diterima" => null,
        "tanggal_arsip" => null
      ]);

      $successMessage = "Link arsip telah diputuskan.";

      $this->output
        ->set_header("HX-Refresh: true")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => $successMessage
          ]
        ]))
        ->set_output($successMessage);
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage()
          ]
        ]))
        ->set_output($th->getMessage());
    }
  }

  public function sinkron_berkas($hash_id)
  {
    MethodFilter::must("patch");
    try {
      $berkasClass = $this->modelNamespace . $this->encryption->decrypt(RequestBody::post("en_class_name"));

      $berkas = $berkasClass::findOrFail(Hashid::singleDecode($hash_id));
      $arsip = Arsip::where("perkara_id", $berkas->perkara_id)->first();
      if (!$arsip) {
        throw new Exception("Data arsip digital tidak ditemukan di sipp", 1);
      }

      $berkas->update([
        "status" => true,
        "tanggal_diterima" => date("Y-m-d"),
        "tanggal_arsip" => $arsip->tanggal_masuk_arsip
      ]);

      $successMessage = "Berhasil menghubungkan dengan arsip digital SIPP. Anda akan diarahkan sebentar lagi.";

      $this->output
        ->set_header("HX-Refresh: true")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => $successMessage
          ]
        ]))
        ->set_output($successMessage);
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage()
          ]
        ]))
        ->set_output($th->getMessage());
    }
  }

  public function check_is_ecourt($hashed_perkara_id)
  {
    MethodFilter::must("get");
    MethodFilter::mustHeader("HX-Request-Component");
    try {
      $perkara_id = Hashid::singleDecode($hashed_perkara_id);

      $efiling = DB::connection("sipp")->table("perkara_efiling_id")
        ->where("perkara_id", $perkara_id)
        ->first();

      $isEcourt = $efiling ? true : false;


      $this->output
        ->set_content_type("text/html")
        ->set_output(Templ::component("components/is_ecourt", [
          "is_ecourt" => $isEcourt
        ]));
    } catch (\Throwable $th) {
      $this->output
        ->set_status_header(500)
        ->set_output(json_encode([
          "error" => $th->getMessage()
        ]));
    }
  }
}
