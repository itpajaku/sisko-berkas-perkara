<?php

use App\Libraries\Eloquent;
use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasAkta;
use App\Models\BerkasGugatan;
use App\Models\Perkara;
use App\Models\PosisiEkspedisi;
use App\Traits\BerkasGugatanValidation;
use App\Services\BerkasGugatanService;
use Illuminate\Support\Facades\Request;

defined('BASEPATH') or exit('No direct script access allowed');

class BerkasGugatanController extends APP_Controller
{
	use BerkasGugatanValidation;
	private BerkasGugatanService $berkasGugatanService;

	public function __construct()
	{
		parent::__construct();
		$this->berkasGugatanService = new BerkasGugatanService($this->eloquent, $this);
	}

	public function index()
	{
		$this->daftar_register();
	}

	public function daftar_register()
	{
		MethodFilter::must("get");
		if (isset($_GET["filter"])) {
			$start = strtotime($this->input->get("start"));
			$end = strtotime($this->input->get("end"));
			if ($start > $end) {
				$this->session->set_flashdata(
					'error_alert',
					Templ::component("components/exception_alert", ["message" => "Tanggal awal tidak boleh lebih besar dari tanggal akhir"])
				);
			}
		}
		Templ::render("berkas_gugatan/list_berkas_gugatan")
			->sidebar("layouts/sidebar_menu", [
				"title" => "Register Berkas Gugatan | Sistem Kontrol Berkas",
			])
			->layout("layouts/main_layout");
	}

	public function datatable()
	{
		MethodFilter::must("post");
		$jsonDatatable = $this->berkasGugatanService->datatable();

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($jsonDatatable));
	}

	public function fetchFormPbt()
	{
		MethodFilter::must("post");
		$berkas = BerkasGugatan::find(Hashid::singleDecode($this->input->post("id")));
		if ($berkas) {
			$this->load->view("berkas_gugatan/form_set_pbt", ["berkas" => $berkas]);
		} else {
			$this->load->view("components/exception_alert", [
				"message" => "Berkas tidak ditemukan",
			]);
		}
	}

	public function add()
	{
		MethodFilter::must("get");
		Templ::render("berkas_gugatan/add_berkas_gugatan")
			->sidebar("layouts/sidebar_menu", [
				"title" => "Berkas Gugatan | Sistem Kontrol Berkas",
			])
			->layout("layouts/main_layout");
	}

	public function fetchForm()
	{
		MethodFilter::must("post");

		try {
			$perkara = Perkara::with('perkara_penetapan')->where('nomor_perkara', $this->input->post('nomor_perkara', true))->first();

			if (!$perkara) {
				throw new Exception("Perkara tidak ditemukan", 1);
			}

			$existedBerkas = BerkasGugatan::where("perkara_id", $perkara->perkara_id)->first();
			if ($existedBerkas) {
				$tanggalBuat = $existedBerkas->created_at->translatedFormat("l d m Y H:i:s");
				throw new Exception("Perkara ini sudah diregister sebelumnya pada tanggal $tanggalBuat", 1);
			}

			if (!$perkara->perkara_putusan) {
				throw new Exception("Perkara ini belum putus", 1);
			}

			$this->load->view("berkas_gugatan/form_berkas_gugatan", [
				"perkara" => $perkara,
				"penetapan" => $perkara->perkara_penetapan,
				"putusan" => $perkara->perkara_putusan,
				"daftar_posisi_berkas" => PosisiEkspedisi::where("status", 1)->get(),
			]);
		} catch (\Throwable $th) {
			$this->load->view("components/exception_alert", ["message" => $th->getMessage()]);
		}
	}

	public function store()
	{
		MethodFilter::must("post");
		try {
			$this->validation($this->input->post(), $this->form_validation);
			$this->berkasGugatanService->insertOne(Hashid::singleDecode($this->input->post("perkara_id")));

			$this->session->set_flashdata("success_alert", $this->load->view("components/success_alert", ["message" => "Berkas berhasil disimpan"], true));
			header("HX-Redirect: /berkas_gugatan/register");
		} catch (\Throwable $th) {
			$this->eloquent->capsule->connection("default")->rollBack();
			$this->load->view("components/exception_alert", ["message" => $th->getMessage()]);
		}
	}

	public function save($id = null)
	{
		MethodFilter::must("patch");

		try {
			$this->validation(RequestBody::post()->toArray(), $this->form_validation);
			$this->berkasGugatanService->updateOne(Hashid::singleDecode($id));

			$this->output->set_header("HX-Redirect:" . base_url("/berkas_gugatan/register"))->set_output("Berkas Gugatan berhasil diupdate");
		} catch (\Throwable $th) {
			$this->load->view("components/exception_alert", ["message" => $th->getMessage()]);
		}
	}

	public function delete($id = null)
	{
		MethodFilter::must("delete");
		try {
			$this->berkasGugatanService->remove(Hashid::singleDecode($id));
			$this->output->set_header("HX-Refresh: true")->set_output("Berkas berhasil dihapus");
		} catch (\Throwable $th) {
			$this->load->view("components/exception_alert", ["message" => $th->getMessage()]);
		}
	}

	public function edit($id = null)
	{
		MethodFilter::must("get");

		$berkas = BerkasGugatan::findOrFail(Hashid::singleDecode($id));
		Templ::render("berkas_gugatan/edit_berkas_gugatan", [
			"berkas" => $berkas,
		])
			->sidebar("layouts/sidebar_menu", [
				"title" => "Edit Berkas Gugatan",
			])
			->layout("layouts/main_layout");
	}

	public function sinkron_berkas_sipp($id = null)
	{
		MethodFilter::must("post");
		try {
			$input = file_get_contents("php://input");
			parse_str($input, $_POST);
			$updatedBerkas = $this->berkasGugatanService->toggle_status(Hashid::singleDecode($id), $_POST["status"]);

			$this->output->set_header("HX-Trigger: " . json_encode([
				"htmx:toastr" => [
					"level" => "success",
					"message" => "Sinkronisasi berkas ke SIPP berhasil"
				]
			]))->set_output(Templ::component("berkas_gugatan/kolom_selisih", ["berkas" => $updatedBerkas]));
		} catch (\Throwable $th) {
			$this->output->set_header("HX-Trigger: " . json_encode(
				["htmx:toastr" => [
					"level" => "error",
					"message" => $th->getMessage()
				]]
			))->set_output($th->getMessage());
		}
	}

	public function ekspedisi_berkas($id = null)
	{
		MethodFilter::must("get");
		$berkas = BerkasGugatan::findOrFail(Hashid::singleDecode($id));
		Templ::render("berkas_gugatan/ekspedisi_berkas_gugatan", [
			"berkas" => $berkas,
			"posisi_berkas" => PosisiEkspedisi::where("status", 1)->get()
		])
			->sidebar("layouts/sidebar_menu", [
				"title" => "Edit Berkas Gugatan",
			])
			->layout("layouts/main_layout");
	}

	public function add_ekspedisi($berkas_id)
	{
		MethodFilter::must("post");
		try {
			$berkasId = Hashid::singleDecode($berkas_id);

			$berkas = BerkasGugatan::findOrFail($berkasId);
			if ($berkas->ekspedisi) {
				$berkas->ekspedisi()->update(["status" => false]);
			}
			$berkas->ekspedisi()->attach($this->input->post("posisi_ekspedisi"), [
				"save_time" => date("Y-m-d H:i:s"),
				"created_by" => $this->userdata->username,
				"status" => true
			]);
			$this->output->set_header("HX-Refresh: true")->set_output("Berhasil menambahkan ekspedisi");
		} catch (\Throwable $th) {
			$this->output->set_output(
				Templ::component("components/exception_alert", ["message" => $th->getMessage()])
			);
		}
	}

	public function bht_page()
	{
		MethodFilter::must("get");
		Templ::render("/berkas_gugatan/bht_hari_ini")
			->sidebar("layouts/sidebar_menu", [
				"title" => "Perkara BHT Hari Ini | Sistem Kontrol Berkas",
			])
			->layout("layouts/main_layout");
	}

	public function bht_datatable()
	{
		MethodFilter::must("post");
		$datatableJson = $this->berkasGugatanService->bht_datatable();
		$this->output->set_header("Content-Type: application/json")->set_output(json_encode($datatableJson));
	}

	public function fetch_form_bht($enid = null)
	{
		$id = Hashid::singleDecode($enid);
		$berkas = BerkasGugatan::findOrFail($id);
		$this->output->set_output(
			Templ::component("/berkas_gugatan/form_set_bht", ["berkas" => $berkas])
		);
	}

	public function set_bht($hash_id)
	{
		MethodFilter::must("patch");
		try {
			$akta = BerkasGugatan::findOrFail(Hashid::singleDecode($hash_id));
			if (RequestBody::post("tanggal_bht") != null) {
				Eloquent::get_instance()
					->connection("sipp")
					->table("perkara_putusan")
					->where("perkara_id", $akta->perkara_id)
					->update([
						"tanggal_bht" => RequestBody::post("tanggal_bht"),
					]);
			}
			$akta->tanggal_bht = RequestBody::post("tanggal_bht");
			$akta->save();
			// $posisiArsip = PosisiEkspedisi::where("posisi", "Arsip")->first();
			// if ($posisiArsip) {
			// 	$akta->ekspedisi()->attach($posisiArsip->id, [
			// 		"save_time" => date("Y-m-d H:i:s"),
			// 		"created_by" => $this->userdata->username,
			// 		"status" => true
			// 	]);
			// }
			$this->session->set_flashdata("success_alert", $this->load->view("components/success_alert", ["message" => "BHT berhasil disimpan"], true));
			$this->output
				->set_header("HX-Redirect: /berkas_gugatan/register")
				->set_output("BHT berhasil disimpan");
		} catch (\Throwable $th) {
			$this->output->set_output(
				Templ::component("components/exception_alert", ["message" => $th->getMessage()])
			);
		}
	}

	public function laporan_page()
	{
		MethodFilter::must("get");
		Templ::render("berkas_gugatan/laporan_berkas_gugatan_page")->layout("layouts/main_layout", [
			"title" => "Laporan Berkas Gugatan"
		]);
	}

	public function generate_laporan()
	{
		MethodFilter::must("post");

		try {
			if (strtotime(RequestBody::post("tanggal_awal")) > strtotime(RequestBody::post("tanggal_akhir"))) {
				throw new Exception("Tanggal awal tidak boleh lebih besar dari tanggal akhir", 1);
			}

			$this->berkasGugatanService->generate_docs();
		} catch (\Throwable $th) {
			$this->session->set_flashdata("error_alert", Templ::component("components/exception_alert", ["message" => $th->getMessage()]));
			redirect("/berkas_gugatan/laporan");
		}
	}
}
