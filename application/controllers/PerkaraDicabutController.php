<?php

use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Hakim;
use App\Models\PerkaraPutusan;
use Carbon\Carbon;

class PerkaraDicabutController extends APP_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->_ensure_menu_exists();
	}

	/**
	 * Memastikan menu Perkara Dicabut terdaftar di database lokal jika belum ada.
	 */
	private function _ensure_menu_exists()
	{
		try {
			$menu = \App\Models\Menu::find(26);
			if (!$menu) {
				\App\Models\Menu::create([
					'id' => 26,
					'title' => 'Perkara Dicabut',
					'section_id' => 8,
					'is_sub' => 0,
					'link' => '/perkara_dicabut',
					'icon' => 'ti ti-file-off',
					'is_active' => 1,
				]);
			}

			if (!\App\Models\AccessMenu::where('group_id', 1)->where('menu_id', 26)->exists()) {
				\App\Models\AccessMenu::create(['group_id' => 1, 'menu_id' => 26]);
			}
			if (!\App\Models\AccessMenu::where('group_id', 430)->where('menu_id', 26)->exists()) {
				\App\Models\AccessMenu::create(['group_id' => 430, 'menu_id' => 26]);
			}

			if (!\App\Models\AccessMenuSection::where('group_id', 1)->where('menu_section_id', 8)->exists()) {
				\App\Models\AccessMenuSection::create(['group_id' => 1, 'menu_section_id' => 8]);
			}
			if (!\App\Models\AccessMenuSection::where('group_id', 430)->where('menu_section_id', 8)->exists()) {
				\App\Models\AccessMenuSection::create(['group_id' => 430, 'menu_section_id' => 8]);
			}
		} catch (\Throwable $e) {
			// Mengabaikan exception jika tabel belum siap
		}
	}

	/**
	 * Helper untuk memetakan hasil mediasi menjadi label, badge color, dan icon.
	 *
	 * @param array $mArray
	 * @param array $statusMediasiNames
	 * @return array
	 */
	private function _format_hasil_mediasi(array $mArray, array $statusMediasiNames = []): array
	{
		$statusId = $mArray['status_mediasi_id'] ?? null;
		$statusNama = ($statusId && isset($statusMediasiNames[$statusId])) ? trim((string) $statusMediasiNames[$statusId]) : null;
		$hasilRaw = trim((string) ($mArray['hasil_mediasi'] ?? ''));
		$keterangan = trim((string) ($mArray['keterangan_mediasi'] ?? ($mArray['keterangan'] ?? '')));
		$alasan = trim((string) ($mArray['alasan_tidak_terlaksana'] ?? ($mArray['alasan_tidak_berhasil'] ?? '')));

		// Mapping kode standar SIPP
		$kodeMap = [
			'Y1' => ['label' => 'Berhasil Dengan Kesepakatan', 'type' => 'success', 'icon' => 'ti ti-heart-handshake'],
			'Y2' => ['label' => 'Berhasil Dengan Pencabutan', 'type' => 'primary', 'icon' => 'ti ti-file-off'],
			'S'  => ['label' => 'Berhasil Sebagian', 'type' => 'info', 'icon' => 'ti ti-scale'],
			'T'  => ['label' => 'Tidak Berhasil', 'type' => 'danger', 'icon' => 'ti ti-x'],
			'D'  => ['label' => 'Tidak Dapat Dilaksanakan', 'type' => 'warning', 'icon' => 'ti ti-alert-circle'],
			'B'  => ['label' => 'Berhasil Damai', 'type' => 'success', 'icon' => 'ti ti-heart-handshake'],
			'TB' => ['label' => 'Tidak Berhasil', 'type' => 'danger', 'icon' => 'ti ti-x'],
			'TD' => ['label' => 'Tidak Dapat Dilaksanakan', 'type' => 'warning', 'icon' => 'ti ti-alert-circle'],
			'BS' => ['label' => 'Berhasil Sebagian', 'type' => 'info', 'icon' => 'ti ti-scale'],
			'P'  => ['label' => 'Dalam Proses', 'type' => 'primary', 'icon' => 'ti ti-clock'],
			'1'  => ['label' => 'Berhasil Dengan Kesepakatan', 'type' => 'success', 'icon' => 'ti ti-heart-handshake'],
			'2'  => ['label' => 'Berhasil Dengan Pencabutan', 'type' => 'primary', 'icon' => 'ti ti-file-off'],
			'3'  => ['label' => 'Berhasil Sebagian', 'type' => 'info', 'icon' => 'ti ti-scale'],
			'4'  => ['label' => 'Tidak Berhasil', 'type' => 'danger', 'icon' => 'ti ti-x'],
			'5'  => ['label' => 'Tidak Dapat Dilaksanakan', 'type' => 'warning', 'icon' => 'ti ti-alert-circle'],
		];

		$upperHasil = strtoupper($hasilRaw);
		if (isset($kodeMap[$upperHasil])) {
			$res = $kodeMap[$upperHasil];
			$res['keterangan'] = $keterangan ?: $alasan;
			return $res;
		}

		// Jika ada nama status dari tabel status_mediasi
		if (!empty($statusNama)) {
			$namaLower = strtolower($statusNama);
			if (strpos($namaLower, 'pencabutan') !== false || strpos($namaLower, 'cabut') !== false) {
				return ['label' => 'Berhasil Dengan Pencabutan', 'type' => 'primary', 'icon' => 'ti ti-file-off', 'keterangan' => $keterangan ?: $alasan];
			} elseif (strpos($namaLower, 'sebagian') !== false) {
				return ['label' => 'Berhasil Sebagian', 'type' => 'info', 'icon' => 'ti ti-scale', 'keterangan' => $keterangan ?: $alasan];
			} elseif (strpos($namaLower, 'tidak berhasil') !== false) {
				return ['label' => 'Tidak Berhasil', 'type' => 'danger', 'icon' => 'ti ti-x', 'keterangan' => $keterangan ?: $alasan];
			} elseif (strpos($namaLower, 'tidak dapat') !== false || strpos($namaLower, 'tidak terlaksana') !== false) {
				return ['label' => 'Tidak Dapat Dilaksanakan', 'type' => 'warning', 'icon' => 'ti ti-alert-circle', 'keterangan' => $keterangan ?: $alasan];
			} elseif (strpos($namaLower, 'kesepakatan') !== false || strpos($namaLower, 'berhasil') !== false) {
				return ['label' => 'Berhasil Dengan Kesepakatan', 'type' => 'success', 'icon' => 'ti ti-heart-handshake', 'keterangan' => $keterangan ?: $alasan];
			}
			return ['label' => $statusNama, 'type' => 'info', 'icon' => 'ti ti-scale', 'keterangan' => $keterangan ?: $alasan];
		}

		// Jika hasil_mediasi berupa teks deskriptif
		if (!empty($hasilRaw)) {
			$lower = strtolower($hasilRaw);
			if (strpos($lower, 'pencabutan') !== false || strpos($lower, 'cabut') !== false) {
				return ['label' => 'Berhasil Dengan Pencabutan', 'type' => 'primary', 'icon' => 'ti ti-file-off', 'keterangan' => $hasilRaw];
			} elseif (strpos($lower, 'sebagian') !== false) {
				return ['label' => 'Berhasil Sebagian', 'type' => 'info', 'icon' => 'ti ti-scale', 'keterangan' => $hasilRaw];
			} elseif (strpos($lower, 'tidak berhasil') !== false) {
				return ['label' => 'Tidak Berhasil', 'type' => 'danger', 'icon' => 'ti ti-x', 'keterangan' => $hasilRaw];
			} elseif (strpos($lower, 'tidak dapat') !== false || strpos($lower, 'tidak terlaksana') !== false) {
				return ['label' => 'Tidak Dapat Dilaksanakan', 'type' => 'warning', 'icon' => 'ti ti-alert-circle', 'keterangan' => $hasilRaw];
			} elseif (strpos($lower, 'kesepakatan') !== false || strpos($lower, 'berhasil') !== false || strpos($lower, 'damai') !== false) {
				return ['label' => 'Berhasil Dengan Kesepakatan', 'type' => 'success', 'icon' => 'ti ti-heart-handshake', 'keterangan' => $hasilRaw];
			}
			return ['label' => $hasilRaw, 'type' => 'info', 'icon' => 'ti ti-scale', 'keterangan' => $keterangan ?: $alasan];
		}

		if (!empty($alasan)) {
			return ['label' => 'Tidak Terlaksana', 'type' => 'warning', 'icon' => 'ti ti-alert-circle', 'keterangan' => $alasan];
		}

		if (!empty($keterangan)) {
			return ['label' => $keterangan, 'type' => 'info', 'icon' => 'ti ti-info-circle', 'keterangan' => $keterangan];
		}

		return ['label' => 'Pernah Mediasi', 'type' => 'secondary', 'icon' => 'ti ti-scale', 'keterangan' => ''];
	}

	/**
	 * Private helper to fetch perkara dicabut data.
	 *
	 * @param int $page
	 * @return array
	 */
	private function _fetch_dicabut_data(int $page = 1)
	{
		$this->load->library('pagination');

		$search = $this->input->get('search', true);
		$filterWaktu = $this->input->get('filter_waktu', true);
		$isAllYearInput = $this->input->get('is_all_year', true);
		$jenisFilter = $this->input->get('jenis', true); // 'all' | 'gugatan' | 'permohonan'
		$hakimFilter = $this->input->get('hakim', true);
		$registerFilter = $this->input->get('register_status', true) ?: 'all'; // 'all' | 'registered' | 'unregistered'

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

		$baseCondition = function ($q) {
			$q->whereIn('perkara_putusan.status_putusan_id', [7, 67])
			  ->orWhereHas('status_putusan', function ($sq) {
				  $sq->where('nama', 'Dicabut');
			  });
		};

		$query = PerkaraPutusan::with([
			'perkara.perkara_penetapan',
			'status_putusan'
		])
			->join('perkara', 'perkara_putusan.perkara_id', '=', 'perkara.perkara_id')
			->where($baseCondition)
			->whereYear('perkara_putusan.tanggal_putusan', $now->year);

		if (!$isAllYear) {
			$query->whereMonth('perkara_putusan.tanggal_putusan', $now->month);
		}

		// Filter jenis perkara (gugatan / permohonan)
		if ($jenisFilter === 'gugatan') {
			$query->where('perkara.nomor_perkara', 'LIKE', '%Pdt.G%');
		} elseif ($jenisFilter === 'permohonan') {
			$query->where('perkara.nomor_perkara', 'LIKE', '%Pdt.P%');
		}

		// Filter status register berkas
		$registeredIds = [];
		if ($registerFilter !== 'all') {
			if ($jenisFilter === 'gugatan') {
				$registeredIds = BerkasGugatan::pluck('perkara_id')->filter()->unique()->toArray();
			} elseif ($jenisFilter === 'permohonan') {
				$registeredIds = BerkasPermohonan::pluck('perkara_id')->filter()->unique()->toArray();
			} else {
				$registeredIds = array_values(array_unique(array_merge(
					BerkasGugatan::pluck('perkara_id')->filter()->toArray(),
					BerkasPermohonan::pluck('perkara_id')->filter()->toArray()
				)));
			}

			if ($registerFilter === 'registered') {
				if (empty($registeredIds)) {
					$query->whereRaw('1 = 0');
				} else {
					$query->whereIn('perkara_putusan.perkara_id', $registeredIds);
				}
			} elseif ($registerFilter === 'unregistered') {
				if (!empty($registeredIds)) {
					$query->whereNotIn('perkara_putusan.perkara_id', $registeredIds);
				}
			}
		}

		// Filter pencarian teks
		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('perkara.nomor_perkara', 'LIKE', "%{$search}%")
				  ->orWhere('perkara.pihak1_text', 'LIKE', "%{$search}%")
				  ->orWhere('perkara.pihak2_text', 'LIKE', "%{$search}%")
				  ->orWhere('perkara.jenis_perkara_nama', 'LIKE', "%{$search}%")
				  ->orWhere('perkara_putusan.amar_putusan', 'LIKE', "%{$search}%")
				  ->orWhereHas('perkara.perkara_penetapan', function ($pq) use ($search) {
					  $pq->where('majelis_hakim_nama', 'LIKE', "%{$search}%")
					     ->orWhere('panitera_pengganti_text', 'LIKE', "%{$search}%");
				  });
			});
		}

		// Filter hakim
		if ($hakimFilter) {
			$query->whereHas('perkara.perkara_penetapan', function ($pq) use ($hakimFilter) {
				$pq->where('majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
			});
		}

		$config = $this->paginationConfig();
		$config['base_url'] = site_url("perkara_dicabut/page");
		$config['total_rows'] = (clone $query)->count();
		$config['reuse_query_string'] = true;

		// Summary: Total Tahun
		$totalYearQuery = PerkaraPutusan::join('perkara', 'perkara_putusan.perkara_id', '=', 'perkara.perkara_id')
			->where($baseCondition)
			->whereYear('perkara_putusan.tanggal_putusan', $now->year);
		if ($hakimFilter) {
			$totalYearQuery->whereHas('perkara.perkara_penetapan', function ($pq) use ($hakimFilter) {
				$pq->where('majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
			});
		}
		$totalTahun = (clone $totalYearQuery)->count();

		// Summary: Total Periode Terpilih
		$totalPeriodeQuery = PerkaraPutusan::join('perkara', 'perkara_putusan.perkara_id', '=', 'perkara.perkara_id')
			->where($baseCondition)
			->whereYear('perkara_putusan.tanggal_putusan', $now->year);
		if (!$isAllYear) {
			$totalPeriodeQuery->whereMonth('perkara_putusan.tanggal_putusan', $now->month);
		}
		if ($hakimFilter) {
			$totalPeriodeQuery->whereHas('perkara.perkara_penetapan', function ($pq) use ($hakimFilter) {
				$pq->where('majelis_hakim_nama', 'LIKE', "%{$hakimFilter}%");
			});
		}
		$totalPeriode = (clone $totalPeriodeQuery)->count();
		$totalGugatanPeriode = (clone $totalPeriodeQuery)->where('perkara.nomor_perkara', 'LIKE', '%Pdt.G%')->count();
		$totalPermohonanPeriode = (clone $totalPeriodeQuery)->where('perkara.nomor_perkara', 'LIKE', '%Pdt.P%')->count();

		$this->pagination->initialize($config);

		if ($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $config['per_page'];

		// Grouping data per Hakim untuk Cards Ringkasan
		$hakimDataQuery = PerkaraPutusan::select('perkara_penetapan.majelis_hakim_nama')
			->selectRaw('COUNT(perkara_putusan.perkara_id) as total_dicabut')
			->join('perkara', 'perkara_putusan.perkara_id', '=', 'perkara.perkara_id')
			->join('perkara_penetapan', 'perkara.perkara_id', '=', 'perkara_penetapan.perkara_id')
			->where($baseCondition)
			->whereYear('perkara_putusan.tanggal_putusan', $now->year)
			->whereNotNull('perkara_penetapan.majelis_hakim_nama')
			->where('perkara_penetapan.majelis_hakim_nama', '!=', '');

		if (!$isAllYear) {
			$hakimDataQuery->whereMonth('perkara_putusan.tanggal_putusan', $now->month);
		}
		if ($jenisFilter === 'gugatan') {
			$hakimDataQuery->where('perkara.nomor_perkara', 'LIKE', '%Pdt.G%');
		} elseif ($jenisFilter === 'permohonan') {
			$hakimDataQuery->where('perkara.nomor_perkara', 'LIKE', '%Pdt.P%');
		}

		$rawHakimList = $hakimDataQuery->groupBy('perkara_penetapan.majelis_hakim_nama')->get();

		// Ekstrak nama hakim ketua dari teks majelis (format: "1. Nama Hakim (Ketua)\n2. ...")
		$groupedHakim = [];
		foreach ($rawHakimList as $h) {
			$majelisRaw = $h->majelis_hakim_nama;
			$lines = preg_split('/\r\n|\r|\n/', trim($majelisRaw));
			$firstLine = $lines[0] ?? $majelisRaw;
			$cleanName = preg_replace('/^\d+[\.\)]\s*/', '', trim($firstLine));
			$cleanName = preg_replace('/\s*\(.*?\)\s*$/', '', $cleanName);
			$cleanName = trim($cleanName);

			if (empty($cleanName)) {
				$cleanName = trim($firstLine);
			}

			if (!isset($groupedHakim[$cleanName])) {
				$groupedHakim[$cleanName] = (object) [
					'nama_gelar' => $cleanName,
					'total_dicabut' => 0,
					'key' => $cleanName,
				];
			}
			$groupedHakim[$cleanName]->total_dicabut += $h->total_dicabut;
		}
		uasort($groupedHakim, function ($a, $b) {
			return $b->total_dicabut <=> $a->total_dicabut;
		});

		$data = $query->select('perkara_putusan.*')
			->orderByDesc('perkara_putusan.tanggal_putusan')
			->limit($config['per_page'])
			->offset($offset)
			->get();

		// Cek keberadaan di tabel register berkas lokal & ambil data mediasi dari SIPP
		$perkaraIds = $data->pluck('perkara_id')->filter()->unique()->toArray();
		$berkasGugatanMap = [];
		$berkasPermohonanMap = [];
		$mediasiMap = [];

		if (!empty($perkaraIds)) {
			$berkasGugatanMap = BerkasGugatan::whereIn('perkara_id', $perkaraIds)
				->get(['id', 'perkara_id', 'status'])
				->keyBy('perkara_id')
				->all();

			$berkasPermohonanMap = BerkasPermohonan::whereIn('perkara_id', $perkaraIds)
				->get(['id', 'hash_id', 'perkara_id', 'status'])
				->keyBy('perkara_id')
				->all();

			// Mengambil data mediasi langsung dari database SIPP
			try {
				$dbSipp = \Illuminate\Database\Capsule\Manager::connection('sipp');
				$statusMediasiNames = [];
				try {
					$statusMediasiNames = $dbSipp->table('status_mediasi')->pluck('nama', 'id')->toArray();
				} catch (\Throwable $e) {}

				$rawMediasi = $dbSipp->table('perkara_mediasi')
					->whereIn('perkara_id', $perkaraIds)
					->orderByDesc('mediasi_id')
					->get();

				foreach ($rawMediasi as $m) {
					$mArray = (array) $m;
					$pId = $mArray['perkara_id'] ?? null;
					if ($pId && !isset($mediasiMap[$pId])) {
						$mediasiMap[$pId] = $this->_format_hasil_mediasi($mArray, $statusMediasiNames);
					}
				}
			} catch (\Throwable $e) {
				// Abaikan jika query mediasi gagal
			}
		}

		$bulanLabel = $isAllYear ? "Tahun {$yearValue}" : $now->translatedFormat('F Y');

		return [
			'page_name' => "Perkara Dicabut",
			'data' => $data,
			'berkas_gugatan' => $berkasGugatanMap,
			'berkas_permohonan' => $berkasPermohonanMap,
			'mediasi_map' => $mediasiMap,
			'register_status' => $registerFilter,
			'search' => $search,
			'bulan_value' => $now->format('Y-m'),
			'bulan_label' => $bulanLabel,
			'is_all_year' => $isAllYear,
			'year_value' => $yearValue,
			'offset' => $offset,
			'total_data' => $config['total_rows'],
			'total_tahun' => $totalTahun,
			'total_periode' => $totalPeriode,
			'total_gugatan_periode' => $totalGugatanPeriode,
			'total_permohonan_periode' => $totalPermohonanPeriode,
			'hakim_data' => $groupedHakim,
			'hakim_filter' => $hakimFilter,
			'jenis_filter' => $jenisFilter ?: 'all',
			'page' => $page,
			'now' => $now,
			'per_page' => $config['per_page'],
		];
	}

	public function index()
	{
		$viewData = $this->_fetch_dicabut_data();

		Templ::render("perkara_dicabut/index", $viewData)
			->layout("layouts/main_layout", [
				"title" => $viewData['page_name'],
			]);
	}

	public function pagination($page = 1)
	{
		if (!MethodFilter::isHeader("HX-Request")) {
			redirect("perkara_dicabut");
			exit;
		}
		MethodFilter::mustHeader("HX-Request");

		$viewData = $this->_fetch_dicabut_data((int) $page);
		$now = $viewData['now'];
		$hakimFilter = $viewData['hakim_filter'];

		$hakimCardsHtml = '';
		foreach ($viewData['hakim_data'] as $hakim) {
			if ($hakim->total_dicabut > 0 || ($hakimFilter && stripos($hakim->nama_gelar, $hakimFilter) !== false)) {
				$isActive = ($hakimFilter && (stripos($hakim->nama_gelar, $hakimFilter) !== false || $hakimFilter == $hakim->key));
				$borderClass = $isActive ? 'border-primary border-2 shadow' : '';
				$hakimCardsHtml .= '
				<div class="col-6 col-md-4 col-lg-3 mb-2" style="cursor: pointer;" onclick="toggleHakimFilter(\'' . htmlspecialchars($hakim->key) . '\')">
					<div class="card shadow-sm h-100 ' . $borderClass . '">
						<div class="card-body p-2 px-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="overflow-hidden pe-2">
									<div class="text-muted text-truncate mb-1" style="font-size: 0.75rem;" title="' . htmlspecialchars($hakim->nama_gelar) . '">' . htmlspecialchars($hakim->nama_gelar) . '</div>
									<h4 class="mb-0 fw-bold text-primary">' . number_format($hakim->total_dicabut, 0, ',', '.') . ' <span class="text-muted fs-2 fw-normal">perkara</span></h4>
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

		echo Templ::component("perkara_dicabut/components/tabel_perkara_dicabut", $viewData);

		// OOB Swaps untuk update summary, cards, dan label
		echo (
			'<div class="row mb-3" id="summary-cards" hx-swap-oob="true">
				<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
					<div class="card shadow-sm h-100 border-danger border-start border-3">
						<div class="card-body p-3">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Dicabut Periode (' . htmlspecialchars($viewData['bulan_label']) . ')</small>
									<h3 class="mb-0 fw-bold text-danger">' . number_format($viewData['total_periode'], 0, ',', '.') . '</h3>
								</div>
								<div class="text-danger fs-1 p-2">
									<i class="ti ti-file-off"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
					<div class="card shadow-sm h-100 border-primary border-start border-3">
						<div class="card-body p-3">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Gugatan Dicabut (Periode)</small>
									<h3 class="mb-0 fw-bold text-primary">' . number_format($viewData['total_gugatan_periode'], 0, ',', '.') . '</h3>
								</div>
								<div class="text-primary fs-1 p-2">
									<i class="ti ti-scale"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
					<div class="card shadow-sm h-100 border-info border-start border-3">
						<div class="card-body p-3">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Permohonan Dicabut (Periode)</small>
									<h3 class="mb-0 fw-bold text-info">' . number_format($viewData['total_permohonan_periode'], 0, ',', '.') . '</h3>
								</div>
								<div class="text-info fs-1 p-2">
									<i class="ti ti-file-description"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xl-3 mb-3 mb-xl-0">
					<div class="card shadow-sm h-100 border-warning border-start border-3">
						<div class="card-body p-3">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<small class="text-muted">Total Dicabut Tahun ' . htmlspecialchars($viewData['year_value']) . '</small>
									<h3 class="mb-0 fw-bold text-warning">' . number_format($viewData['total_tahun'], 0, ',', '.') . '</h3>
								</div>
								<div class="text-warning fs-1 p-2">
									<i class="ti ti-calendar-stats"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>' .
			'<div class="row mb-3" id="hakim-cards" hx-swap-oob="true">
				' . $hakimCardsHtml . '
			</div>' .
			'<div id="periode-label" hx-swap-oob="true">
				<small class="text-muted">Periode: ' . htmlspecialchars($viewData['bulan_label']) . '</small>
			</div>'
		);
	}

	private function paginationConfig(): array
	{
		return [
			'per_page' => 20,
			'uri_segment' => 3,
			'use_page_numbers' => true,
			'full_tag_open' => '<nav><ul class="pagination justify-content-center">',
			'full_tag_close' => '</ul></nav>',
			'attributes' => ['class' => 'page-link'],
			'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close' => '</span></li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
		];
	}
}
