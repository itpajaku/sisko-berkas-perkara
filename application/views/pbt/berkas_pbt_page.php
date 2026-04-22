<div class="container-fluid">
  <?= $breadcrumb ?>
  <div class="d-flex mb-3 justify-content-between align-items-center">
    <button type="button" class="btn btn-outline-primary">
      Total Berkas: <span class="badge bg-primary"><?= count($berkas) ?></span>
    </button>
    <form action="" class="d-flex gap-2">
      <select name="j" class="form-select">
        <option value="" selected disabled>Semua Jurusita</option>
        <?php foreach ($jurusita as $j) : ?>
          <option value="<?= $j->id ?>" <?= (isset($_GET['j']) && $_GET['j'] == $j->id) ? "selected" : "" ?>><?= $j->nama ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" class="form-control" placeholder="Cari Nomor Perkara..." name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
      <button type="submit" class="btn btn-primary d-flex align-items-center">
        <i class="ti ti-search me-1"></i>
        Cari
      </button>
      <?php if (isset($_GET['q'])) { ?>
        <a type="button" class="btn btn-danger d-flex align-items-center" href="<?= base_url('berkas_pbt') ?>">
          <i class="ti ti-rotate me-1"></i>
          Reset
        </a>
      <?php } ?>
    </form>
  </div>
  <?php foreach ($berkas as $b) : ?>
    <div class="card border-danger card-darker-hover">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="mb-1">
              <i class="ti ti-mail-off me-1"></i>
              Surat Pemberitahuan Putusan
            </h6>
            <small class="text-muted">
              Nomor: <?= $b->nomor_perkara ?>
            </small>
          </div>

          <div class="d-flex gap-2">
            <?php if ($b->prodeo == 1) { ?>
              <span class="badge bg-info">
                <i class="ti ti-user-check me-1"></i>
                Prodeo
              </span>
            <?php } ?>
            <span class="badge bg-danger">
              <i class="ti ti-alert-circle me-1"></i>
              Belum Dikirim
            </span>
            <span
              class="badge bg-success"
              style="cursor: pointer;"
              onclick="window.open( '<?= sipp_url('perkara_detil_agama/' . base64_encode(App\Libraries\AccessLegacyEn::encode($b->perkara_id))) ?>', 'Snopzer', 'left=20,top=20,width=1200,height=800,toolbar=1,resizable=0'); return false;">
              <i class="ti ti-send me-1"></i>
              Link ke SIPP
            </span>
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md">
            <small class="text-muted">Jenis Surat</small>
            <div>Pemberitahuan Putusan</div>
          </div>

          <div class="col-md">
            <small class="text-muted">Tanggal Putusan</small>
            <div><?= tanggal_indo($b->perkara_putusan->tanggal_putusan) ?></div>
          </div>

          <div class="col-md">
            <small class="text-muted">Jenis Perkara</small>
            <div class="fw-semibold"><?= $b->jenis_perkara_nama ?></div>
          </div>

          <div class="col-md">
            <small class="text-muted">Tahapan</small>
            <div><?= $b->proses_terakhir_text ?></div>
          </div>

          <div class="col-md">
            <small class="text-muted">Jurusita</small>
            <?php foreach ($b->perkara_jurusita as $jurusita) : ?>
              <div><?= $jurusita->jurusita_nama ?></div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- <div class="border border-primary rounded p-3">
          <small class="text-muted">Penerima</small>
          <ol>
            <?php foreach ($b->pemberitahuan_putusan->where('pihak', 1) as $pp) : ?>
              <li>
                <div class="fw-semibold"><?= $pp->pihak_1->nama ?></div>
                <small class="text-muted d-block">
                  <?= $pp->pihak_1->alamat ?>
                </small>
              </li>
            <?php endforeach; ?>
          </ol>
          <ol>
            <?php foreach ($b->pemberitahuan_putusan->where('pihak', 2) as $pp) : ?>
              <li>
                <div class="fw-semibold"><?= $pp->pihak_2->nama ?></div>
                <small class="text-muted d-block">
                  <?= $pp->pihak_2->alamat ?>
                </small>
              </li>
            <?php endforeach; ?>
          </ol>
        </div> -->
      </div>
      <table class="table table-bordered table-hover">
        <thead class="bg-warning bg-opacity-25 ">
          <tr>
            <th>No</th>
            <th>Transaksi</th>
            <th>Tanggal</th>
            <th>Pihak</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($b->perkara_transaksi as $n => $pt) { ?>
            <tr></tr>
            <td><?= $n + 1 ?></td>
            <td><?= $pt->uraian ?></td>
            <td><?= tanggal_indo($pt->tanggal_transaksi) ?></td>
            <td><?= $pt->detail_pihak->nama ?? null ?></td>
            <td>Rp <?= number_format($pt->jumlah, 0, ',', '.') ?></td>
            </tr>
          <?php } ?>
          <?php if (count($b->perkara_transaksi) == 0) { ?>
            <tr>
              <td colspan="5" class="text-center">Belum ada instrumen panggilan/Putusan tidak verstek</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>