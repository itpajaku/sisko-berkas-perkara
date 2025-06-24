<?php

use App\Libraries\AuthData;
use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasEkspedisi;
use Illuminate\Database\Capsule\Manager as DB;

class EkspedisiBerkasController extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        MethodFilter::mustHeader("HX-Request");
    }

    public function attach_to_berkas($berkas_id)
    {
        MethodFilter::must("post");
        $berkasId = Hashid::singleDecode($berkas_id);
        $berkasType = $this->input->post("berkas_type", true);
        try {
            DB::connection("default")->beginTransaction();

            BerkasEkspedisi::where([
                "berkas_id" => $berkasId,
                "berkas_type" => "App\Models\\$berkasType",
            ])->update([
                "status" => false,
            ]);

            BerkasEkspedisi::create([
                "save_point" => $this->input->post("posisi_ekspedisi"),
                "save_time" => date("Y-m-d H:i:s"),
                "berkas_id" => $berkasId,
                "berkas_type" => "App\Models\\$berkasType",
                "created_by" => $this->userdata->username,
                "status" => true
            ]);

            DB::connection("default")->commit();

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
            DB::connection("default")->rollBack();
            $this->output->set_output(
                Templ::component("/components/exception_alert", ["message" => $th->getMessage()])
            );
        }
    }

    public function detach_from_berkas($berkas_id)
    {
        MethodFilter::must("delete");
        $berkasId = Hashid::singleDecode($berkas_id);
        $berkasType = RequestBody::get("berkas_type");

        try {
            DB::connection("default")->beginTransaction();
            $berkas = BerkasEkspedisi::where([
                "save_point" => RequestBody::get("save_point"),
                "save_time" => RequestBody::get("save_time"),
                "berkas_id" => $berkasId,
                "berkas_type" => "App\Models\\$berkasType"
            ])->first();
            $berkas->delete();

            if ($berkas->status) {
                $rest = BerkasEkspedisi::where([
                    "berkas_id" => $berkasId,
                    "berkas_type" => "App\Models\\$berkasType"
                ])
                    ->orderBy("id", "desc")
                    ->first();
                $rest->update([
                    "status" => true
                ]);
            }

            DB::connection("default")->commit();
            $this->output->set_header("HX-Refresh: true")->set_output("Berhasil menghapus ekspedisi");
        } catch (\Throwable $th) {
            DB::connection("default")->rollBack();
            $alertData = ["htmx:toastr" => [
                "level" => "success",
                "message" => "Sinkronisasi berkas ke SIPP berhasil"
            ]];

            $this->output
                ->set_header("HX-Trigger: " . json_encode($alertData))
                ->set_output(
                    Templ::component("/components/exception_alert", ["message" => $th->getMessage()])
                );
        }
    }
}
