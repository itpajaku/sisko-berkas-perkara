<?php

use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\AtkItem;
use App\Models\AtkStock;
use App\Models\ReferensiAtkDataTable;
use App\Traits\ItemAtkValidation;
use Illuminate\Database\Capsule\Manager as DB;

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
			$item->hashedId = Hashid::encode($item->id);
			return [
				'no' => $_POST['start'] + $index + 1,
				'item' => "<strong><ti class='$item->icon'></ti> $item->name</strong>",
				'type' => $item->type,
				'desc' => $item->desc,
				'status' => $item->status ? "Berlaku" : "Tidak Berlaku",
				'aksi' => Templ::component("stock_opname/components/datatable_aksi_button", compact("item")),
				'stock' => Templ::component("stock_opname/components/kolom_stock_atk", compact("item")),
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

	public function add_transaction_form()
	{
		MethodFilter::mustHeader('HX-Request-Component');
		$this->output->set_content_type("text/html")->set_output(
			Templ::component("stock_opname/components/add_atk_trans_form")
		);
	}

	public function autocomplete_item()
	{
		MethodFilter::mustHeader("HX-Request-Autocomplete");
		$q = $this->input->get('q', true);

		$data = DB::table('atk_item')
			->select('id', 'name', 'type', 'icon')
			->where('status', 1)
			->where('name', 'like', "%{$q}%")
			->limit(10)
			->get();

		echo json_encode($data->transform(function ($value, $key) {
			$value->id = Hashid::encode($value->id);
			return $value;
		}));
	}

	public function stock_info($id)
	{
		$itemId = Hashid::singleDecode($id);

		$stock = DB::table('atk_stock')
			->where('atk_item_id', $itemId)
			->where('tahun', date('Y'))
			->first();

		if (!$stock) {
			return $this->output->set_output(
				Templ::component(
					"components/exception_alert",
					["message" => "Belum ada stock tahunan pada item ini. Silahkan tambahkan terlebih dahulu <a href='" . base_url("stock_opname_atk/referensi/$id/stock") . "' target='_blank'> disini </a>"]
				)
			);
		}

		$this->output->set_output(
			Templ::component(
				"stock_opname/components/info_stock_atk",
				["stock" => $stock->stock]
			)
		);
	}

	public function stock_page($id)
	{
		$atk = AtkItem::findOrFail(Hashid::singleDecode($id));
		$stocks = AtkStock::where('atk_item_id', $atk->id)
			->orderBy('tahun', 'desc')
			->get();
		$page_name = "Halaman Stock ATK $atk->name";
		Templ::render("stock_opname/atk_stock_page", [
			"atk" => $atk,
			"stocks" => $stocks,
			"breadcrumb" => Templ::component("layouts/page_header", [
				"page_name" => $page_name,
				"breadcrumbs" => [
					["name" => "Home", "url" => site_url("meja_3/dashboard")],
					["name" => "Referensi ATK", "url" => site_url("stock_opname_atk/referensi")],
					["name" => $page_name, "url" => site_url("stock_opname_atk/referensi/$id/stock")],
				],
			])
		])->layout("layouts/main_layout");
	}

	public function stock_form($id)
	{
		MethodFilter::mustHeader("HX-Request-Component");
		$atk = AtkItem::findOrFail(Hashid::singleDecode($id));
		$this->load->view('stock_opname/components/atk_stock_form', compact('atk'));
	}



	public function stock_store($hashId)
	{
		try {

			$atkId = Hashid::singleDecode($hashId);
			$atk = AtkItem::findOrFail($atkId);

			$this->form_validation->set_rules('tahun', 'Tahun', [
				'required',
				[
					'tahun_check',
					function ($val) use ($atk) {
						$cekTahun = AtkStock::where('atk_item_id', $atk->id)->where('tahun', $val)->first();
						if ($cekTahun) {
							$this->form_validation->set_message('tahun_check', 'Tidak dapat menggunakan tahun yang sama');
							return FALSE;
						}
						return TRUE;
					}
				]
			]);
			$this->form_validation->set_rules('stock', 'Stock', 'required|numeric');

			$tahun = $this->input->post('tahun', true);

			if ($this->form_validation->run() === FALSE) {
				$this->output
					->set_content_type("text/html")
					->set_output(
						Templ::component(
							"stock_opname/components/atk_stock_form",
							compact('atk')
						)
					);
				return;
			}


			AtkStock::create([
				'atk_item_id' => $atkId,
				'tahun' => $tahun,
				'stock' => $this->input->post('stock', true),
			]);

			$this->output->set_header('HX-Trigger: action-success');

			$this->output
				->set_content_type("text/html")
				->set_output(
					'<div class="alert alert-success">
						<i class="ti ti-check"></i> Stock berhasil ditambahkan. Jendela akan ditutup dalam 2 detik.
					</div>'
				);
		} catch (\Throwable $th) {
			$this->output
				->set_content_type("text/html")
				->set_output(
					Templ::component("components/exception_alert", [
						"message" => $th->getMessage()
					])
				);
		}
	}

	public function stock_table($hashId)
	{
		$atkId = Hashid::singleDecode($hashId);

		$stocks = AtkStock::where('atk_item_id', $atkId)
			->orderBy('tahun', 'desc')
			->get();

		$this->output
			->set_content_type("text/html")
			->set_output(
				Templ::component(
					"stock_opname/components/atk_stock_table",
					compact('stocks')
				)
			);
	}

	public function stock_delete($hashid)
	{
		$atkId = Hashid::singleDecode($hashid);

		$stock = AtkStock::find($atkId);
		try {
			if (!$stock) {
				throw new Exception("Tidak ada data");
			}
			if ((int) $stock->tahun == (int) date("Y")) {
				throw new Exception("Tidak bisa menghapus stock tahun ini");
			}
			$stock->delete();
			$this->output->set_header("HX-Trigger: action-success")->set_output("Menghapus stock berhasil");	
		} catch (\Throwable $th) {
			$this->output->set_output($th->getMessage());
		}
	}

	public function add_transaction($id)
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules(
			'nama_barang',
			'Nama Barang',
			'required|trim'
		);

		$this->form_validation->set_rules(
			'waktu',
			'Waktu Transaksi',
			'required'
		);

		$this->form_validation->set_rules(
			'restock',
			'Restock',
			'numeric|greater_than_equal_to[0]'
		);

		$this->form_validation->set_rules(
			'pengeluaran',
			'Pengeluaran',
			'numeric|greater_than_equal_to[0]'
		);

		$this->form_validation->set_rules(
			'keterangan',
			'Keterangan',
			'trim|max_length[255]'
		);

		$this->form_validation->set_rules(
			'atk_item_id',
			'Item ATK',
			'required'
		);

		$this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run() === FALSE) {

			// ❗ KHUSUS HTMX → render ulang FORM
			return $this->output
				->set_content_type('text/html')
				->set_output(
					Templ::component('stock_opname_atk/components/add_atk_trans_form')
				);
		}

		$this->output
			->set_header('HX-Trigger: action-success')
			->set_content_type('text/html')
			->set_output(
				'<div class="alert alert-success">Transaksi berhasil disimpan</div>'
			);
	}

	public function stock_calculation($id)
	{
		$restock     = (int) $this->input->get('restock');
		$pengeluaran = (int) $this->input->get('pengeluaran');
		$atkId = Hashid::singleDecode($id);
		$stockAwal = AtkStock::where('atk_item_id', $atkId)
			->where('tahun', date('Y'))
			->value('stock') ?? 0;

		$sisaStock = $stockAwal + $restock - $pengeluaran;

		return $this->output
			->set_content_type('text/html')
			->set_output(
				Templ::component('stock_opname_atk/stock_info', [
					'stock_awal'  => $stockAwal,
					'restock'     => $restock,
					'pengeluaran' => $pengeluaran,
					'sisa_stock'  => $sisaStock
				])
			);
	}
}
