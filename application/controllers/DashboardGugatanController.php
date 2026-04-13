 
  <?php

  use App\Libraries\MethodFilter;
  use App\Libraries\Templ;
  use App\Models\PosisiEkspedisi;
  use Carbon\Carbon;
  use Illuminate\Database\Capsule\Manager as DB;


  class DashboardGugatanController extends APP_Controller
  {
    public function index()
    {
      $page_title = "Dashboard Berkas Gugatan";

      $breadcrumbComp = Templ::component("layouts/page_header", [
        "page_name" => $page_title,
        "breadcrumbs" => [
          [
            "name" => "Dashboard",
            "url" => "/dashboard_gugatan"
          ]
        ]
      ]);

      $posisi_ekspedisi = PosisiEkspedisi::where('status', 1)->get();

      // Query total berkas per hari dalam 1 bulan terakhir
      $startDate = date('Y-m-01');
      $endDate = date('Y-m-t');
      $berkasPerHari = \App\Models\BerkasGugatan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupByRaw('DATE(created_at)')
        ->orderBy('tanggal')
        ->get();

      // Format data untuk chart (label dan data)
      $labels = [];
      $data = [];
      $datePointer = strtotime($startDate);
      $endPointer = strtotime($endDate);
      $berkasMap = [];
      foreach ($berkasPerHari as $row) {
        $berkasMap[$row->tanggal] = $row->total;
      }
      while ($datePointer <= $endPointer) {
        $tgl = date('Y-m-d', $datePointer);
        $labels[] = date('j M', $datePointer);
        $data[] = isset($berkasMap[$tgl]) ? $berkasMap[$tgl] : 0;
        $datePointer = strtotime('+1 day', $datePointer);
      }

      Templ::render("berkas_gugatan/dashboard_gugatan_page", [
        "breadcrumbComp" => $breadcrumbComp,
        "posisi_ekspedisi" => $posisi_ekspedisi,
        "chart_labels" => $labels,
        "chart_data" => $data
      ])->layout("layouts/main_layout", ["title" => $page_title]);
    }

    public function total_berkas()
    {
      MethodFilter::must('get');
      MethodFilter::mustHeader('HX-Request-Component');
      try {
        $startYear = Carbon::now()->startOfYear()->toDateString();
        $startNextYear = Carbon::now()->addYear()->startOfYear()->toDateString();

        $data = DB::table('berkas_gugatan')
          ->whereBetween('created_at', [$startYear, $startNextYear])
          ->selectRaw('
        COUNT(*) AS total_tahun_ini,

        COUNT(CASE 
            WHEN tanggal_terima IS NULL 
            THEN 1 END) AS total_aktif,

        COUNT(CASE 
            WHEN tanggal_terima IS NOT NULL 
             AND tanggal_arsip IS NULL 
            THEN 1 END) AS total_proses,

        COUNT(CASE 
            WHEN tanggal_arsip IS NOT NULL 
            THEN 1 END) AS total_arsip
    ')
          ->first();

        $this->output->set_content_type('text/html')->set_output(
          Templ::component("berkas_gugatan/components/card_total_berkas", [
            "data" => $data
          ])
        );
      } catch (\Throwable $th) {
        $this->output->set_content_type('text/html')->set_output(
          Templ::component("components/error_component", [
            "message" => $th->getMessage()
          ])
        );
      }
    }

    public function total_ekspedisi_berkas()
    {
      MethodFilter::must('get');
      MethodFilter::mustHeader('HX-Request-Component');
      try {
        $subquery = DB::table('berkas_ekspedisi')
          ->select('berkas_id', DB::raw('MAX(id) as last_id'))
          ->groupBy('berkas_id');

        $data = DB::table('posisi_ekspedisi as p')
          ->leftJoinSub($subquery, 'last_exp', function ($join) {
            $join->on(DB::raw('1'), '=', DB::raw('1'));
          })
          ->leftJoin('berkas_ekspedisi as e', function ($join) {
            $join->on('e.id', '=', 'last_exp.last_id')
              ->on('e.save_point', '=', 'p.id');
          })
          ->leftJoin('berkas_gugatan as b', function ($join) {
            $join->on('b.id', '=', 'e.berkas_id')
              ->whereYear('b.created_at', date('Y')); // 🔥 Filter tahun ini
          })
          ->select(
            'p.id',
            'p.posisi',
            'p.keterangan',
            DB::raw('COUNT(b.id) as total_berkas')
          )
          ->groupBy('p.id', 'p.posisi', 'p.keterangan')
          ->orderBy('p.id')
          ->get();
        $this->output->set_content_type('text/html')->set_output(
          Templ::component("berkas_gugatan/components/card_total_ekspedisi_berkas", [
            "data" => $data
          ])
        );
      } catch (\Throwable $th) {
        $this->output->set_content_type('text/html')->set_output(
          Templ::component("components/exceptions_alert", [
            "message" => $th->getMessage()
          ])
        );
      }
    }

    public function chart_berkas_harian()
    {
      MethodFilter::must('get');
      $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
      $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

      $startDate = date('Y-m-01', strtotime("$tahun-$bulan-01"));
      $endDate = date('Y-m-t', strtotime($startDate));
      $berkasPerHari = \App\Models\BerkasGugatan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupByRaw('DATE(created_at)')
        ->orderBy('tanggal')
        ->get();

      $labels = [];
      $data = [];
      $datePointer = strtotime($startDate);
      $endPointer = strtotime($endDate);
      $berkasMap = [];
      $totalBulan = 0;
      foreach ($berkasPerHari as $row) {
        $berkasMap[$row->tanggal] = $row->total;
        $totalBulan += $row->total;
      }
      while ($datePointer <= $endPointer) {
        $tgl = date('Y-m-d', $datePointer);
        $labels[] = date('j M', $datePointer);
        $data[] = isset($berkasMap[$tgl]) ? $berkasMap[$tgl] : 0;
        $datePointer = strtotime('+1 day', $datePointer);
      }

      header('Content-Type: application/json');
      echo json_encode([
        'labels' => $labels,
        'data' => $data,
        'total_bulan' => $totalBulan
      ]);
      exit;
    }

    public function detail_ekspedisi_berkas()
    {
      MethodFilter::must('get');
      MethodFilter::mustHeader('HX-Request-Component');
      try {
        $posisi_id = isset($_GET['posisi_id']) ? (int)$_GET['posisi_id'] : 0;
        if (!$posisi_id) {
          throw new \Exception('ID posisi ekspedisi tidak valid');
        }
        // Ambil data berkas gugatan dengan posisi ekspedisi tertentu
        $berkas = \App\Models\BerkasGugatan::select('id', 'nomor_berkas', 'created_at', 'perkara_id')
          ->whereHas('berkas_ekspedisi', function ($q) use ($posisi_id) {
            $q->where('save_point', $posisi_id);
          })
          ->orderBy('created_at', 'desc')
          ->limit(50)
          ->get();

        echo Templ::component('berkas_gugatan/components/detail_ekspedisi_berkas', [
          'berkas' => $berkas
        ]);
      } catch (\Throwable $th) {
        echo Templ::component('components/alert_exception', [
          'message' => $th->getMessage()
        ]);
      }
    }
  }
