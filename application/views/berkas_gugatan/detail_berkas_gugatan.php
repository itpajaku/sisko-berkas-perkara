<style>
  .htmx-indicator {
    opacity: 0;
    visibility: hidden;
  }

  .htmx-request .htmx-indicator,
  .htmx-request.htmx-indicator {
    opacity: 1;
    visibility: visible;
    transition: opacity 200ms ease-in;
  }
</style>

<div class="container-fluid">
  <?= $breadcrumb ?>
  <div class="d-flex">
    <a href="<?= site_url("berkas_gugatan") ?>" class="btn btn-danger mb-3">
      Kembali
      <i class="ti ti-arrow-left ms-1"></i>
    </a>
  </div>
  <div class="card mb-4 shadow-md">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h5 class="mb-1">
            <i class="ti ti-folder-open"></i>
            Detail Berkas Perkara
          </h5>
          <small class="text-muted">Informasi umum perkara</small> |
          <small class="text-muted"><a href="javascript:void(0)" target="_blank" rel="noopener" onclick="window.open( '<?= $sipp_url ?>', 'Snopzer', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0'); return false;">
              <i class="ti ti-send"></i>
              Kunjungi SIPP</a></small>
        </div>
        <div>
          <?php if ($berkas->tanggal_bht) { ?>
            <span class="badge bg-primary">BHT : <?= $berkas->tanggal_bht ?></span>
          <?php } else { ?>
            <span class="badge bg-secondary">Belum BHT</span>
          <?php } ?>
          <a href="<?= site_url("berkas_gugatan/$hash_id/edit") ?>" class="badge bg-warning">
            <i class="ti ti-pencil"></i>
            Ubah</a>
          <a href="javascript:void(0)"
            hx-confirm="Data yang dihapus tidak bisa dikembalikan."
            hx-delete="<?= base_url("berkas_gugatan/$hash_id") ?>"
            class="badge bg-danger">
            <i class=" ti ti-trash"></i>
            Hapus</a>
        </div>
      </div>

      <hr>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <small class="text-muted">Nomor Perkara</small>
            <div class="fw-semibold"><?= $perkara->nomor_perkara ?></div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Jenis Perkara</small>
            <div><?= $perkara->jenis_perkara_nama ?></div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Tanggal Pendaftaran</small>
            <div><?= tanggal_indo($perkara->tanggal_pendaftaran) ?></div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Tanggal Putusan</small>
            <div><?= tanggal_indo($berkas->tanggal_putusan) ?></div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <small class="text-muted">Penggugat</small>
            <div><?= $perkara->pihak1_text ?></div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Tergugat</small>
            <div><?= $perkara->pihak2_text ?></div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Jurusita</small>
            <div>
              <?= $perkara->perkara_penetapan->jurusita_text ?>
            </div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Panitera</small>
            <div>
              <?= $perkara->perkara_penetapan->panitera_pengganti_text ?>
            </div>
          </div>

          <div class="mb-3">
            <small class="text-muted">Majelis Hakim</small>
            <div>
              <?= $perkara->perkara_penetapan->majelis_hakim_text ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h6 class="mb-3">
        <i class="ti ti-file-text me-1"></i>
        Informasi Berkas
      </h6>

      <div class="row">
        <div class="col-md-6">
          <div class="border rounded p-3">
            <small class="text-muted">Status Biaya perkara</small>
            <div class="d-flex">
              <?php if ($perkara->prodeo == 1) { ?>
                <h5 class="mb-0 text-success">
                  Prodeo
                </h5>
              <?php } else { ?>
                <h5 class="mb-0 text-primary">
                  Reguler
                </h5>
              <?php } ?>
              <?php if ($is_efiling) { ?>
                <h5 class="mb-0 text-warning border ms-2 px-2 border-warning rounded">
                  E-Court
                </h5>
              <?php } else { ?>
                <h5 class="mb-0 text-info border ms-2 px-2 border-info rounded">
                  E-Court
                </h5>
              <?php } ?>
            </div>


          </div>
          <div class="border rounded p-3">
            <small class="text-muted">Status Berkas</small>
            <?php if ($berkas->status) { ?>
              <h6 class="mb-0 text-success">
                <i class="ti ti-circle-check me-1"></i>
                Diterima Meja 2
              </h6>
            <?php } else { ?>
              <h6 class="mb-0 text-danger">
                <i class="ti ti-x me-1"></i>
                Belum Diterima Meja 2
              </h6>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="border rounded p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <small class="text-muted">Riwayat pemberitahuan isi putusan</small>
                <div class="fw-semibold">
                  <ol>
                    <?php foreach ($perkara->pemberitahuan_putusan as $pemberitahuan) { ?>
                      <?php
                      if ($pemberitahuan->tanggal_pemberitahuan_putusan) {
                        echo  "<li>Pihak ke $pemberitahuan->pihak. PBT : " . tanggal_indo($pemberitahuan->tanggal_pemberitahuan_putusan, false) . "<br>Keterangan : " . $pemberitahuan->ket_ketemu  . "</li>";
                      }
                      ?>
                    <?php } ?>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">
          <i class="ti ti-truck-delivery me-1"></i>
          Riwayat Ekspedisi Berkas
        </h6>

        <button
          data-bs-toggle="modal"
          data-bs-target="#modalId"
          class="btn btn-sm btn-outline-primary">
          <i class="ti ti-plus me-1"></i>
          Tambah Ekspedisi
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:40px;">#</th>
              <th>Tanggal</th>
              <th>Tujuan</th>
              <th>Oleh</th>
              <th>Penerima</th>
              <th>Status</th>
              <th style="width:90px;">Aksi</th>
            </tr>
          </thead>
          <tbody
            hx-get="<?= base_url("berkas_gugatan/$hash_id/ekspedisi") ?>"
            hx-trigger="load delay:1s, action-success from:body"
            hx-target="this"
            hx-headers='{"HX-Request-Component":"tbody_ekspedisi"}'
            hx-swap="innerHTML">
            <tr>
              <td colspan="7" class="text-center">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modalId"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modalTitleId"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">
          Tambah Ekspedisi Berkas
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <form
        hx-post="<?= base_url("/berkas_gugatan/" . App\Libraries\Hashid::encode($berkas->id) . "/ekspedisi") ?>"
        hx-target="#post-result">
        <input type="hidden" name="berkas_type" value="<?= class_basename($berkas) ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="modal-body">
          <div class="container-fluid">
            <div id="post-result"></div>
            <div class="form-group">
              <label for="select-posisi-ekspedisi">Pilih Penerima Berkas</label>
              <select required name="posisi_ekspedisi" class="form-select" id="select-posisi-ekspedisi">
                <option value="" selected disabled>--- Pilih Disini ---</option>
                <?php foreach ($posisi_berkas as $pe) { ?>
                  <option value="<?= $pe->id ?>"><?= $pe->posisi ?></option>
                <?php }  ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Close
          </button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>