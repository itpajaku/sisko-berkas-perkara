<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\PerkaraPutusan;
use Carbon\Carbon;

class MinutasiPerkaraController extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Halaman utama monitoring minutasi perkara
	 */
	public function index()
	{
		$this->load->library('pagination');

		$search = $this->input->get('search', true);
		$inputBulan = $this->input->get('bulan', true);

		if ($inputBulan) {
			$now = Carbon::parse($inputBulan . '-01'); // format YYYY-MM
		} else {
			$now = Carbon::now();
		}

		$query = PerkaraPutusan::with(['perkara.perkara_penetapan'])
			->whereNull('tanggal_minutasi')
			->whereMonth('tanggal_putusan', $now->month)
			->whereYear('tanggal_putusan', $now->year);

		if ($search) {
			$query->whereHas('perkara', function ($q) use ($search) {
				$q->where('nomor_perkara', 'LIKE', "%{$search}%");
			});
		}

		$config = $this->paginationConfig();
		$config['base_url'] = site_url('minutasi_perkara/page');
		$config['total_rows'] = $query->count();
		$config['reuse_query_string'] = true;

		$total_year = PerkaraPutusan::whereNull('tanggal_minutasi')
			->whereYear('tanggal_putusan', $now->year)
			->count();

		$this->pagination->initialize($config);

		$page = $this->uri->segment(3, 1);
		$offset = ($page - 1) * $config['per_page'];

		// Calculate totals per hakim
		$hakimData = \App\Models\Hakim::select('hakim_pn.id', 'hakim_pn.nama_gelar')
			->selectRaw('COUNT(pp.perkara_id) as total_belum_minutasi')
			->leftJoin('perkara_hakim_pn as ph', function ($join) {
				$join->on('hakim_pn.id', '=', 'ph.hakim_id')
					->where('ph.urutan', 1)
					->where('ph.aktif', 'Y');
			})
			->leftJoin('perkara_putusan as pp', function ($join) use ($now) {
				$join->on('ph.perkara_id', '=', 'pp.perkara_id')
					->whereNull('pp.tanggal_minutasi')
					->whereMonth('pp.tanggal_putusan', '=', $now->month)
					->whereYear('pp.tanggal_putusan', '=', $now->year);
			})
			->where('hakim_pn.aktif', 'Y')
			->groupBy('hakim_pn.id', 'hakim_pn.nama_gelar')
			->get();

		Templ::render("minutasi_perkara/index", [
			"page_name" => "Minutasi Perkara",
			"data" => $query->latest('tanggal_putusan')
				->limit($config['per_page'])
				->offset($offset)
				->get(),
			"search" => $search,
			"bulan_value" => $now->format('Y-m'),
			"bulan_label" => $now->translatedFormat('F Y'),
			"offset" => $offset,
			"total_data" => $config['total_rows'],
			"total_year" => $total_year,
			"hakim_data" => $hakimData, // Pass hakim data to view
		])
			->layout("layouts/main_layout", [
				"title" => "Minutasi Perkara",
			]);
	}

	/**
	 * Endpoint HTMX pagination + search
	 */
	public function pagination($page = 1)
	{
		if (!MethodFilter::isHeader("HX-Request")) {
			redirect("minutasi_perkara");
			exit;
		}
		MethodFilter::mustHeader("HX-Request");

		$this->load->library('pagination');

		$search = $this->input->get('search', true);
		$inputBulan = $this->input->get('bulan', true);

		if ($inputBulan) {
			$now = Carbon::parse($inputBulan . '-01');
		} else {
			$now = Carbon::now();
		}

		$query = PerkaraPutusan::with(['perkara.perkara_penetapan'])
			->whereNull('tanggal_minutasi')
			->whereMonth('tanggal_putusan', $now->month)
			->whereYear('tanggal_putusan', $now->year);

		if ($search) {
			$query->whereHas('perkara', function ($q) use ($search) {
				$q->where('nomor_perkara', 'LIKE', "%{$search}%");
			});
		}

		$config = $this->paginationConfig();
		$config['base_url'] = site_url('minutasi_perkara/page');
		$config['total_rows'] = $query->count();
		$config['reuse_query_string'] = true;

		$total_year = PerkaraPutusan::whereNull('tanggal_minutasi')
			->whereYear('tanggal_putusan', $now->year)
			->count();

		$this->pagination->initialize($config);

		// If $page is not a number, fallback
		if (!is_numeric($page) || $page < 1) {
			$page = 1;
		}

		// Actually best to rely on what the pagination library sees just in case
		// But in routes, page is segment 3.
		$page = (int) $this->uri->segment(3, $page);
		$offset = ($page - 1) * $config['per_page'];

		// Calculate totals per hakim for HTMX request
		$hakimData = \App\Models\Hakim::select('hakim_pn.id', 'hakim_pn.nama_gelar')
			->selectRaw('COUNT(pp.perkara_id) as total_belum_minutasi')
			->leftJoin('perkara_hakim_pn as ph', function ($join) {
				$join->on('hakim_pn.id', '=', 'ph.hakim_id')
					->where('ph.urutan', 1)
					->where('ph.aktif', 'Y');
			})
			->leftJoin('perkara_putusan as pp', function ($join) use ($now) {
				$join->on('ph.perkara_id', '=', 'pp.perkara_id')
					->whereNull('pp.tanggal_minutasi')
					->whereMonth('pp.tanggal_putusan', '=', $now->month)
					->whereYear('pp.tanggal_putusan', '=', $now->year);
			})
			->where('hakim_pn.aktif', 'Y')
			->groupBy('hakim_pn.id', 'hakim_pn.nama_gelar')
			->get();

		// Create HTML for hakim cards
		$hakimCardsHtml = '';
		foreach ($hakimData as $hakim) {
			if ($hakim->total_belum_minutasi > 0) {
				$hakimCardsHtml .= '
				<div class="col-6 col-md-4 col-lg-3 mb-2">
					<div class="card shadow-sm h-100">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="' . htmlspecialchars($hakim->nama_gelar) . '">' . htmlspecialchars($hakim->nama_gelar) . '</div>
									<h4 class="mb-0 fw-bold text-primary">' . number_format($hakim->total_belum_minutasi, 0, ',', '.') . '</h4>
								</div>
								<div class="text-primary fs-3 rounded bg-primary-subtle p-1 d-flex">
									<i class="ti ti-user"></i>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
		}

		$this->output->set_content_type('text/html')->set_output(
			Templ::component('minutasi_perkara/components/tabel_monitoring', [
				'data' => $query->latest('tanggal_putusan')
					->limit($config['per_page'])
					->offset($offset)
					->get(),
				'offset' => $offset,
				'bulan_label' => $now->translatedFormat('F Y')
			]) . '
			<div id="periode-label" hx-swap-oob="true">
				<small class="text-muted">Periode: ' . $now->translatedFormat('F Y') . '</small>
			</div>
			<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
				<div class="col-md-6 mb-3 mb-md-0">
					<div class="card shadow-sm h-100 border-warning border-start border-3">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Total Belum Minutasi (Bulan ' . $now->translatedFormat('F Y') . ')</small>
									<h3 class="mb-0 fw-bold text-warning">' . number_format($config['total_rows'], 0, ',', '.') . '</h3>
								</div>
								<div class="text-warning fs-1 p-2">
									<i class="ti ti-clock"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 mb-3 mb-md-0">
					<div class="card shadow-sm h-100 border-danger border-start border-3">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Total Belum Minutasi (Tahun ' . $now->year . ')</small>
									<h3 class="mb-0 fw-bold text-danger">' . number_format($total_year, 0, ',', '.') . '</h3>
								</div>
								<div class="text-danger fs-1 p-2">
									<i class="ti ti-calendar-stats"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-3" id="hakim-cards" hx-swap-oob="true">
				' . $hakimCardsHtml . '
			</div>'
		);
	}

	/**
	 * Konfigurasi pagination CI3 + Bootstrap 5
	 */
	private function paginationConfig(): array
	{
		return [
			'per_page' => 20,
			'uri_segment' => 3,
			'use_page_numbers' => TRUE,
			'full_tag_open'   => '<nav><ul class="pagination justify-content-center">',
			'full_tag_close'  => '</ul></nav>',
			'attributes'      => ['class' => 'page-link'],
			'cur_tag_open'    => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close'   => '</span></li>',
			'num_tag_open'    => '<li class="page-item">',
			'num_tag_close'   => '</li>',
		];
	}
}
