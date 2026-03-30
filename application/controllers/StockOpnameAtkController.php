<?php

use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\AtkItem;
use App\Models\AtkStock;
use App\Models\AtkTransaksi;
use App\Models\ReferensiAtkDataTable;
use App\Traits\ItemAtkValidation;
use Illuminate\Database\Capsule\Manager as DB;
use Symfony\Component\Config\Builder\Method;

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
				"stock_opname/components/calculate_stock_atk",
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

	public function add_transaction()
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

		if ($this->form_validation->run() === FALSE) {
			return $this->output
				->set_content_type('text/html')
				->set_output(
					Templ::component('stock_opname_atk/components/add_atk_trans_form')
				);
		}

		try {
			$atk_item_id = Hashid::singleDecode(RequestBody::post("atk_item_id"));
			$checkStock = AtkStock::where("atk_item_id", $atk_item_id)->where("tahun", date("Y"))->first();
			if (!$checkStock) {
				throw new Exception("Tidak bisa menambah transaksi atas item ini. Tidak ada stock");
			}
			DB::connection("default")->transaction(function () use ($atk_item_id) {
				$atk = AtkTransaksi::create([
					"atk_item_id" => $atk_item_id,
					"waktu" => RequestBody::post("waktu"),
					"restock" => (int) RequestBody::post("restock") ?? 0,
					"pengeluaran" => (int) RequestBody::post("pengeluaran") ?? 0,
					"keterangan" => RequestBody::post("keterangan")
				]);
				$stockThisYear = $atk->stocks()->where("tahun", date(("Y")))->first();
				$atk->current_stock = $stockThisYear->stock;
				$atk->after_stock = (int) $stockThisYear->stock + (int) $atk->restock - (int) $atk->pengeluaran;
				$atk->save();
				$stockThisYear->where("tahun", date(("Y")))->update([
					"stock" => $atk->after_stock
				]);
			});
			$triggers = [
				"htmx:toastr" => [
					"level" => "success",
					"message" => "Input transaksi berhasil jendela akan ditutup dalam 2 detik"
				],
				"action-success" => true
			];
			$this->output
				->set_header("HX-Trigger:" . json_encode($triggers))
				->set_output(Templ::component("components/success_alert", [
					"message" => "Input transaksi berhasil. Jendela akan ditutup dalam 2 detik"
				]));
		} catch (\Throwable $th) {
			$toastErr = [
				"htmx:toastr" => [
					"level" => "error",
					"message" => $th->getMessage()
				]
			];
			header("HX-Trigger:contol");
			$this->output
				->set_status_header(200)
				->set_header("HX-Trigger: " . json_encode([
					"htmx:toastr" => [
						"level" => "error",
						"message" => $th->getMessage(),
					],
				]))
				->set_output(Templ::component("components/exception_alert", [
					"message" => $th->getMessage()
				]));
		}
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
				Templ::component('stock_opname_atk/components/calculate_stock_info', [
					'stock_awal'  => $stockAwal,
					'restock'     => $restock,
					'pengeluaran' => $pengeluaran,
					'sisa_stock'  => $sisaStock
				])
			);
	}

	public function datatable()
	{
		MethodFilter::mustHeader("HX-Request-DataTable");
		$draw   = $this->input->get('draw');
		$start  = $this->input->get('start', 0);
		$length = $this->input->get('length', 10);
		$search = $this->input->get('search.value');

		$bulan = $this->input->get("bulan") ?? date("m");
		$tahun = $this->input->get("tahun") ?? date("Y");
		$jumlahHari = 31;

		$itemQuery = AtkItem::query()
			->where('status', 1);

		if ($search) {
			$itemQuery->where('name', 'like', "%{$search}%");
		}

		$recordsTotal = AtkItem::where('status', 1)->count();
		$recordsFiltered = $itemQuery->count();

		$items = $itemQuery
			->where('status', 1)
			->orderBy('name')
			->get();

		$itemIds = $items->pluck('id');

		$rows = DB::table('atk_transaksi')
			->whereIn('atk_item_id', $itemIds)
			->whereMonth('waktu', $bulan)
			->whereYear('waktu', $tahun)
			->select(
				'atk_item_id',
				DB::raw('DAY(waktu) as tanggal'),
				DB::raw('SUM(COALESCE(restock,0)) as restock'),
				DB::raw('SUM(COALESCE(pengeluaran,0)) as pengeluaran')
			)
			->groupBy('atk_item_id', DB::raw('DAY(waktu)'))
			->get();

		$transaksiPivot = [];

		foreach ($rows as $r) {
			$transaksiPivot[$r->atk_item_id][$r->tanggal] = [
				(int)$r->restock,
				(int)$r->pengeluaran
			];
		}

		$stocks = AtkStock::whereIn("atk_item_id", $itemIds)->where("tahun", $tahun)->get();
		$data = [];
		foreach ($items as $index => $item) {

			$row = [];

			$row[] = $start + $index + 1;
			$row[] = $item->name;

			for ($d = 1; $d <= $jumlahHari; $d++) {

				$t = $transaksiPivot[$item->id][$d] ?? [0, 0];

				$row[] = Templ::component("stock_opname/components/kolom_transaksi", [
					"t" => $t,
					"tanggal" => "$tahun-$bulan-$d",
					"item_id" => $item->id
				]);
			}

			$totalRestock = 0;
			$totalPengeluaran = 0;

			for ($d = 1; $d <= 31; $d++) {
				$t = $transaksiPivot[$item->id][$d] ?? [0, 0];

				$totalRestock += $t[0];
				$totalPengeluaran += $t[1];
			}

			$row[] = "
				<div class='small text-success fw-bold'>+{$totalRestock}</div>
				<div class='small text-danger fw-bold'>-{$totalPengeluaran}</div>
			";

			if ($stocks->where("atk_item_id", $item->id)->first()) {
				$row[] = $stocks->where("atk_item_id", $item->id)->first()->stock;
			} else {
				$row[] = 0;
			}

			$data[] = $row;
		}

		$this->output->set_content_type("application/json")->set_output(json_encode([
			"draw" => intval($draw),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data
		]));
	}

	public function modal_detail()
	{
		MethodFilter::mustHeader("HX-Request-Component");
		$data = AtkTransaksi::where('atk_item_id', $this->input->get("item_id"))
			->whereDate('waktu', $this->input->get("date"))
			->orderBy('waktu', 'asc')
			->get();
		$html = Templ::component("stock_opname/components/modal_detail_trans", compact("data"));

		$this->output->set_content_type("text/html")->set_output($html);
	}

	public function delete_trans($hid)
	{
		MethodFilter::must("delete");
		try {
			$id = Hashid::singleDecode($hid);
			DB::connection("default")->transaction(function () use ($id) {
				$atk = AtkTransaksi::find($id);
				$stock = $atk->stocks()->where("tahun", date("Y"))->first();
				$stock->update([
					"stock" => (int) $stock->stock + (int) $atk->pengeluaran - (int) $atk->restock
				]);
				$atk->delete();
			});
			$this->output->set_output("Berhasil Menghapus Transaksi");
		} catch (\Throwable $th) {
			$this->output->set_output($th->getMessage());
		}
	}

	public function dashboard()
	{
		$page_name = "Dashboard Monitoring ATK";
		$items = AtkItem::with(['stocks' => function ($q) {
			$q->where('tahun', date('Y'));
		}])->where("status", 1)->get();
		Templ::render("stock_opname/dashboard_page_atk", [
			"items" => $items,
			"breadcrumb" => Templ::component("layouts/page_header", [
				"page_name" => $page_name,
				"breadcrumbs" => [
					["name" => "Dashboard", "url" => site_url("stock_opname_atk/dashboard")],
				],
			])
		])->layout("layouts/main_layout", [
			"title" => $page_name,
		]);
	}

	public function chart($hid)
	{
		MethodFilter::must('get');
		MethodFilter::mustHeader("HX-Request-Chart");
		$itemId = Hashid::singleDecode($hid);
		$item = AtkItem::findOrFail($itemId);
		try {
			$year = $this->input->get('tahun') ?? date("Y");
			$month = $this->input->get('bulan') ?? date("m");

			$transaksi = DB::table('atk_transaksi')
				->selectRaw("
        DAY(waktu) as hari,
        SUM(COALESCE(restock,0)) as total_restock,
        SUM(COALESCE(pengeluaran,0)) as total_pengeluaran
    ")
				->where('atk_item_id', $itemId)
				->whereYear('waktu', $year)
				->whereMonth('waktu', $month)
				->groupBy(DB::raw('DAY(waktu)'))
				->get()
				->keyBy('hari');
			$days = collect(range(1, 31));
			$series = [
				[
					'name' => 'Restock',
					'data' => $days
						->map(fn($d) => optional($transaksi->get($d))->total_restock ?? 0)
						->values()
				],
				[
					'name' => 'Pengeluaran',
					'data' => $days
						->map(fn($d) => optional($transaksi->get($d))->total_pengeluaran ?? 0)
						->values()
				]
			];
			$categories = $days->map(fn($d) => (string) $d)->values();
			$this->output
				->set_content_type("application/json")
				->set_output(json_encode([
					'categories' => $categories,
					'series' => $series,
					'item_name' => $item->name,
					'month' => nama_bulan($month - 1)
				]));
		} catch (\Throwable $th) {
			$this->output->set_output($th->getMessage());
		}
	}

	public function laporan()
	{
		$page_name = "Laporan Transaksi ATK";
		$items = AtkItem::where("status", 1)->get();
		Templ::render("stock_opname/laporan_atk_page", [
			"items" => $items,
			"breadcrumb" => Templ::component("layouts/page_header", [
				"page_name" => $page_name,
				"breadcrumbs" => [
					["name" => "Dashboard", "url" => site_url("stock_opname_atk/dashboard")],
					["name" => "Laporan", "url" => site_url("stock_opname_atk/laporan")],
				],
			])
		])->layout("layouts/main_layout", [
			"title" => $page_name,
		]);
	}
}
