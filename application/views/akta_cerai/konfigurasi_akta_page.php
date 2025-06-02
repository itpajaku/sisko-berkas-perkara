<div class="container-fluid">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "page_name" => "Konfigurasi Akta Cerai",
    "breadcrumbs" => [
      ["name" => "Konfigurasi Akta Cerai", "url" => site_url("akta_cerai/konfigurasi")],
    ],
  ]) ?>
  <div class="card">
    <div class="px-4 py-3 border-bottom">
      <h4 class="card-title mb-0">Sesuaikan konfigurasi Akta Cerai</h4>
    </div>
    <div class="card-body">
      <?= $this->session->flashdata("success_alert") ?>
      <?= $this->session->flashdata("error_alert") ?>
      <div id="result"></div>
      <form
        hx-post="<?= site_url("akta_cerai/konfigurasi") ?>"
        hx-target="#result"
        hx-swap="none">
        <div class="mb-4 row align-items-center">
          <label for="exampleInputText31" class="form-label col-sm-3 col-form-label">Prefix *</label>
          <div class="col-sm-9">
            <div class="input-group border rounded-1">
              <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
                <i class="ti ti-tags fs-6"></i>
              </span>
              <input required name="prefix" type="text" class="form-control border-0 ps-2" id="exampleInputText31" placeholder="Kode atau Huruf Depan Akta Cerai" aria-label="Prefix" aria-describedby="basic-addon1" value="<?= $konfigurasi->prefix ?? '' ?>">
            </div>
          </div>
        </div>
        <div class="mb-4 row align-items-center">
          <label for="exampleInputText32" class="form-label col-sm-3 col-form-label">Nomor Akta Terakhir</label>
          <div class="col-sm-9">
            <div class="input-group border rounded-1">
              <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
                <i class="ti ti-id fs-6"></i>
              </span>
              <input required name="nomor_akta_terakhir" type="text" id="exampleInputText32" class="form-control border-0 ps-2" placeholder="000" value="<?= $konfigurasi->nomor_akta_terakhir ?? null ?>">
            </div>
          </div>
        </div>
        <div class="mb-4 row align-items-center">
          <label for="exampleInputText36" class="form-label col-sm-3 col-form-label">Nomor Seri Terakhir</label>
          <div class="col-sm-9">
            <div class="input-group border rounded-1">
              <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
                <i class="ti ti-id fs-6"></i>
              </span>
              <input required name="nomor_seri_terakhir" type="text" id="exampleInputText36" class="form-control border-0 ps-2" placeholder="000" value="<?= $konfigurasi->nomor_seri_terakhir ?? null ?>">
            </div>
          </div>
        </div>
        <div class="mb-4 row align-items-center">
          <label for="exampleInputText32" class="form-label col-sm-3 col-form-label">Kode Satker</label>
          <div class="col-sm-9">
            <div class="input-group border rounded-1">
              <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
                <i class="ti ti-tags fs-6"></i>
              </span>
              <input disabled type="text" id="exampleInputText32" class="form-control border-0 ps-2" value="<?= $this->sysconf->KodePN ?>">
            </div>
            <small class="text-danger">Kode satker menyesuaikan dengan SIPP</small>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-9">
            <button class="btn btn-primary">
              <i class="ti ti-device-floppy"></i>
              Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>