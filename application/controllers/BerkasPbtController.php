<?php

use App\Libraries\Templ;
use App\Models\BerkasGugatan;
use App\Models\Perkara;
use Illuminate\Database\Capsule\Manager as DB;

class BerkasPbtController extends APP_Controller
{
  public function index()
  {
    $page_title = "Berkas PBT";

    // Ambil daftar jurusita aktif
    $jurusita = DB::connection("sipp")->table("jurusita")->where("aktif", "Y")->get();

    // Deteksi kolom dokumen upload pada tabel perkara_putusan_pemberitahuan_putusan di SIPP
    $hasDokumen = false;
    $hasFileDokumen = false;
    try {
      $schema = DB::connection("sipp")->getSchemaBuilder();
      $hasDokumen = $schema->hasColumn("perkara_putusan_pemberitahuan_putusan", "dokumen");
      $hasFileDokumen = $schema->hasColumn("perkara_putusan_pemberitahuan_putusan", "file_dokumen");
    } catch (\Throwable $e) {
      // safe fallback
    }

    $filterStatus = isset($_GET['status']) && in_array($_GET['status'], ['belum', 'sudah', 'semua']) ? $_GET['status'] : 'belum';
    $filterTahun = isset($_GET['tahun']) && !empty($_GET['tahun']) ? $_GET['tahun'] : date("Y");
    $filterJurusita = isset($_GET['j']) && !empty($_GET['j']) ? $_GET['j'] : null;
    $filterQuery = isset($_GET['q']) ? trim($_GET['q']) : null;

    // Ambil list perkara_id yang sudah dicatat tanggal_pbt di berkas_gugatan (database lokal)
    $perkaraSudahPbtLokal = BerkasGugatan::whereNotNull("tanggal_pbt")
      ->pluck("perkara_id")
      ->filter()
      ->unique()
      ->toArray();

    $query = Perkara::select(
      "perkara_id",
      "nomor_perkara",
      "tanggal_pendaftaran",
      "jenis_perkara_nama",
      "pihak1_text",
      "pihak2_text",
      "proses_terakhir_text",
      "prodeo"
    )
      ->whereHas("perkara_putusan", function ($q) {
        $q->where("status_putusan_id", 62)->where("putusan_verstek", "Y");
      });

    // Penerapan filter status PBT
    if ($filterStatus === 'belum') {
      // Hanya perkara yang belum BHT
      $query->whereHas("perkara_putusan", function ($q) {
        $q->whereNull("tanggal_bht");
      });

      // Kecualikan jika sudah diinput tanggal PBT pada berkas_gugatan lokal
      if (!empty($perkaraSudahPbtLokal)) {
        $query->whereNotIn("perkara_id", $perkaraSudahPbtLokal);
      }

      // Kecualikan jika Tergugat (pihak 2) SUDAH memiliki tanggal pemberitahuan ATAU relaas dokumen PBT sudah diupload
      $query->whereDoesntHave("pemberitahuan_putusan", function ($q) use ($hasDokumen, $hasFileDokumen) {
        $q->where("pihak", 2)
          ->where(function ($sub) use ($hasDokumen, $hasFileDokumen) {
            $sub->whereNotNull("tanggal_pemberitahuan_putusan");
            if ($hasDokumen) {
              $sub->orWhere(function ($d) {
                $d->whereNotNull("dokumen")->where("dokumen", "!=", "");
              });
            }
            if ($hasFileDokumen) {
              $sub->orWhere(function ($d) {
                $d->whereNotNull("file_dokumen")->where("file_dokumen", "!=", "");
              });
            }
          });
      });
    } elseif ($filterStatus === 'sudah') {
      // Tampilkan perkara yang SUDAH selesai PBT:
      // (PBT Tergugat sudah ada tanggal atau relaas terupload, ATAU sudah diisi di lokal, ATAU sudah BHT)
      $query->where(function ($mainQ) use ($perkaraSudahPbtLokal, $hasDokumen, $hasFileDokumen) {
        $mainQ->whereHas("pemberitahuan_putusan", function ($q) use ($hasDokumen, $hasFileDokumen) {
          $q->where("pihak", 2)
            ->where(function ($sub) use ($hasDokumen, $hasFileDokumen) {
              $sub->whereNotNull("tanggal_pemberitahuan_putusan");
              if ($hasDokumen) {
                $sub->orWhere(function ($d) {
                  $d->whereNotNull("dokumen")->where("dokumen", "!=", "");
                });
              }
              if ($hasFileDokumen) {
                $sub->orWhere(function ($d) {
                  $d->whereNotNull("file_dokumen")->where("file_dokumen", "!=", "");
                });
              }
            });
        });

        if (!empty($perkaraSudahPbtLokal)) {
          $mainQ->orWhereIn("perkara_id", $perkaraSudahPbtLokal);
        }

        $mainQ->orWhereHas("perkara_putusan", function ($q) {
          $q->whereNotNull("tanggal_bht");
        });
      });
    }

    // Filter tahun pendaftaran
    if ($filterTahun !== 'all') {
      $query->whereYear("tanggal_pendaftaran", $filterTahun);
    }

    // Filter pencarian nomor perkara
    if (!empty($filterQuery)) {
      if (strpos($filterQuery, '/') !== false) {
        $query->where("nomor_perkara", "LIKE", "%{$filterQuery}%");
      } else {
        $query->where("nomor_perkara", "LIKE", "%{$filterQuery}/Pdt.G%");
      }
    } else {
      $query->where("nomor_perkara", "LIKE", "%/Pdt.G%");
    }

    // Filter jurusita
    if (!empty($filterJurusita)) {
      $query->whereHas("perkara_jurusita", function ($q) use ($filterJurusita) {
        $q->where("jurusita_id", $filterJurusita);
      });
    }

    $berkas_pbt = $query
      ->with([
        "register_berkas_gugatan",
        "perkara_jurusita",
        "pemberitahuan_putusan",
        "perkara_putusan" => function ($q) {
          $q->select("perkara_id", "tanggal_putusan", "tanggal_bht", "status_putusan_id", "putusan_verstek");
        },
        "perkara_transaksi" => function ($q) {
          $q->where("jenis_biaya_id", 29);
        },
        "perkara_transaksi.detail_pihak"
      ])
      ->orderBy("tanggal_pendaftaran", "desc")
      ->get();

    $breadcrumb = Templ::component("layouts/page_header", [
      'page_name' => $page_title,
      'breadcrumbs' => [
        [
          'url' => '/berkas_gugatan',
          'name' => "Berkas Gugatan"
        ],
        [
          'url' => '/berkas_pbt',
          'name' => $page_title
        ]
      ]
    ]);

    Templ::render("pbt/berkas_pbt_page", [
      'breadcrumb' => $breadcrumb,
      'jurusita' => $jurusita,
      'berkas' => $berkas_pbt,
      'filterStatus' => $filterStatus,
      'filterTahun' => $filterTahun,
      'filterJurusita' => $filterJurusita,
      'filterQuery' => $filterQuery,
      'hasDokumen' => $hasDokumen,
      'hasFileDokumen' => $hasFileDokumen,
    ])->layout("layouts/main_layout", ['title' => $page_title]);
  }
}
