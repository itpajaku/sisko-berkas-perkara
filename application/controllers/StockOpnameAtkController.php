<?php

use App\Libraries\Hashid;
use App\Libraries\Templ;
use App\Models\AtkItem;
use App\Models\ReferensiAtkDataTable;
use App\Traits\ItemAtkValidation;

class StockOpnameAtkController extends APP_Controller
{
	use ItemAtkValidation;

	public function index(): void
	{
		Templ::render("stock_opname/monitoring_atk", [
			"page_name" => "Monitoring Alat Tulis Kantor",
		])->layout("layouts/main_layout", [
			"title" => "Monitoring ATK",
		]);
	}

	public function referensi_page(): void
	{
		Templ::render("stock_opname/referensi_atk", [
			"page_name" => "Referensi Alat Tulis Kantor",
		])->layout("layouts/main_layout", [
			"title" => "Referensi ATK",
		]);
	}

	public function referensi_datatable(): CI_Output
	{
		$model = new ReferensiAtkDataTable();
		$data = $model->getDatatables()->map(function ($item, $index) {
			return [
				'no' => $_POST['start'] + $index + 1,
				'item' => "<strong><ti class='$item->icon'></ti> $item->name</strong>",
				'type' => $item->type,
				'desc' => $item->desc,
				'status' => $item->status ? "Berlaku" : "Tidak Berlaku",
				'aksi' => Templ::component("stock_opname/components/datatable_aksi_button", compact("item")),
			];
		});
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'draw'            => intval($_POST['draw'] ?? 0),
				'recordsTotal'    => $model->countAll(),
				'recordsFiltered' => $model->countFiltered(),
				'data'            => $data,
			]));
	}

	public function referensi_form()
	{
		if (!isset($this->input->request_headers()['Hx-Request'])) {
			return $this->output->set_header(404)->set_output("Theres nothing here");
		};

		$this->output
			->set_content_type("text/html")
			->set_output(Templ::component("stock_opname/components/referensi_form"));
	}

	public function add_referensi()
	{
		if (!isset($this->input->request_headers()['Hx-Request'])) {
			return $this->output->set_header(404)->set_output("Theres nothing here");
		};

		try {
			$this->validate_form();

			$item = AtkItem::create([
				'name'   => $this->input->post('name', true),
				'type'   => $this->input->post('type', true),
				'status' => $this->input->post('status') ? 1 : 0,
				'icon'   => $this->input->post('icon', true) ?: 'ti ti-pencil',
				'desc'   => $this->input->post('desc', true),
			]);

			$htmxEvent = [
				"insertSuccess" => [
					"data" => $item
				],
				"closeDynamicModal" => true
			];

			$this->output
				->set_header("HX-Trigger: " . json_encode($htmxEvent))
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => "Berhasil Menambahkan Item. Jendela akan ditutup dalam 2 detik",
					"type" => "success"
				]));
		} catch (\Throwable $th) {
			$this->output
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => $th->getMessage(),
					"type" => "danger"
				]));
		}
	}

	public function referensi_edit($id)
	{
		if (!isset($this->input->request_headers()['Hx-Request'])) {
			return $this->output->set_header(404)->set_output("Theres nothing here");
		};

		$item = AtkItem::find(Hashid::singleDecode($id));

		$this->output
			->set_content_type("text/html")
			->set_output(Templ::component("stock_opname/components/referensi_form", [
				"item" => $item
			]));
	}

	public function referensi_update($id)
	{
		if (!isset($this->input->request_headers()['Hx-Request'])) {
			return $this->output->set_header(404)->set_output("Theres nothing here");
		};

		try {
			$this->validate_form();
			AtkItem::where("id", Hashid::singleDecode($id))->update([
				'name'   => $this->input->post('name', true),
				'type'   => $this->input->post('type', true),
				'status' => $this->input->post('status') ? 1 : 0,
				'icon'   => $this->input->post('icon', true) ?: 'ti ti-pencil',
				'desc'   => $this->input->post('desc', true),
			]);

			$htmxEvent = [
				"updateSuccess" => true,
				"closeDynamicModal" => true
			];

			$this->output
				->set_header("HX-Trigger: " . json_encode($htmxEvent))
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => "Berhasil Memperbaharui Item. Jendela akan ditutup dalam 2 detik",
					"type" => "success"
				]));
		} catch (\Throwable $th) {
			$this->output
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => $th->getMessage(),
					"type" => "danger"
				]));
		}
	}

	public function referensi_delete($id)
	{
		if (!isset($this->input->request_headers()['Hx-Request'])) {
			return $this->output->set_header(404)->set_output("Theres nothing here");
		};

		try {
			AtkItem::find(Hashid::singleDecode($id))->delete();
			$this->output
				->set_header("HX-Trigger: deleteSuccess, closeDynamicModal")
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => "Berhasil Menghapus Item. Jendela akan ditutup dalam 2 detik",
					"type" => "success"
				]));
		} catch (\Throwable $th) {
			$this->output
				->set_content_type("text/html")
				->set_output(Templ::component("stock_opname/components/referensi_form_alert", [
					"message" => $th->getMessage(),
					"type" => "danger"
				]));
		}
	}
}
