<?php

use App\Libraries\Sysconf;
?>

<style>
  .datepicker {
    padding: 8px !important;
  }
</style>
<div class="container-lg">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "page_name" => "Laporan Berkas Gugatan",
    "breadcrumbs" => [
      ["name" => "Berkas Gugatan", "url" => "/berkas_gugatan/register"],
      ["name" => "Laporan Berkas Gugatan", "url" => "/berkas_gugatan/laporan"]
    ]
  ]) ?>
  <div class="card border shadow">
    <div class="card-header">
      <div class="card-title">Sesuaikan Laporan Anda</div>
    </div>
    <div class="card-body">
      <form
        action="<?= base_url("/berkas_gugatan/laporan") ?>"
        method="post"
        target="_blank">
        <div class="row g-3 d-flex">
          <div class="col-md-3">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input
              required
              type="text"
              class="form-control datepicker"
              id="tanggal_awal"
              name="tanggal_awal"
              value="<?= date("Y-m-d", strtotime("-1 month")) ?>">

          </div>
          <div class="col-md-3">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input
              required
              type="text" class="form-control datepicker" id="tanggal_akhir" name="tanggal_akhir" value="<?= date("Y-m-d") ?>">
          </div>
          <div class="col-md-3">
            <label for="berdasarkan" class="form-label">Berdasarkan</label>
            <select class="form-select" id="berdasarkan" name="berdasarkan">
              <option value="<?= $this->encryption->encrypt("created_at") ?>">Tanggal Dibuat</option>
              <option value="<?= $this->encryption->encrypt("tanggal_bht") ?>">Tanggal BHT</option>
              <option value="<?= $this->encryption->encrypt("tanggal_pbt") ?>">Tanggal PBT</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="penandatangan" class="form-label">Penandatangan</label>
            <select required class="form-select" id="penandatangan" name="penandatangan">
              <option
                value="<?= Sysconf::getVar()->PanSekNama ?>">
                Panitera : <?= Sysconf::getVar()->PanSekNama ?>
              </option>
              <option value="<?= Sysconf::getVar()->PlhPanitera ?>">
                PLH Panitera : <?= Sysconf::getVar()->PlhPanitera ?>
              </option>
            </select>
          </div>
        </div>
        <div class="text-center">
          <button class="btn btn-primary mt-3" type="submit">
            <i class="ti ti-table-export"></i>
            Generate
          </button>
        </div>
      </form>
      <?= $this->session->flashdata("success_alert") ?>
      <?= $this->session->flashdata("error_alert") ?>
      <div id="form-result"></div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    $(".datepicker").datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      todayHighlight: true,
    });
  })
</script>