<?php

use App\Libraries\Templ;
use App\Models\AtkItem;
use App\Models\ReferensiAtkDataTable;

class StockOpnameAtkController extends APP_Controller
{
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
				'aksi' => Templ::component("stock_opname/components/datatable_aksi_button"),
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
			$this->form_validation->set_rules([
				[
					'field' => 'name',
					'label' => 'Nama Item',
					'rules' => 'required|min_length[3]|max_length[255]',
					'errors' => [
						'required'   => '%s wajib diisi.',
						'min_length' => '%s minimal 3 karakter.',
						'max_length' => '%s maksimal 255 karakter.',
					]
				],
				[
					'field' => 'type',
					'label' => 'Tipe Item',
					'rules' => 'required|in_list[consume,assets,etc]',
					'errors' => [
						'required' => '%s wajib dipilih.',
						'in_list'  => '%s tidak valid.',
					]
				],
				[
					'field' => 'icon',
					'label' => 'Icon',
					'rules' => 'max_length[32]',
					'errors' => [
						'max_length' => '%s maksimal 32 karakter.',
					]
				],
				[
					'field' => 'desc',
					'label' => 'Deskripsi',
					'rules' => 'required|max_length[512]',
					'errors' => [
						'max_length' => '%s maksimal 512 karakter.',
						'required' => 'Tolong isi keterangan barang'
					]
				],
			]);

			if ($this->form_validation->run() == FALSE) {
				$ul = "<ul>";
				foreach ($this->form_validation->error_array() as $er) {
					$ul .= "<li>$er</li>";
				}
				$ul .= "</ul>";
				throw new Exception($ul);
			}

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
}
