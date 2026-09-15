<?php

use App\Libraries\AuthData;
use App\Libraries\MethodFilter;
use App\Libraries\Templ;
use App\Models\Arsip;
use App\Models\BerkasAkta;
use App\Models\BerkasGugatan;
use App\Models\BerkasPermohonan;
use App\Models\Menu;
use App\Models\MenuSection;
use App\Models\PosisiEkspedisi;
use App\Models\SysGroup;
use App\Models\SysUser;
use Illuminate\Database\Capsule\Manager as DB;

class Dashboard extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    MethodFilter::must('get');

    // Cek koneksi database default & sipp
    $dbAppStatus = false;
    $dbSippStatus = false;

    try {
      DB::connection('default')->getPdo();
      $dbAppStatus = true;
    } catch (\Throwable $e) {
      $dbAppStatus = false;
    }

    try {
      DB::connection('sipp')->getPdo();
      $dbSippStatus = true;
    } catch (\Throwable $e) {
      $dbSippStatus = false;
    }

    // Statistik Pengguna & Grup SIPP
    $totalUsers = 0;
    $totalGroups = 0;
    $groupsList = [];

    if ($dbSippStatus) {
      try {
        $totalUsers = SysUser::count();
        $totalGroups = SysGroup::count();
        $groupsList = SysGroup::orderBy('groupid', 'asc')->limit(10)->get();
      } catch (\Throwable $e) {
      }
    }

    // Statistik Data Aplikasi
    $totalMenu = 0;
    $totalSection = 0;
    $totalPosisiEkspedisi = 0;
    $totalGugatan = 0;
    $gugatanTahunIni = 0;
    $totalPermohonan = 0;
    $permohonanTahunIni = 0;
    $totalAkta = 0;
    $aktaTahunIni = 0;
    $totalArsip = 0;

    if ($dbAppStatus) {
      try {
        $totalMenu = Menu::count();
        $totalSection = MenuSection::count();
        $totalPosisiEkspedisi = PosisiEkspedisi::count();
        $totalGugatan = BerkasGugatan::count();
        $gugatanTahunIni = BerkasGugatan::whereYear('tanggal_pendaftaran', date('Y'))->count();
        $totalPermohonan = BerkasPermohonan::count();
        $permohonanTahunIni = BerkasPermohonan::whereYear('tanggal_pendaftaran', date('Y'))->count();
        $totalAkta = BerkasAkta::count();
        $aktaTahunIni = BerkasAkta::whereYear('tanggal_akta', date('Y'))->count();
      } catch (\Throwable $e) {
      }
    }

    if ($dbSippStatus) {
      try {
        $totalArsip = Arsip::count();
      } catch (\Throwable $e) {
      }
    }

    $data = [
      'user' => AuthData::getUserData(),
      'dbAppStatus' => $dbAppStatus,
      'dbSippStatus' => $dbSippStatus,
      'totalUsers' => $totalUsers,
      'totalGroups' => $totalGroups,
      'groupsList' => $groupsList,
      'totalMenu' => $totalMenu,
      'totalSection' => $totalSection,
      'totalPosisiEkspedisi' => $totalPosisiEkspedisi,
      'totalGugatan' => $totalGugatan,
      'gugatanTahunIni' => $gugatanTahunIni,
      'totalPermohonan' => $totalPermohonan,
      'permohonanTahunIni' => $permohonanTahunIni,
      'totalAkta' => $totalAkta,
      'aktaTahunIni' => $aktaTahunIni,
      'totalArsip' => $totalArsip,
      'phpVersion' => PHP_VERSION,
      'env' => defined('ENVIRONMENT') ? ENVIRONMENT : 'development',
    ];

    Templ::render('admin/dashboard_page', $data)->layout('layouts/main_layout', ['title' => 'Dashboard Administrator']);
  }
}
