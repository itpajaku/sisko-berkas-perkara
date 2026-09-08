<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\Hakim;
use App\Models\PerkaraIkrarTalak;
use Carbon\Carbon;

class MonitoringIkrarTalakController extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Helper function to normalize Hakim name for grouping and filtering.
	 */
	private function _normalize_hakim_name(string $name): string
	{
		$clean = preg_replace('/\b(Drs\.|Dr\.|H\.|Hj\.|S\.Ag\.|S\.H\.|M\.H\.|S\.E\.I\.|M\.Ag\.|M\.Pd\.)\b/i', '', $name);
		$clean = trim(preg_replace('/[,\.\s]+/', ' ', $clean));
		return $clean;
	}

	/**
	 * Private helper to fetch monitoring ikrar talak data.
	 *
	 * @param int $page
	 * @return array
	 */
	private function _fetch_ikrar_data(int $page = 1)
	{
		$this->load->library('pagination');

		$search = $this->input->get('search', true);
		$filterWaktu = $this->input->get('filter_waktu', true);
		$isAllYearInput = $this->input->get('is_all_year', true);
		$hakimFilter = $this->input->get('hakim', true);
		$statusFilter = $this->input->get('status', true); // 'belum' | 'sudah' | ''

		if (!$filterWaktu) {
			$filterWaktu = Carbon::now()->format('Y-m');
		}

		$isAllYear = ($isAllYearInput == '1') || (strpos($filterWaktu, '-all') !== false);
		$yearValue = substr($filterWaktu, 0, 4);

		if ($isAllYear) {
			$now = Carbon::parse($yearValue . '-01-01');
		} else {
			$now = Carbon::parse($filterWaktu . '-01'); // format YYYY-MM
		}

		$query = PerkaraIkrarTalak::with([
			'perkara.perkara_putusan',
			'perkara.perkara_penetapan',
		])
			->join('perkara', 'perkara_ikrar_talak.perkara_id', '=', 'perkara.perkara_id')
			->whereYear('perkara_ikrar_talak.tanggal_penetapan_sidang_ikrar', $now->year);

		if (!$isAllYear) {
			$query->whereMonth('perkara_ikrar_talak.tanggal_penetapan_sidang_ikrar', $now->month);
		}

		// Filter status ikrar (default: belum ikrar talak jika status filter belum diset, atau sesuai filter)
		if ($statusFilter === 'sudah') {
			$query->whereNotNull('perkara_ikrar_talak.tgl_ikrar_talak');
		} else if ($statusFilter === 'all') {
			// tidak ada filter status
		} else {
			// default atau 'belum': belum ikrar talak
			$query->whereNull('perkara_ikrar_talak.tgl_ikrar_talak');
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('perkara.nomor_perkara', 'LIKE', "%{$search}%")
				  ->orWhere('perkara_ikrar_talak.majelis_hakim_nama', 'LIKE', "%{$search}%")
				  ->orWhere('perkara_ikrar_talak.panitera_pengganti_text', 'LIKE', "%{$search}%");
			});
		}

		if ($hakimFilter) {
			$query->where('perkara_ikrar_talak.majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
		}

		$config = $this->paginationConfig();
		$config['base_url'] = site_url("monitoring_ikrar_talak/page");
		$config['total_rows'] = (clone $query)->count();
		$config['reuse_query_string'] = true;

		// Hitung statistik untuk summary card
		$totalYearQuery = PerkaraIkrarTalak::whereYear('tanggal_penetapan_sidang_ikrar', $now->year);
		if ($hakimFilter) {
			$totalYearQuery->where('majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
		}
		$totalBelumIkrarTahun = (clone $totalYearQuery)->whereNull('tgl_ikrar_talak')->count();
		$totalSudahIkrarTahun = (clone $totalYearQuery)->whereNotNull('tgl_ikrar_talak')->count();

		$totalPeriodeQuery = PerkaraIkrarTalak::whereYear('tanggal_penetapan_sidang_ikrar', $now->year);
		if (!$isAllYear) {
			$totalPeriodeQuery->whereMonth('tanggal_penetapan_sidang_ikrar', $now->month);
		}
		if ($hakimFilter) {
			$totalPeriodeQuery->where('majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
		}
		$totalBelumIkrarPeriode = (clone $totalPeriodeQuery)->whereNull('tgl_ikrar_talak')->count();
		$totalSudahIkrarPeriode = (clone $totalPeriodeQuery)->whereNotNull('tgl_ikrar_talak')->count();

		$this->pagination->initialize($config);

		if ($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $config['per_page'];

		// Grouping data per Hakim untuk Cards Ringkasan
		$hakimDataQuery = PerkaraIkrarTalak::whereYear('tanggal_penetapan_sidang_ikrar', $now->year)
			->whereNotNull('majelis_hakim_nama')
			->where('majelis_hakim_nama', '!=', '');

		if (!$isAllYear) {
			$hakimDataQuery->whereMonth('tanggal_penetapan_sidang_ikrar', $now->month);
		}

		$rawHakimList = $hakimDataQuery->select(
			'majelis_hakim_nama',
			\Illuminate\Database\Capsule\Manager::raw('COUNT(*) as total_perkara'),
			\Illuminate\Database\Capsule\Manager::raw('SUM(CASE WHEN tgl_ikrar_talak IS NULL THEN 1 ELSE 0 END) as total_belum_ikrar'),
			\Illuminate\Database\Capsule\Manager::raw('SUM(CASE WHEN tgl_ikrar_talak IS NOT NULL THEN 1 ELSE 0 END) as total_sudah_ikrar')
		)
			->groupBy('majelis_hakim_nama')
			->get();

		// Mengelompokkan variasi penulisan nama hakim
		$hakimAktif = Hakim::where('aktif', 'Y')->get();
		$groupedHakim = [];

		foreach ($rawHakimList as $row) {
			$matchedKey = null;
			$displayName = $row->majelis_hakim_nama;

			foreach ($hakimAktif as $h) {
				$cleanCore = $this->_normalize_hakim_name($h->nama_gelar);
				$words = explode(' ', $cleanCore);
				foreach ($words as $w) {
					if (strlen($w) >= 5 && stripos($row->majelis_hakim_nama, $w) !== false) {
						$matchedKey = $w;
						$displayName = $h->nama_gelar;
						break 2;
					}
				}
			}

			if (!$matchedKey) {
				$cleanName = $this->_normalize_hakim_name($row->majelis_hakim_nama);
				$parts = explode(' ', $cleanName);
				$matchedKey = !empty($parts[0]) ? $parts[0] : $row->majelis_hakim_nama;
			}

			if (!isset($groupedHakim[$matchedKey])) {
				$groupedHakim[$matchedKey] = (object) [
					'key' => $matchedKey,
					'nama_gelar' => $displayName,
					'total_belum_ikrar' => 0,
					'total_sudah_ikrar' => 0,
					'total_perkara' => 0,
				];
			}

			$groupedHakim[$matchedKey]->total_belum_ikrar += (int) $row->total_belum_ikrar;
			$groupedHakim[$matchedKey]->total_sudah_ikrar += (int) $row->total_sudah_ikrar;
			$groupedHakim[$matchedKey]->total_perkara += (int) $row->total_perkara;
		}

		// Urutkan hakim berdasarkan jumlah belum ikrar terbanyak
		usort($groupedHakim, function ($a, $b) {
			return $b->total_belum_ikrar <=> $a->total_belum_ikrar;
		});

		$data = $query->select('perkara_ikrar_talak.*')
			->orderBy('perkara_ikrar_talak.tanggal_penetapan_sidang_ikrar', 'desc')
			->limit($config['per_page'])
			->offset($offset)
			->get();

		return [
			'page_name' => "Monitoring Ikrar Talak",
			'data' => $data,
			'search' => $search,
			'filter_waktu' => $filterWaktu,
			'bulan_value' => $now->format('Y-m'),
			'bulan_label' => $isAllYear ? ('Sepanjang Tahun ' . $now->year) : $now->translatedFormat('F Y'),
			'is_all_year' => $isAllYear,
			'offset' => $offset,
			'total_data' => $config['total_rows'],
			'total_belum_ikrar_periode' => $totalBelumIkrarPeriode,
			'total_sudah_ikrar_periode' => $totalSudahIkrarPeriode,
			'total_belum_ikrar_tahun' => $totalBelumIkrarTahun,
			'total_sudah_ikrar_tahun' => $totalSudahIkrarTahun,
			'hakim_data' => $groupedHakim,
			'hakim_filter' => $hakimFilter,
			'status_filter' => $statusFilter ?: 'belum',
			'page' => $page,
			'now' => $now,
			'per_page' => $config['per_page'],
		];
	}

	public function index()
	{
		$viewData = $this->_fetch_ikrar_data();

		Templ::render("monitoring_ikrar_talak/index", $viewData)
			->layout("layouts/main_layout", [
				"title" => $viewData['page_name'],
			]);
	}

	public function pagination($page = 1)
	{
		if (!MethodFilter::isHeader("HX-Request")) {
			redirect("monitoring_ikrar_talak");
			exit;
		}
		MethodFilter::mustHeader("HX-Request");

		$viewData = $this->_fetch_ikrar_data((int) $page);
		$now = $viewData['now'];
		$hakimFilter = $viewData['hakim_filter'];

		$hakimCardsHtml = '';
		foreach ($viewData['hakim_data'] as $hakim) {
			if ($hakim->total_belum_ikrar > 0 || ($hakimFilter && stripos($hakim->nama_gelar, $hakimFilter) !== false)) {
				$isActive = ($hakimFilter && (stripos($hakim->nama_gelar, $hakimFilter) !== false || $hakimFilter == $hakim->key));
				$borderClass = $isActive ? 'border-primary border-2 shadow' : '';
				$hakimCardsHtml .= '
				<div class="col-6 col-md-4 col-lg-3 mb-2" style="cursor: pointer;" onclick="toggleHakimFilter(\'' . htmlspecialchars($hakim->key) . '\')">
					<div class="card shadow-sm h-100 ' . $borderClass . '">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="' . htmlspecialchars($hakim->nama_gelar) . '">' . htmlspecialchars($hakim->nama_gelar) . '</div>
									<h4 class="mb-0 fw-bold text-primary">' . number_format($hakim->total_belum_ikrar, 0, ',', '.') . ' <span class="text-muted fs-2 fw-normal">perkara</span></h4>
								</div>
								<div class="text-primary fs-3 rounded bg-primary-subtle p-1 d-flex">
									<i class="ti ti-gavel"></i>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
		}

		$this->output->set_content_type('text/html')->set_output(
			Templ::component('monitoring_ikrar_talak/components/tabel_monitoring', [
				'data' => $viewData['data'],
				'offset' => $viewData['offset'],
				'bulan_label' => $viewData['bulan_label'],
			]) . '
			<div id="periode-label" hx-swap-oob="true">
				<small class="text-muted">Periode: ' . $viewData['bulan_label'] . '</small>
			</div>
			<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
				<div class="col-md-6 mb-3 mb-md-0">
					<div class="card shadow-sm h-100 border-warning border-start border-3">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Belum Ikrar Talak (' . $viewData['bulan_label'] . ')</small>
									<h3 class="mb-0 fw-bold text-warning">' . number_format($viewData['total_belum_ikrar_periode'], 0, ',', '.') . '</h3>
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
									<small class="text-muted">Total Belum Ikrar Talak (Tahun ' . $now->year . ')</small>
									<h3 class="mb-0 fw-bold text-danger">' . number_format($viewData['total_belum_ikrar_tahun'], 0, ',', '.') . '</h3>
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
