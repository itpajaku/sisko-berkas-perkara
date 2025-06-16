<div class="container-xl">
  <?= App\Libraries\Templ::component('layouts/page_header', [
    'page_name' => 'Sinkron Berkas',
    'breadcrumbs' => [
      [
        'url' => '/sinkron',
        'name' => 'Sinkron'
      ],
      [
        'url' => '/sinkron/akta',
        'name' => 'Sinkron Akta'
      ],
    ]
  ]) ?>
  <div class="card">
    <div class="card-header text-bg-danger">
      <h5 class="mb-0 text-white">Aksi Khusus Admin</h5>
    </div>
    <form
      hx-post="<?= base_url('sinkron/akta') ?>"
      hx-target="#sinkron-result"
      hx-confirm="Aksi ini memerlukan resource memory dan waktu yang cukup. Pastikan anda tidak meninggalkan halaman ini."
      class="form-horizontal">
      <div class="form-body">
        <div class="card-body">
          <h5 class="card-title mb-0">Form Sinkron</h5>
        </div>
        <hr class="m-0" />
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div
                class="alert alert-danger"
                role="alert">
                <h5 class="alert-heading">
                  <i class="ti ti-alert-triangle"></i>
                  Perhatian
                </h5>
                <!-- <hr /> -->
                <p class="mb-0">Sinkron berkas akan <strong> menghapus semua data </strong> berkas yang ada dan mengunguh data berkas dari sipp berdasarkan <strong> perkara yang sudah putus </strong>. Pastikan anda mengetahui resiko yang terjadi.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label for="select-tahun" class="form-label text-end col-md-4">Tahun Tujuan:</label>
                <div class="col-md-4">
                  <select name="tahun" id="select-tahun" class="form-select form-input-sm">
                    <?php
                    $years = (function () {
                      $years = [];
                      $curYear = date('Y');
                      for ($i = $curYear - 3; $i <= $curYear; $i++) {
                        array_unshift($years, $i);
                      }
                      return $years;
                    })();

                    foreach ($years as $year) { ?>
                      <option><?= $year ?></option>
                    <?php } ?>
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label for="select-opsi-import" class="form-label text-end col-md-4">Option:</label>
                <div class="col-md-6">
                  <select name="opsi" id="select-opsi-import" class="form-select">
                    <option value="1">Hanya Import yang sudah terbit</option>
                    <option value="2">Import semua akta walau belum terbit</option>
                  </select>
                </div>
              </div>
            </div>
            <!--/span-->
          </div>
          <div class="text-center">
            <button
              type="submit" class="btn btn-primary mt-3">
              <i class="ti ti-cloud-download"></i>
              Import
            </button>
            <button
              type="button"
              class="btn btn-danger mt-3"
              onclick="$('#sinkron-result').html('Aborted')">
              <i class="ti ti-x"></i>
              Abort Log
            </button>
          </div>
        </div>
        <hr class="m-0" />
        <div class="card-body" id="sinkron-result">

        </div>
      </div>
    </form>
  </div>
</div>