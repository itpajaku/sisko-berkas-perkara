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
	 * Private helper to fetch minutasi data.
	 *
	 * @param string $jenis 'gugatan' | 'permohonan' | null
	 * @return array
	 */
	private function _fetch_minutasi_data(string $jenis = null, int $page = 1)
	{
		if ($jenis === 'all') $jenis = null;

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

		// Filter by jenis perkara (G | P) based on the route segment
		if ($jenis === 'gugatan') {
			$query->whereHas('perkara', function ($q) {
				$q->where('nomor_perkara', 'LIKE', "%Pdt.G%");
			});
		} else if ($jenis === 'permohonan') {
			$query->whereHas('perkara', function ($q) {
				$q->where('nomor_perkara', 'LIKE', "%Pdt.P%");
			});
		}

		if ($search) {
			$query->whereHas('perkara', function ($q) use ($search) {
				$q->where('nomor_perkara', 'LIKE', "%{$search}%");
			});
		}

		$config = $this->paginationConfig();

		// Adjust base URL based on jenis
		$baseUrlSuffix = $jenis ? "_{$jenis}" : "";
		$config['base_url'] = site_url("minutasi_perkara{$baseUrlSuffix}/page");

		$config['total_rows'] = $query->count();
		$config['reuse_query_string'] = true;

		$totalYearQuery = PerkaraPutusan::whereNull('tanggal_minutasi')
			->whereYear('tanggal_putusan', $now->year);

		// Apply the same jenis filter to total_year query
		if ($jenis === 'gugatan') {
			$totalYearQuery->whereHas('perkara', function ($q) {
				$q->where('nomor_perkara', 'LIKE', "%Pdt.G%");
			});
		} else if ($jenis === 'permohonan') {
			$totalYearQuery->whereHas('perkara', function ($q) {
				$q->where('nomor_perkara', 'LIKE', "%Pdt.P%");
			});
		}

		$total_year = $totalYearQuery->count();

		$this->pagination->initialize($config);

		// Check segment(4) which is the page number e.g: minutasi_perkara_gugatan/page/2
		// If segment 3 is 'gugatan', then segment 2 is 'index' or 'pagination' mostly.
		// Since we use the routes:
		// minutasi_perkara_gugatan/page/(:num) -> MinutasiPerkaraController/pagination/gugatan/$1
		// the segment(1) is 'minutasi_perkara_gugatan', segment(2) is 'page', segment(3) is $page.

		// If we are calling from index endpoint: segment(1)=minutasi_perkara_gugatan, segment(2)=empty (default index)
		// Or in route we map `minutasi_perkara_gugatan` to `MinutasiPerkaraController/index/gugatan`
		// which means $page might be passed as an argument if it's there.

		// Ensure page is at least 1
		if ($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $config['per_page'];

		// Calculate totals per hakim
		// We also need to filter the total per hakim sum by jenis
		$hakimDataCountQuery = \App\Models\Hakim::select('hakim_pn.id', 'hakim_pn.nama_gelar')
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
			});

		// To filter hakim count by jenis, we need to join `perkara` table on `pp.perkara_id`
		// but since it's a leftJoin, we need to be careful with condition scopes.
		// Actually, the leftJoin for `pp` gets records, and then we filter the aggregate.
		// Since the logic counts `pp.perkara_id`, we can join `perkara` and put condition there.

		if ($jenis === 'gugatan') {
			$hakimDataCountQuery->leftJoin('perkara as p', function ($join) {
				$join->on('pp.perkara_id', '=', 'p.perkara_id')
					->where('p.nomor_perkara', 'LIKE', '%Pdt.G%');
			});
			// Only count those that matched
			$hakimDataCountQuery->selectRaw('COUNT(p.perkara_id) as total_belum_minutasi_filtered');
		} else if ($jenis === 'permohonan') {
			$hakimDataCountQuery->leftJoin('perkara as p', function ($join) {
				$join->on('pp.perkara_id', '=', 'p.perkara_id')
					->where('p.nomor_perkara', 'LIKE', '%Pdt.P%');
			});
			$hakimDataCountQuery->selectRaw('COUNT(p.perkara_id) as total_belum_minutasi_filtered');
		}

		$hakimDataRaw = $hakimDataCountQuery
			->where('hakim_pn.aktif', 'Y')
			->groupBy('hakim_pn.id', 'hakim_pn.nama_gelar')
			->get();

		// Ensure we use the correct count property
		$hakimData = $hakimDataRaw->map(function ($hakim) use ($jenis) {
			if ($jenis) {
				$hakim->total_belum_minutasi = $hakim->total_belum_minutasi_filtered;
			}
			return $hakim;
		});

		$data = $query->latest('tanggal_putusan')
			->limit($config['per_page'])
			->offset($offset)
			->get();

		return [
			'jenis' => $jenis,
			'page_name' => "Minutasi Perkara " . ucfirst($jenis ?? 'Keseluruhan'),
			'data' => $data,
			'search' => $search,
			'bulan_value' => $now->format('Y-m'),
			'bulan_label' => $now->translatedFormat('F Y'),
			'offset' => $offset,
			'total_data' => $config['total_rows'],
			'total_year' => $total_year,
			'hakim_data' => $hakimData,
			'page' => $page,
			'now' => $now,
			'per_page' => $config['per_page']
		];
	}

	/**
	 * Halaman utama monitoring minutasi perkara
	 */
	public function index($jenis = null)
	{
		$viewData = $this->_fetch_minutasi_data($jenis);

		Templ::render("minutasi_perkara/index", $viewData)
			->layout("layouts/main_layout", [
				"title" => $viewData['page_name'],
			]);
	}

	/**
	 * Endpoint HTMX pagination + search
	 */
	public function pagination($jenis = null, $page = 1)
	{
		if ($jenis === 'all') $jenis = null;

		if (!MethodFilter::isHeader("HX-Request")) {
			// Redirect back to either index or proper prefix
			$suffix = $jenis ? "_{$jenis}" : "";
			redirect("minutasi_perkara{$suffix}");
			exit;
		}
		MethodFilter::mustHeader("HX-Request");

		// Override segment for page extraction in helper
		// The helper gets page via URL segment but here we have the $page argument.
		// Let's pass it to HTTP GET temporarily to ensure clean extraction?
		// Since we're using routes mapping MinutasiPerkaraController/pagination/gugatan/$1
		// Actually _fetch_minutasi_data can just pick it up correctly since $this->uri->segment() works with the final routed URL segments.

		$viewData = $this->_fetch_minutasi_data($jenis, $page);
		$now = $viewData['now'];

		// Create HTML for hakim cards
		$hakimCardsHtml = '';
		foreach ($viewData['hakim_data'] as $hakim) {
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
				'data' => $viewData['data'],
				'offset' => $viewData['offset'],
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
									<h3 class="mb-0 fw-bold text-warning">' . number_format($viewData['total_data'], 0, ',', '.') . '</h3>
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
									<h3 class="mb-0 fw-bold text-danger">' . number_format($viewData['total_year'], 0, ',', '.') . '</h3>
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