<?php

use App\Libraries\AuthData;
use App\Libraries\Templ;
?>
<div class="container-fluid">
  <?= Templ::component("layouts/page_header", [
    "page_name" => "Dashboard",
    "breadcrumbs" => [
      [
        'url' => 'meja_3/dashboard',
        'name' => AuthData::getUserData()->name
      ],
    ]
  ]) ?>
  <?= Templ::component("meja_3/info_persentase", $infolist) ?>
  <div class="row">
    <div class="col-lg-8 d-flex align-items-stretch">
      <?= Templ::component("components/berkas_bar") ?>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch flex-column">
      <!-- Yearly Breakup -->
      <?= Templ::component("components/akta_belum_ambil") ?>
      <!-- Monthly Earnings -->
      <div class="card w-100">
        <div class="card-body">
          <div class="row align-items-start">
            <div class="col-8">
              <h4 class="card-title fw-semibold">
                Putus Hari Ini
              </h4>
              <h4 class="fw-semibold text-success"><?= $putus_hari_ini->count() ?></h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-arrow-down-right text-danger"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0"><?= $berkas_gugatan_masuk_putus->count() + $berkas_permohonan_masuk_putus->count()  ?></p>
                <p class="fs-3 mb-0">Sudah Diregister</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white text-bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-hammer fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="earning"></div>
      </div>
    </div>
    <hr>
    <div class="text-center">Featured Soon</div>
    <div class="col-lg-4 d-flex align-items-stretch">
      <?= Templ::component("components/arsip_bar") ?>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch flex-column">
      <div class="row">
        <!-- Customers -->
        <div class="col-sm-6 d-flex align-items-stretch">
          <div class="card w-100">
            <div class="card-body pb-0 mb-xxl-4 pb-1">
              <p class="mb-1 fs-3">Customers</p>
              <h4 class="fw-semibold fs-7">36,358</h4>
              <div class="d-flex align-items-center mb-3">
                <span class="me-2 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-arrow-down-right text-danger"></i>
                </span>
                <p class="text-dark fs-3 mb-0">+9%</p>
              </div>
            </div>
            <div id="customers"></div>
          </div>
        </div>
        <!-- Projects -->
        <div class="col-sm-6 d-flex align-items-stretch">
          <div class="card w-100">
            <div class="card-body">
              <p class="mb-1 fs-3">Projects</p>
              <h4 class="fw-semibold fs-7">78,298</h4>
              <div class="d-flex align-items-center mb-3">
                <span class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-arrow-up-left text-success"></i>
                </span>
                <p class="text-dark fs-3 mb-0">+9%</p>
              </div>
              <div id="projects"></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Comming Soon -->
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center mb-7 pb-2">
            <div class="me-3 pe-1">
              <img src="../assets/images/profile/user-2.jpg" class="shadow-warning rounded-2" alt="modernize-img" width="72" height="72" />
            </div>
            <div>
              <h5 class="fw-semibold fs-5 mb-2">
                Super awesome, Vue coming soon!
              </h5>
              <p class="fs-3 mb-0">22 March, 2024</p>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <ul class="hstack mb-0">
              <li class="ms-n8">
                <a href="javascript:void(0)" class="me-1">
                  <img src="../assets/images/profile/user-2.jpg" class="rounded-circle border border-2 border-white" width="44" height="44" alt="modernize-img" />
                </a>
              </li>
              <li class="ms-n8">
                <a href="javascript:void(0)" class="me-1">
                  <img src="../assets/images/profile/user-3.jpg" class="rounded-circle border border-2 border-white" width="44" height="44" alt="modernize-img" />
                </a>
              </li>
              <li class="ms-n8">
                <a href="javascript:void(0)" class="me-1">
                  <img src="../assets/images/profile/user-4.jpg" class="rounded-circle border border-2 border-white" width="44" height="44" alt="modernize-img" />
                </a>
              </li>
              <li class="ms-n8">
                <a href="javascript:void(0)" class="me-1">
                  <img src="../assets/images/profile/user-5.jpg" class="rounded-circle border border-2 border-white" width="44" height="44" alt="modernize-img" />
                </a>
              </li>
            </ul>
            <a href="javascript:void(0)" class="text-bg-light rounded py-1 px-8 d-flex align-items-center text-decoration-none">
              <i class="ti ti-message-2 fs-6 text-primary"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch">
      <div class="card text-bg-primary border-0 w-100">
        <div class="card-body pb-0">
          <h4 class="fw-semibold mb-1 text-white card-title">
            Best Selling Products
          </h4>
          <p class="fs-3 mb-3 text-white">Overview 2024</p>
          <div class="text-center mt-3">
            <img src="../assets/images/backgrounds/piggy.png" class="img-fluid" alt="modernize-img" />
          </div>
        </div>
        <div class="card mx-2 mb-2 mt-n2">
          <div class="card-body">
            <div class="mb-7 pb-1">
              <div class="d-flex justify-content-between align-items-center mb-6">
                <div>
                  <h6 class="mb-1 fs-4 fw-semibold">MaterialPro</h6>
                  <p class="fs-3 mb-0">$23,568</p>
                </div>
                <div>
                  <span class="badge bg-primary-subtle text-primary fw-semibold fs-3">55%</span>
                </div>
              </div>
              <div class="progress bg-primary-subtle h-4">
                <div class="progress-bar w-50" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div>
              <div class="d-flex justify-content-between align-items-center mb-6">
                <div>
                  <h6 class="mb-1 fs-4 fw-semibold">Flexy Admin</h6>
                  <p class="fs-3 mb-0">$23,568</p>
                </div>
                <div>
                  <span class="badge bg-secondary-subtle text-secondary fw-bold fs-3">20%</span>
                </div>
              </div>
              <div class="progress bg-secondary-subtle h-4">
                <div class="progress-bar text-bg-secondary w-25" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body">
          <h4 class="card-title fw-semibold">Weekly Stats</h4>
          <p class="card-subtitle">Average sales</p>
          <div id="stats" class="my-4"></div>
          <div class="position-relative">
            <div class="d-flex align-items-center justify-content-between mb-7">
              <div class="d-flex">
                <div class="p-6 bg-primary-subtle rounded me-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-grid-dots text-primary fs-6"></i>
                </div>
                <div>
                  <h6 class="mb-1 fs-4 fw-semibold">Top Sales</h6>
                  <p class="fs-3 mb-0">Johnathan Doe</p>
                </div>
              </div>
              <div class="bg-primary-subtle badge">
                <p class="fs-3 text-primary fw-semibold mb-0">+68</p>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-7">
              <div class="d-flex">
                <div class="p-6 bg-success-subtle rounded me-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-grid-dots text-success fs-6"></i>
                </div>
                <div>
                  <h6 class="mb-1 fs-4 fw-semibold">Best Seller</h6>
                  <p class="fs-3 mb-0">MaterialPro Admin</p>
                </div>
              </div>
              <div class="bg-success-subtle badge">
                <p class="fs-3 text-success fw-semibold mb-0">+68</p>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex">
                <div class="p-6 bg-danger-subtle rounded me-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-grid-dots text-danger fs-6"></i>
                </div>
                <div>
                  <h6 class="mb-1 fs-4 fw-semibold">
                    Most Commented
                  </h6>
                  <p class="fs-3 mb-0">Ample Admin</p>
                </div>
              </div>
              <div class="bg-danger-subtle badge">
                <p class="fs-3 text-danger fw-semibold mb-0">+68</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-7">
            <div class="mb-3 mb-sm-0">
              <h4 class="card-title fw-semibold">Top Performers</h4>
              <p class="card-subtitle">Best Employees</p>
            </div>
            <div>
              <select class="form-select">
                <option value="1">March 2024</option>
                <option value="2">April 2024</option>
                <option value="3">May 2024</option>
                <option value="4">June 2024</option>
              </select>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle text-nowrap mb-0">
              <thead>
                <tr class="text-muted fw-semibold">
                  <th scope="col" class="ps-0">Assigned</th>
                  <th scope="col">Project</th>
                  <th scope="col">Priority</th>
                  <th scope="col">Budget</th>
                </tr>
              </thead>
              <tbody class="border-top">
                <tr>
                  <td class="ps-0">
                    <div class="d-flex align-items-center">
                      <div class="me-2 pe-1">
                        <img src="../assets/images/profile/user-3.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1">Sunil Joshi</h6>
                        <p class="fs-2 mb-0 text-muted">
                          Web Designer
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="mb-0 fs-3">Elite Admin</p>
                  </td>
                  <td>
                    <span class="badge fw-semibold py-1 w-85 bg-primary-subtle text-primary">Low</span>
                  </td>
                  <td>
                    <p class="fs-3 text-dark mb-0">$3.9K</p>
                  </td>
                </tr>
                <tr>
                  <td class="ps-0">
                    <div class="d-flex align-items-center">
                      <div class="me-2 pe-1">
                        <img src="../assets/images/profile/user-5.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1">John Deo</h6>
                        <p class="fs-2 mb-0 text-muted">
                          Web Developer
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="mb-0 fs-3">Flexy Admin</p>
                  </td>
                  <td>
                    <span class="badge fw-semibold py-1 w-85 bg-warning-subtle text-warning">Medium</span>
                  </td>
                  <td>
                    <p class="fs-3 text-dark mb-0">$24.5K</p>
                  </td>
                </tr>
                <tr>
                  <td class="ps-0">
                    <div class="d-flex align-items-center">
                      <div class="me-2 pe-1">
                        <img src="../assets/images/profile/user-6.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1">Nirav Joshi</h6>
                        <p class="fs-2 mb-0 text-muted">
                          Web Manager
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="mb-0 fs-3">Material Pro</p>
                  </td>
                  <td>
                    <span class="badge fw-semibold py-1 w-85 bg-info-subtle text-info">High</span>
                  </td>
                  <td>
                    <p class="fs-3 text-dark mb-0">$12.8K</p>
                  </td>
                </tr>
                <tr>
                  <td class="ps-0">
                    <div class="d-flex align-items-center">
                      <div class="me-2 pe-1">
                        <img src="../assets/images/profile/user-7.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1">Yuvraj Sheth</h6>
                        <p class="fs-2 mb-0 text-muted">
                          Project Manager
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="mb-0 fs-3">Xtreme Admin</p>
                  </td>
                  <td>
                    <span class="badge fw-semibold py-1 w-85 bg-success-subtle text-success">Low</span>
                  </td>
                  <td>
                    <p class="fs-3 text-dark mb-0">$4.8K</p>
                  </td>
                </tr>
                <tr>
                  <td class="border-0 ps-0">
                    <div class="d-flex align-items-center">
                      <div class="me-2 pe-1">
                        <img src="../assets/images/profile/user-8.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
                      </div>
                      <div>
                        <h6 class="fw-semibold mb-1">Micheal Doe</h6>
                        <p class="fs-2 mb-0 text-muted">
                          Content Writer
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="border-0">
                    <p class="mb-0 fs-3">Helping Hands WP Theme</p>
                  </td>
                  <td class="border-0">
                    <span class="badge fw-semibold py-1 w-85 bg-danger-subtle text-danger">High</span>
                  </td>
                  <td class="border-0">
                    <p class="fs-3 text-dark mb-0">$9.3K</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>