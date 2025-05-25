<?php

use App\Libraries\AuthData;
use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\BerkasEkspedisi;

class EkspedisiBerkasController extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        MethodFilter::mustHeader("Hx-Request");
    }

    public function attach_to_berkas($berkas_id)
    {
        MethodFilter::must("post");
        $berkasId = $this->hash->decode($berkas_id)[0];
        $berkasType = $this->input->post("berkas_type", true);
        try {
            BerkasEkspedisi::create([
                "save_point" => $this->input->post("posisi_ekspedisi"),
                "save_time" => date("Y-m-d H:i:s"),
                "berkas_id" => $berkasId,
                "berkas_type" => "App\Models\\$berkasType",
                "created_by" => $this->userdata->username
            ]);

            $this->output
                ->set_header("HX-Refresh: true")
                ->set_header("HX-Trigger: " . json_encode([
                    "htmx:toastr" => [
                        "level" => "success",
                        "message" => "Berhasil mengupdate ekspedisi. Anda akan diarahkan sebentar lagi."
                    ]
                ]))
                ->set_output("Berhasil menambahkan ekspedisi");
        } catch (\Throwable $th) {
            $this->output->set_output(
                Templ::component("/component/exception_alert", ["messasge" => $th->getMessage()])
            );
        }
    }

    public function detach_from_berkas($berkas_id)
    {
        MethodFilter::must("delete");
        $berkasId = $this->hash->decode($berkas_id)[0];
        $berkasType = $this->input->get("berkas_type");

        try {
            BerkasEkspedisi::where([
                "save_point" => $this->input->get("save_point"),
                "save_time" => $this->input->get("save_time"),
                "berkas_id" => $berkasId,
                "berkas_type" => "App\Models\\$berkasType"
            ])->delete();

            $this->output->set_header("HX-Refresh: true")->set_output("Berhasil menghapus ekspedisi");
        } catch (\Throwable $th) {
            $alertData = ["htmx:toastr" => [
                "level" => "success",
                "message" => "Sinkronisasi berkas ke SIPP berhasil"
            ]];

            $this->output
                ->set_header("HX-Trigger: " . json_encode($alertData))
                ->set_output(
                    Templ::component("/component/exception_alert", ["messasge" => $th->getMessage()])
                );
        }
    }
}
