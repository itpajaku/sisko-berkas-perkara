<?php

use App\Libraries\AuthData;
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? $_ENV["APP_NAME"] ?></title>
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('favicon/apple-touch-icon.png') ?>/">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon/favicon-16x16.png') ?>">
  <link rel="manifest" href="<?= base_url('favicon/site.webmanifest') ?>">

  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles.min.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/datatable/datatable.bs5.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/bs.datepicker.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/addons.css?v=2" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/swal2/swal2.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/libs/material_datepicker/material.datepicker.css" />

  <script src="<?= base_url() ?>assets/js/htmx.min.js"></script>
  <script src="<?= base_url() ?>assets/js/htmx.sse.js"></script>
  <script src="<?= base_url() ?>assets/libs/apexcharts/dist/apexcharts.min.js"></script>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="javascript:void(0)" class="text-nowrap logo-img">
            <img src="<?= base_url($_SERVER['LOGO']) ?>" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <?= $sidebar_menu ?>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <div class="d-flex flex-column align-items-end ">
                <a href="javascript:void(0)" class=" text-dark"><?= AuthData::getUserData()->fullname ?></a>
                <a href="javascript:void(0)"><?= AuthData::getUserData()->name ?></a>
              </div>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="https://api.dicebear.com/9.x/micah/svg?seed=<?= AuthData::getAvatar()->dice_bear_query ?? "w0e9as" ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="<?= base_url("/profile") ?>" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <button
                      hx-post="<?= base_url("/logout") ?>"
                      class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <?= $page_content ?>
    </div>
  </div>
  <script src="<?= base_url() ?>assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/js/sidebarmenu.js"></script>
  <script src="<?= base_url() ?>assets/js/app.min.js"></script>
  <script src="<?= base_url() ?>assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="<?= base_url() ?>assets/libs/datatable/datatable.js"></script>
  <script src="<?= base_url() ?>assets/libs/datatable/datatable.bs5.js"></script>
  <script src="<?= base_url() ?>assets/js/bloodhound.min.js"></script>
  <script src="<?= base_url() ?>assets/js/typeahead.jquery.js"></script>
  <script src="<?= base_url() ?>assets/js/moment.min.js"></script>
  <script src="<?= base_url() ?>assets/js/bs.datepicker.min.js"></script>
  <script src="<?= base_url() ?>assets/libs/material_datepicker/material.datepicker.js"></script>
  <script src="<?= base_url() ?>assets/libs/swal2/swal2.js"></script>
  <script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
  <?php if ($this->uri->segment(1) != "dashboard") { ?>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
  <?php } ?>
  <script src="<?= base_url('assets/js/chart.init.js') ?>"></script>
</body>

</html>