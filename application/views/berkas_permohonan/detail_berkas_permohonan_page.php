<div class="container-fluid">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Berkas Permohonan", "url" => "/berkas_permohonan"],
      ["name" => "Detail Berkas $berkas->nomor_perkara", "url" => "/berkas_permohonan/$berkas->hash_id"]
    ],
    "page_name" => "Berkas Gugatan",
  ], true) ?>
  <div class="card shadow border widget-card bsb-timeline-8">
    <div class="card-header text-bg-primary">
      <h5 class="mb-0 text-white">Detail Berkas (view only)</h5>
    </div>
    <div class="form-horizontal">
      <div class="form-body">
        <div class="card-body">
          <h5 class="card-title mb-0">Info Berkas</h5>
        </div>
        <hr class="m-0" />
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Nomor Perkara :</label>
                <div class="col-md-8">
                  <p><?= $berkas->nomor_perkara ?></p>
                </div>
              </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Majelis :</label>
                <div class="col-md-8">
                  <?= $berkas->majelis_hakim ?>
                </div>
              </div>
            </div>
            <!--/span-->
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Jenis perkara :</label>
                <div class="col-md-8">
                  <p><?= $berkas->jenis_perkara ?></p>
                </div>
              </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Panitera :</label>
                <div class="col-md-8">
                  <p><?= $berkas->panitera ?></p>
                </div>
              </div>
            </div>
            <!--/span-->
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Tanggal Daftar :</label>
                <div class="col-md-8">
                  <p>
                    <?= tanggal_indo($berkas->tanggal_pendaftaran)  ?>
                  </p>
                </div>
              </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Jurusita :</label>
                <div class="col-md-8">
                  <p>
                    <?= $berkas->jurusita ?>
                  </p>
                </div>
              </div>
            </div>
            <!--/span-->
          </div>
          <!--/row-->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Para Pihak :</label>
                <div class="col-md-8">
                  <?= $berkas->para_pihak  ?>
                </div>
              </div>
            </div>
          </div>

        </div>
        <hr class="m-0" />
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Tanggal Putusan :</label>
                <div class="col-md-8">
                  <p>
                    <?= tanggal_indo($berkas->tanggal_putusan)  ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Arsip Berkas :</label>
                <div class="col-md-8">
                  <p>
                    <?= $berkas->status ? "Diarsipkan" : "Belum Diarsipkan" ?> |
                    <a href="javascript:void(0)"
                      data-bs-toggle="popover"
                      title="Data register ini akan di sinkronkand dengan arsip di SIPP">
                      <i class="ti ti-link"></i>
                      Sinkronkan arsip
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Tanggal Diterima :</label>
                <div class="col-md-9 text-start">
                  <?= tanggal_indo($berkas->tanggal_diterima) ?>
                </div>
              </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Tanggal Arsip :</label>
                <div class="col-md-9">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
            </div>
            <!--/span-->
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-end col-md-4">Keterangan :</label>
                <div class="col-md-8"> <?= $berkas->keterangan ?> </div>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex mx-5 mb-4 justify-content-between">
          <div class="d-flex">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-edit fs-5"></i>
              Edit
            </button>
            <a href="<?= base_url("/berkas_permohonan/register") ?>" type="button" class="btn bg-warning-subtle text-danger ms-6">
              <i class="ti ti-arrow-left"></i>
              Kembali
            </a>
          </div>
          <button type="submit" class="btn btn-danger">
            <i class="ti ti-trash fs-5"></i>
            Hapus
          </button>
        </div>
        <hr class="m-0" />
        <div class="card-body py-4">
          <h5 class="card-title widget-card-title mb-3">Ekspedisi Berkas Nomor <?= $berkas->nomor_perkara ?></h5>
          <ul class="timeline">
            <?php foreach ($berkas->berkas_ekspedisi as $ekspedisi) { ?>
              <li class="timeline-item">
                <div class="timeline-body">
                  <div class="timeline-meta">
                    <span><?= $ekspedisi->save_time->diffForHumans() . " oleh : " . $ekspedisi->created_by ?></span>
                  </div>
                  <div class="timeline-content timeline-indicator">
                    <h5 class="mb-1">Diterima oleh : <?= $ekspedisi->posisi_ekspedisi->posisi ?>.</h5>
                    <h6 class="text-primary"> <?= $ekspedisi->posisi_ekspedisi->keterangan ?></h6>
                    <span class="text-secondary fs-7"> <?= $ekspedisi->save_time->format("d F Y") ?> |
                      <a
                        class="text-danger"
                        href="javascript:void(0)"
                        hx-delete="<?= base_url("/berkas_gugatan/" . $this->hash->encode($berkas->id) . "/ekspedisi") ?>"
                        hx-confirm="Data yang dihapus tidak bisa dikembalikan."
                        hx-vals='<?= json_encode([
                                    "save_point" => $ekspedisi->save_point,
                                    "save_time" => $ekspedisi->save_time,
                                    "berkas_type" => class_basename($berkas)
                                  ]) ?>'>
                        <i class="ti ti-trash"></i>
                      </a>
                    </span>
                  </div>
                </div>
              </li>
            <?php } ?>
          </ul>
          <button
            data-bs-toggle="modal"
            data-bs-target="#modalId"
            class="btn btn-success ms-2">
            <i class="ti ti-plus"></i>
            Tambah kspedisi
          </button>
        </div>
      </div>
    </div>
  </div>
</div>