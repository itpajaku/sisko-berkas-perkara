<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\PerkaraJadwalSidang;
use App\Models\Panitera;
use Carbon\Carbon;

class MonitoringBasController extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Private helper to fetch monitoring bas data.
	 *
	 * @param string $jenis 'gugatan' | 'permohonan' | null
	 * @return array
	 */
	private function _fetch_bas_data(string $jenis = null, int $page = 1)
	{
		if ($jenis === 'all') $jenis = null;

		$this->load->library('pagination');

		$search = $this->input->get('search', true);
		$inputBulan = $this->input->get('bulan', true);
		$paniteraId = $this->input->get('panitera_id', true);

		if ($inputBulan) {
			$now = Carbon::parse($inputBulan . '-01'); // format YYYY-MM
		} else {
			$now = Carbon::now();
		}

		$query = PerkaraJadwalSidang::with(['perkara'])
			->where(function($q) {
				$q->whereNull('edoc_bas')->orWhere('edoc_bas', '');
			})
			->whereMonth('tanggal_sidang', $now->month)
			->whereYear('tanggal_sidang', $now->year);

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

		if ($paniteraId) {
			$query->whereHas('perkara', function ($q) use ($paniteraId) {
				$q->whereExists(function($sq) use ($paniteraId) {
					$sq->select(\Illuminate\Database\Capsule\Manager::raw(1))
					   ->from('perkara_panitera_pn')
					   ->whereColumn('perkara_panitera_pn.perkara_id', 'perkara.perkara_id')
					   ->where('perkara_panitera_pn.panitera_id', $paniteraId)
					   ->where('perkara_panitera_pn.aktif', 'Y');
				});
			});
		}

		$config = $this->paginationConfig();

		// Adjust base URL based on jenis
		$baseUrlSuffix = $jenis ? "_{$jenis}" : "";
		$config['base_url'] = site_url("monitoring_bas{$baseUrlSuffix}/page");

		$config['total_rows'] = $query->count();
		$config['reuse_query_string'] = true;

		$totalYearQuery = PerkaraJadwalSidang::where(function($q) {
				$q->whereNull('edoc_bas')->orWhere('edoc_bas', '');
			})
			->whereYear('tanggal_sidang', $now->year);

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

		if ($paniteraId) {
			$totalYearQuery->whereHas('perkara', function ($q) use ($paniteraId) {
				$q->whereExists(function($sq) use ($paniteraId) {
					$sq->select(\Illuminate\Database\Capsule\Manager::raw(1))
					   ->from('perkara_panitera_pn')
					   ->whereColumn('perkara_panitera_pn.perkara_id', 'perkara.perkara_id')
					   ->where('perkara_panitera_pn.panitera_id', $paniteraId)
					   ->where('perkara_panitera_pn.aktif', 'Y');
				});
			});
		}

		$total_year = $totalYearQuery->count();

		$this->pagination->initialize($config);

		if ($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $config['per_page'];

		// Calculate totals per panitera
		$paniteraDataCountQuery = Panitera::select('panitera_pn.id', 'panitera_pn.nama_gelar')
			->selectRaw('COUNT(js.id) as total_belum_bas')
			->leftJoin('perkara_panitera_pn as pp', function ($join) {
				$join->on('panitera_pn.id', '=', 'pp.panitera_id')
					->where('pp.aktif', 'Y');
			})
			->leftJoin('perkara_jadwal_sidang as js', function ($join) use ($now) {
				$join->on('pp.perkara_id', '=', 'js.perkara_id')
					->where(function($q) {
						$q->whereNull('js.edoc_bas')->orWhere('js.edoc_bas', '');
					})
					->whereMonth('js.tanggal_sidang', '=', $now->month)
					->whereYear('js.tanggal_sidang', '=', $now->year);
			});

		if ($jenis === 'gugatan') {
			$paniteraDataCountQuery->leftJoin('perkara as p', function ($join) {
				$join->on('js.perkara_id', '=', 'p.perkara_id')
					->where('p.nomor_perkara', 'LIKE', '%Pdt.G%');
			});
			$paniteraDataCountQuery->selectRaw('COUNT(p.perkara_id) as total_belum_bas_filtered');
		} else if ($jenis === 'permohonan') {
			$paniteraDataCountQuery->leftJoin('perkara as p', function ($join) {
				$join->on('js.perkara_id', '=', 'p.perkara_id')
					->where('p.nomor_perkara', 'LIKE', '%Pdt.P%');
			});
			$paniteraDataCountQuery->selectRaw('COUNT(p.perkara_id) as total_belum_bas_filtered');
		}

		$paniteraDataRaw = $paniteraDataCountQuery
			->where('panitera_pn.aktif', 'Y')
			->groupBy('panitera_pn.id', 'panitera_pn.nama_gelar')
			->get();

		// Ensure we use the correct count property
		$paniteraData = $paniteraDataRaw->map(function ($pan) use ($jenis) {
			if ($jenis) {
				$pan->total_belum_bas = $pan->total_belum_bas_filtered;
			}
			return $pan;
		});

		$data = $query->latest('tanggal_sidang')
			->limit($config['per_page'])
			->offset($offset)
			->get();

		return [
			'jenis' => $jenis,
			'page_name' => "Monitoring BAS " . ucfirst($jenis ?? 'Keseluruhan'),
			'data' => $data,
			'search' => $search,
			'bulan_value' => $now->format('Y-m'),
			'bulan_label' => $now->translatedFormat('F Y'),
			'offset' => $offset,
			'total_data' => $config['total_rows'],
			'total_year' => $total_year,
			'panitera_data' => $paniteraData,
			'panitera_id' => $paniteraId,
			'page' => $page,
			'now' => $now,
			'per_page' => $config['per_page']
		];
	}

	public function index($jenis = null)
	{
		$viewData = $this->_fetch_bas_data($jenis);

		Templ::render("monitoring_bas/index", $viewData)
			->layout("layouts/main_layout", [
				"title" => $viewData['page_name'],
			]);
	}

	public function pagination($jenis = null, $page = 1)
	{
		if ($jenis === 'all') $jenis = null;

		if (!MethodFilter::isHeader("HX-Request")) {
			$suffix = $jenis ? "_{$jenis}" : "";
			redirect("monitoring_bas{$suffix}");
			exit;
		}
		MethodFilter::mustHeader("HX-Request");

		$viewData = $this->_fetch_bas_data($jenis, $page);
		$now = $viewData['now'];
		$paniteraId = $viewData['panitera_id'];

		$paniteraCardsHtml = '';
		foreach ($viewData['panitera_data'] as $panitera) {
			if ($panitera->total_belum_bas > 0 || (isset($paniteraId) && $paniteraId == $panitera->id)) {
				$borderClass = (isset($paniteraId) && $paniteraId == $panitera->id) ? 'border-primary border-2' : '';
				$paniteraCardsHtml .= '
				<div class="col-6 col-md-4 col-lg-3 mb-2 cursor-pointer" style="cursor: pointer;" onclick="document.getElementById(\'filter-panitera\').value = \'' . $panitera->id . '\'; htmx.trigger(\'#filter-panitera\', \'change\');">
					<div class="card shadow-sm h-100 ' . $borderClass . '">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="' . htmlspecialchars($panitera->nama_gelar) . '">' . htmlspecialchars($panitera->nama_gelar) . '</div>
									<h4 class="mb-0 fw-bold text-primary">' . number_format($panitera->total_belum_bas, 0, ',', '.') . '</h4>
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
			Templ::component('monitoring_bas/components/tabel_monitoring', [
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
									<small class="text-muted">Total BAS Belum Upload (Bulan ' . $now->translatedFormat('F Y') . ')</small>
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
									<small class="text-muted">Total BAS Belum Upload (Tahun ' . $now->year . ')</small>
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
			<div class="row mb-3" id="panitera-cards" hx-swap-oob="true">
				' . $paniteraCardsHtml . '
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