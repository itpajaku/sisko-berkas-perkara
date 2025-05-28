<?php

use App\Libraries\Eloquent;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use Symfony\Component\Config\Builder\Method;

class AktaCeraiController extends APP_Controller
{
  public function register_page()
  {
    Templ::render("akta_cerai/register_akta_page")
      ->layout("layouts/main_layout", [
        "title" => "Register Akta Cerai",
      ]);
  }

  public function konfigurasi_page()
  {
    Templ::render("akta_cerai/konfigurasi_akta_page", [
      "konfigurasi" => Eloquent::get_instance()->table("konfigurasi_akta")
        ->where("id", 1)
        ->first()
    ])
      ->layout("layouts/main_layout", [
        "title" => "Konfigurasi Akta Cerai",
      ]);
  }

  public function update_konfigurasi()
  {
    MethodFilter::must("post");
    try {
      Eloquent::get_instance()->table("konfigurasi_akta")
        ->where("id", 1)
        ->update([
          "prefix" => RequestBody::post("prefix"),
          "nomor_akta_terakhir" => RequestBody::post("nomor_akta_terakhir"),
        ]);

      $this->session->set_flashdata("success", "Konfigurasi berhasil diperbarui");
      $this->output
        // ->set_header("HX-Refresh: true")
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "success",
            "message" => "Konfigurasi berhasil diperbarui. Anda akan diarahkan sebentar lagi.",
          ],
        ]))
        ->set_output("Berhasil memperbarui konfigurasi. Anda akan diarahkan sebentar lagi.");
    } catch (\Throwable $th) {
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "error",
            "message" => $th->getMessage(),
          ],
        ]))
        ->set_output($th->getMessage());
    }
  }
}
